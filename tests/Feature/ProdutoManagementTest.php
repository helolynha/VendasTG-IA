<?php

namespace Tests\Feature;

use App\Models\Categoria;
use App\Models\Produto;
use App\Models\Usuario;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use PHPUnit\Framework\Attributes\TestWith;
use Tests\TestCase;

class ProdutoManagementTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        config(['database.default' => 'sqlite', 'database.connections.sqlite.database' => ':memory:']);
        DB::purge();
        DB::setDefaultConnection('sqlite');
        $this->withoutVite();

        Schema::create('categorias', function (Blueprint $table): void {
            $table->increments('codigo');
            $table->string('nome');
        });
        Schema::create('produtos', function (Blueprint $table): void {
            $table->increments('codigo');
            $table->string('descricao', 100);
            $table->decimal('preco', 10, 2);
            $table->integer('estoque');
            $table->integer('status');
            $table->unsignedInteger('categoria_codigo');
            $table->foreign('categoria_codigo')->references('codigo')->on('categorias');
        });
    }

    public function test_normal_user_can_select_all_categories_and_create_product(): void
    {
        $categoria = Categoria::create(['nome' => 'Papelaria']);
        Categoria::create(['nome' => 'Eletrônicos']);
        $this->actingAs($this->usuario())->get(route('produtos.create'))
            ->assertOk()->assertSee('Papelaria')->assertSee('Eletrônicos');

        $this->post(route('produtos.store'), $this->payload($categoria) + ['codigo' => 99, 'status' => 3])
            ->assertRedirect();

        $produto = Produto::query()->sole();
        $this->assertSame(1, $produto->status);
        $this->assertNotSame(99, $produto->codigo);
        $this->assertSame($categoria->codigo, $produto->categoria->codigo);
        $this->assertSame($produto->codigo, $categoria->produtos()->sole()->codigo);
        $this->get(route('produtos.show', $produto))->assertOk()->assertSee('Papelaria')->assertDontSee('Editar');
    }

    #[TestWith([0, 2])]
    #[TestWith([5, 1])]
    public function test_creation_sets_status_from_stock(int $estoque, int $status): void
    {
        $categoria = Categoria::create(['nome' => 'Papelaria']);
        $this->actingAs($this->usuario())->post(route('produtos.store'), [
            ...$this->payload($categoria), 'estoque' => $estoque,
        ])->assertRedirect();

        $this->assertSame($status, Produto::query()->sole()->status);
    }

    public function test_normal_user_cannot_edit_update_or_inactivate_product(): void
    {
        $produto = $this->produto();
        $this->actingAs($this->usuario())->get(route('produtos.edit', $produto))->assertForbidden();
        $this->put(route('produtos.update', $produto), ['descricao' => 'Alterado'])->assertForbidden();
        $this->delete(route('produtos.destroy', $produto))->assertForbidden();

        $this->assertSame('Lápis', $produto->refresh()->descricao);
        $this->assertSame(1, $produto->status);
    }

    public function test_admin_can_edit_product_and_zero_stock_forces_out_of_stock(): void
    {
        $produto = $this->produto();
        $categoria = Categoria::create(['nome' => 'Outra categoria']);
        $this->actingAs($this->usuario('adm'))->get(route('produtos.edit', $produto))
            ->assertOk()->assertSee('Outra categoria');

        $this->put(route('produtos.update', $produto), [
            ...$this->payload($categoria), 'descricao' => 'Atualizado', 'estoque' => 0, 'status' => 1,
        ])->assertRedirect(route('produtos.show', $produto));

        $produto->refresh();
        $this->assertSame('Lápis', $produto->descricao);
        $this->assertSame('2.00', $produto->preco);
        $this->assertSame(0, $produto->estoque);
        $this->assertSame(2, $produto->status);
        $this->assertSame($categoria->codigo, $produto->categoria_codigo);

        $this->put(route('produtos.update', $produto), [
            ...$this->payload($categoria), 'status' => 2,
        ])->assertRedirect();
        $this->assertSame(1, $produto->refresh()->status);
    }

    public function test_admin_can_update_without_description_and_cannot_change_it_in_a_forged_request(): void
    {
        $produto = $this->produto();
        $payload = $this->payload($produto->categoria);
        unset($payload['descricao']);

        $this->actingAs($this->usuario('adm'))->get(route('produtos.edit', $produto))
            ->assertSee('value="Lápis" disabled', false);

        $this->put(route('produtos.update', $produto), [...$payload, 'preco' => '3.00', 'status' => 1])
            ->assertRedirect();
        $this->assertSame('3.00', $produto->refresh()->preco);
        $this->assertSame('Lápis', $produto->descricao);

        $this->put(route('produtos.update', $produto), [
            ...$payload, 'descricao' => 'Descrição adulterada', 'status' => 1,
        ])->assertRedirect();
        $this->assertSame('Lápis', $produto->refresh()->descricao);
    }

    public function test_restocking_does_not_override_explicit_inactivation(): void
    {
        $produto = $this->produto(estoque: 0);

        $this->actingAs($this->usuario('adm'))->put(route('produtos.update', $produto), [
            ...$this->payload($produto->categoria), 'status' => 3,
        ])->assertRedirect();

        $this->assertSame(5, $produto->refresh()->estoque);
        $this->assertSame(3, $produto->status);
    }

    public function test_inactivation_preserves_product_and_blocks_all_further_changes(): void
    {
        $produto = $this->produto(estoque: 0);
        $this->actingAs($this->usuario('adm'))->delete(route('produtos.destroy', $produto))
            ->assertRedirect(route('produtos.index'));

        $this->assertModelExists($produto);
        $this->assertSame(3, $produto->refresh()->status);
        $this->get(route('produtos.show', $produto))->assertOk()->assertSee('Inativo')->assertDontSee('Editar');
        $this->get(route('produtos.index'))->assertOk()->assertSee('Lápis')->assertDontSee('Inativar');
        $this->get(route('produtos.edit', $produto))->assertForbidden();
        $this->put(route('produtos.update', $produto), [
            ...$this->payload($produto->categoria), 'status' => 1,
        ])->assertForbidden();
        $this->delete(route('produtos.destroy', $produto))->assertForbidden();
        $this->assertSame(3, $produto->refresh()->status);
        $this->assertSame(0, $produto->estoque);
    }

    public function test_normal_user_cannot_list_or_directly_view_inactive_products(): void
    {
        $ativo = $this->produto();
        $inativo = $this->produto(status: 3, descricao: 'Produto oculto');
        $this->produto(estoque: 0, descricao: 'Produto em falta');

        $this->actingAs($this->usuario())->get(route('produtos.index'))
            ->assertOk()->assertSee('Lápis')->assertSee('Produto em falta')->assertDontSee('Produto oculto')
            ->assertDontSee('Editar')->assertDontSee('Inativar');
        $this->get(route('produtos.show', $inativo))->assertNotFound();
        $this->get(route('produtos.show', $ativo))->assertOk();
    }

    #[TestWith(['preco', -1])]
    #[TestWith(['preco', 'abc'])]
    #[TestWith(['preco', '1.234'])]
    #[TestWith(['preco', '100000000'])]
    #[TestWith(['estoque', -1])]
    #[TestWith(['estoque', 1.5])]
    #[TestWith(['estoque', 2147483648])]
    #[TestWith(['categoria_codigo', 999])]
    #[TestWith(['descricao', ''])]
    public function test_invalid_product_is_not_created(string $campo, mixed $valor): void
    {
        $categoria = Categoria::create(['nome' => 'Papelaria']);
        $this->actingAs($this->usuario())->post(route('produtos.store'), [
            ...$this->payload($categoria), $campo => $valor,
        ])->assertSessionHasErrors($campo);

        $this->assertDatabaseCount('produtos', 0);
    }

    public function test_required_fields_and_description_length_are_validated(): void
    {
        $this->actingAs($this->usuario())->post(route('produtos.store'), [])
            ->assertSessionHasErrors(['descricao', 'preco', 'estoque', 'categoria_codigo']);
        $categoria = Categoria::create(['nome' => 'Papelaria']);
        $this->post(route('produtos.store'), [
            ...$this->payload($categoria), 'descricao' => str_repeat('a', 101),
        ])->assertSessionHasErrors('descricao');
        $this->assertDatabaseCount('produtos', 0);
    }

    public function test_invalid_status_does_not_change_product(): void
    {
        $produto = $this->produto();
        $this->actingAs($this->usuario('adm'))->put(route('produtos.update', $produto), [
            ...$this->payload($produto->categoria), 'status' => 4,
        ])->assertSessionHasErrors('status');

        $this->assertSame(1, $produto->refresh()->status);
    }

    public function test_admin_can_mark_product_inactive_through_edit(): void
    {
        $produto = $this->produto();
        $this->actingAs($this->usuario('adm'))->put(route('produtos.update', $produto), [
            ...$this->payload($produto->categoria), 'estoque' => 0, 'status' => 3,
        ])->assertRedirect();
        $this->assertSame(3, $produto->refresh()->status);
        $this->get(route('produtos.edit', $produto))->assertForbidden();
    }

    public function test_empty_categories_disable_creation_and_empty_list_is_displayed(): void
    {
        $this->actingAs($this->usuario())->get(route('produtos.create'))->assertOk()
            ->assertSee('Não há categorias cadastradas')->assertSee('disabled', false);
        $this->get(route('produtos.index'))->assertOk()->assertSee('Nenhum produto encontrado.');
    }

    public function test_product_and_category_text_are_escaped(): void
    {
        $produto = $this->produto(descricao: '<script>alert(1)</script>');
        $produto->categoria->update(['nome' => '<script>alert(2)</script>']);
        $this->actingAs($this->usuario())->get(route('produtos.index'))
            ->assertSee($produto->descricao)->assertDontSee($produto->descricao, false)
            ->assertSee($produto->categoria->nome)->assertDontSee($produto->categoria->nome, false);
        $this->get(route('produtos.show', $produto))->assertSee($produto->descricao)
            ->assertDontSee($produto->descricao, false);
    }

    public function test_guests_are_redirected_for_product_routes(): void
    {
        $produto = $this->produto();
        $this->get(route('produtos.index'))->assertRedirect(route('login'));
        $this->get(route('produtos.create'))->assertRedirect(route('login'));
        $this->get(route('produtos.show', $produto))->assertRedirect(route('login'));
        $this->get(route('produtos.edit', $produto))->assertRedirect(route('login'));
        $this->post(route('produtos.store'), [])->assertRedirect(route('login'));
        $this->put(route('produtos.update', $produto), [])->assertRedirect(route('login'));
        $this->delete(route('produtos.destroy', $produto))->assertRedirect(route('login'));
        $this->assertSame(1, $produto->refresh()->status);
    }

    private function usuario(string $nivel = 'normal'): Usuario
    {
        $usuario = new Usuario(['email' => 'teste@example.com', 'nivel' => $nivel, 'status' => 1]);
        $usuario->codigo = 1;

        return $usuario;
    }

    private function produto(int $estoque = 5, int $status = 1, string $descricao = 'Lápis'): Produto
    {
        $categoria = Categoria::create(['nome' => 'Papelaria']);

        return Produto::create([...$this->payload($categoria), 'estoque' => $estoque, 'status' => $status, 'descricao' => $descricao]);
    }

    /**
     * @return array<string, int|string>
     */
    private function payload(Categoria $categoria): array
    {
        return ['descricao' => 'Lápis', 'preco' => '2.00', 'estoque' => 5, 'categoria_codigo' => $categoria->codigo];
    }
}
