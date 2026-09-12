<?php

namespace Tests\Feature;

use App\Models\Cliente;
use App\Models\Usuario;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class ClienteManagementTest extends TestCase
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

    public function test_normal_user_can_create_list_and_view_clients(): void
    {
        $normal = $this->createUsuario(email: 'normal@example.com', nivel: 'normal');
        $cliente = $this->createCliente(nome: 'Cliente Existente', cpf: '111.111.111-11');

        $this->actingAs($normal)
            ->get(route('clientes.index'))
            ->assertOk()
            ->assertSee('Cliente Existente')
            ->assertSee('Novo cliente')
            ->assertDontSee('Editar')
            ->assertDontSee('Inativar');

        $this->actingAs($normal)
            ->get(route('clientes.show', $cliente))
            ->assertOk()
            ->assertSee('Cliente Existente')
            ->assertDontSee('Editar');

        $this->actingAs($normal)
            ->post(route('clientes.store'), [
                'nome' => 'Cliente Novo',
                'cpf' => '222.222.222-22',
            ])
            ->assertRedirect();

        $novoCliente = Cliente::whereHas('pessoa', function ($query): void {
            $query->where('cpf', '222.222.222-22');
        })->firstOrFail();

        $this->assertSame(1, $novoCliente->nivel_fidelidade);
        $this->assertSame(1, $novoCliente->pessoa->status);
    }

    public function test_client_list_displays_loyalty_level_labels(): void
    {
        $normal = $this->createUsuario(email: 'normal@example.com', nivel: 'normal');
        $this->createCliente(nome: 'Cliente Prata', cpf: '888.888.888-88', nivelFidelidade: 2);
        $this->createCliente(nome: 'Cliente Ouro', cpf: '999.999.999-99', nivelFidelidade: 3);

        $this->actingAs($normal)
            ->get(route('clientes.index'))
            ->assertOk()
            ->assertSee('Cliente Prata')
            ->assertSee('Prata')
            ->assertSee('Cliente Ouro')
            ->assertSee('Ouro');
    }

    public function test_normal_user_cannot_update_or_inactivate_clients(): void
    {
        $normal = $this->createUsuario(email: 'normal@example.com', nivel: 'normal');
        $cliente = $this->createCliente(nome: 'Cliente Protegido', cpf: '333.333.333-33');

        $this->actingAs($normal)
            ->get(route('clientes.edit', $cliente))
            ->assertForbidden();

        $this->actingAs($normal)
            ->put(route('clientes.update', $cliente), [
                'nome' => 'Cliente Alterado',
                'cpf' => '444.444.444-44',
                'status' => 1,
            ])
            ->assertForbidden();

        $this->actingAs($normal)
            ->delete(route('clientes.destroy', $cliente))
            ->assertForbidden();

        $cliente->refresh()->load('pessoa');

        $this->assertSame('Cliente Protegido', $cliente->pessoa->nome);
        $this->assertSame('333.333.333-33', $cliente->pessoa->cpf);
        $this->assertSame(1, $cliente->pessoa->status);
    }

    public function test_admin_can_update_and_logically_delete_clients(): void
    {
        $admin = $this->createUsuario(email: 'admin@example.com', nivel: 'adm');
        $cliente = $this->createCliente(nome: 'Cliente Antigo', cpf: '555.555.555-55');

        $this->actingAs($admin)
            ->put(route('clientes.update', $cliente), [
                'nome' => 'Cliente Atualizado',
                'cpf' => '666.666.666-66',
                'status' => 1,
            ])
            ->assertRedirect(route('clientes.show', $cliente));

        $cliente->refresh()->load('pessoa');

        $this->assertSame('Cliente Atualizado', $cliente->pessoa->nome);
        $this->assertSame('666.666.666-66', $cliente->pessoa->cpf);
        $this->assertSame(1, $cliente->pessoa->status);

        $this->actingAs($admin)
            ->delete(route('clientes.destroy', $cliente))
            ->assertRedirect(route('clientes.index'));

        $cliente->refresh()->load('pessoa');

        $this->assertModelExists($cliente);
        $this->assertSame(2, $cliente->pessoa->status);
    }

    public function test_client_cpf_must_be_unique(): void
    {
        $normal = $this->createUsuario(email: 'normal@example.com', nivel: 'normal');
        $this->createCliente(nome: 'Cliente Existente', cpf: '777.777.777-77');

        $this->actingAs($normal)
            ->post(route('clientes.store'), [
                'nome' => 'Cliente Duplicado',
                'cpf' => '777.777.777-77',
            ])
            ->assertSessionHasErrors('cpf');
    }

    private function createDatabaseSchema(): void
    {
        Schema::dropIfExists('usuarios');
        Schema::dropIfExists('clientes');
        Schema::dropIfExists('vendedores');
        Schema::dropIfExists('pessoas');

        Schema::create('pessoas', function (Blueprint $table): void {
            $table->increments('codigo');
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

        Schema::create('clientes', function (Blueprint $table): void {
            $table->integer('pessoa_codigo')->primary();
            $table->integer('nivel_fidelidade');
        });
    }

    private function createUsuario(
        string $email = 'user@example.com',
        string $nivel = 'normal',
        int $status = 1,
        int $pessoaCodigo = 100,
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
        if (DB::table('pessoas')->where('codigo', $pessoaCodigo)->exists()) {
            return;
        }

        DB::table('pessoas')->insert([
            'codigo' => $pessoaCodigo,
            'nome' => 'Vendedor '.$pessoaCodigo,
            'cpf' => '000.000.'.$pessoaCodigo,
            'status' => 1,
        ]);

        DB::table('vendedores')->insert([
            'pessoa_codigo' => $pessoaCodigo,
            'salario' => 2500,
            'comissao' => 0,
        ]);
    }

    private function createCliente(string $nome, string $cpf, int $nivelFidelidade = 1): Cliente
    {
        $pessoaCodigo = DB::table('pessoas')->insertGetId([
            'nome' => $nome,
            'cpf' => $cpf,
            'status' => 1,
        ]);

        return Cliente::create([
            'pessoa_codigo' => $pessoaCodigo,
            'nivel_fidelidade' => $nivelFidelidade,
        ]);
    }
}
