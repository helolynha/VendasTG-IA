<?php

namespace Tests\Feature;

use App\Models\Usuario;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class UsuarioManagementTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        config(['database.default' => 'sqlite']);
        config(['database.connections.sqlite.database' => ':memory:']);
        DB::purge();
        DB::setDefaultConnection('sqlite');

        $this->withoutVite();
        $this->createDatabaseSchema();
    }

    public function test_login_page_is_the_initial_screen(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('Entrar no sistema');
    }

    public function test_active_user_can_login_and_see_user_menu(): void
    {
        $usuario = $this->createUsuario(email: 'admin@example.com', nivel: 'adm');

        $this->post('/login', [
            'email' => $usuario->email,
            'password' => 'password123',
        ])->assertRedirect(route('dashboard'));

        $this->assertAuthenticatedAs($usuario);

        $this->get(route('dashboard'))
            ->assertOk()
            ->assertSee('Usuarios');
    }

    public function test_inactive_user_cannot_login(): void
    {
        $usuario = $this->createUsuario(email: 'inactive@example.com', status: 2);

        $this->post('/login', [
            'email' => $usuario->email,
            'password' => 'password123',
        ])->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_normal_user_can_list_and_view_users_but_cannot_modify_them(): void
    {
        $normal = $this->createUsuario(email: 'normal@example.com', nivel: 'normal');
        $managed = $this->createUsuario(email: 'managed@example.com', pessoaCodigo: 2);

        $this->actingAs($normal)
            ->get(route('usuarios.index'))
            ->assertOk()
            ->assertSee('managed@example.com')
            ->assertDontSee('Novo usuario');

        $this->actingAs($normal)
            ->get(route('usuarios.show', $managed))
            ->assertOk()
            ->assertSee('Vendedor 2')
            ->assertDontSee('Editar');

        $this->actingAs($normal)
            ->get(route('usuarios.create'))
            ->assertForbidden();

        $this->actingAs($normal)
            ->delete(route('usuarios.destroy', $managed))
            ->assertForbidden();
    }

    public function test_admin_can_create_update_and_inactivate_users(): void
    {
        $admin = $this->createUsuario(email: 'admin@example.com', nivel: 'adm');
        $this->createVendedor(2);

        $this->actingAs($admin)
            ->post(route('usuarios.store'), [
                'pessoa_codigo' => 2,
                'email' => 'new@example.com',
                'password' => 'password123',
                'nivel' => 'normal',
                'status' => 1,
            ])
            ->assertRedirect();

        $usuario = Usuario::where('email', 'new@example.com')->firstOrFail();

        $this->assertTrue(Hash::check('password123', $usuario->senha_hash));

        $this->actingAs($admin)
            ->put(route('usuarios.update', $usuario), [
                'pessoa_codigo' => 2,
                'email' => 'updated@example.com',
                'password' => '',
                'nivel' => 'adm',
                'status' => 1,
            ])
            ->assertRedirect(route('usuarios.show', $usuario));

        $usuario->refresh();

        $this->assertSame('updated@example.com', $usuario->email);
        $this->assertSame('adm', $usuario->nivel);

        $this->actingAs($admin)
            ->delete(route('usuarios.destroy', $usuario))
            ->assertRedirect(route('usuarios.index'));

        $this->assertSame(2, $usuario->refresh()->status);
    }

    private function createDatabaseSchema(): void
    {
        Schema::dropIfExists('usuarios');
        Schema::dropIfExists('vendedores');
        Schema::dropIfExists('pessoas');

        Schema::create('pessoas', function (Blueprint $table): void {
            $table->integer('codigo')->primary();
            $table->string('nome', 50);
            $table->string('cpf', 15)->unique();
            $table->integer('status')->nullable();
        });

        Schema::create('vendedores', function (Blueprint $table): void {
            $table->integer('pessoa_codigo')->primary();
            $table->decimal('salario', 10, 2);
            $table->decimal('comissao', 10, 2)->nullable()->default(0);
        });

        Schema::create('usuarios', function (Blueprint $table): void {
            $table->integer('codigo')->primary();
            $table->integer('pessoa_codigo');
            $table->string('email', 100)->unique();
            $table->string('senha_hash', 255);
            $table->string('nivel', 20);
            $table->integer('status')->default(1);
        });
    }

    private function createUsuario(
        string $email = 'user@example.com',
        string $nivel = 'normal',
        int $status = 1,
        int $pessoaCodigo = 1,
    ): Usuario {
        $this->createVendedor($pessoaCodigo);

        return Usuario::create([
            'codigo' => $pessoaCodigo,
            'pessoa_codigo' => $pessoaCodigo,
            'email' => $email,
            'senha_hash' => Hash::make('password123'),
            'nivel' => $nivel,
            'status' => $status,
        ]);
    }

    private function createVendedor(int $pessoaCodigo): void
    {
        if (Schema::getConnection()->table('pessoas')->where('codigo', $pessoaCodigo)->exists()) {
            return;
        }

        Schema::getConnection()->table('pessoas')->insert([
            'codigo' => $pessoaCodigo,
            'nome' => 'Vendedor '.$pessoaCodigo,
            'cpf' => '000.000.000-'.$pessoaCodigo,
            'status' => 1,
        ]);

        Schema::getConnection()->table('vendedores')->insert([
            'pessoa_codigo' => $pessoaCodigo,
            'salario' => 2500,
            'comissao' => 0,
        ]);
    }
}
