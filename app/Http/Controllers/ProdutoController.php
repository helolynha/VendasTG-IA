<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProdutoRequest;
use App\Http\Requests\UpdateProdutoRequest;
use App\Models\Categoria;
use App\Models\Produto;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProdutoController extends Controller
{
    public function index(Request $request): View
    {
        $produtos = Produto::query()->with('categoria')
            ->when(! $request->user()->isAdm(), fn ($query) => $query->where('status', '!=', 3))
            ->orderBy('codigo')->paginate(10);

        return view('produtos.index', compact('produtos'));
    }

    public function create(): View
    {
        return view('produtos.create', [
            'produto' => new Produto(['status' => 1, 'estoque' => 0]),
            'categorias' => Categoria::query()->orderBy('nome')->get(),
        ]);
    }

    public function store(StoreProdutoRequest $request): RedirectResponse
    {
        $produto = Produto::create([...$request->validated(), 'status' => 1]);

        return redirect()->route('produtos.show', $produto)->with('status', 'Produto criado com sucesso.');
    }

    public function show(Request $request, Produto $produto): View
    {
        abort_if($produto->status === 3 && ! $request->user()->isAdm(), 404);
        $produto->load('categoria');

        return view('produtos.show', compact('produto'));
    }

    public function edit(Produto $produto): View
    {
        abort_if($produto->status === 3, 403);

        return view('produtos.edit', [
            'produto' => $produto,
            'categorias' => Categoria::query()->orderBy('nome')->get(),
        ]);
    }

    public function update(UpdateProdutoRequest $request, Produto $produto): RedirectResponse
    {
        $produto->update($request->validated());

        return redirect()->route('produtos.show', $produto)->with('status', 'Produto atualizado com sucesso.');
    }

    public function destroy(Produto $produto): RedirectResponse
    {
        abort_if($produto->status === 3, 403);
        $produto->update(['status' => 3]);

        return redirect()->route('produtos.index')->with('status', 'Produto inativado com sucesso.');
    }
}
