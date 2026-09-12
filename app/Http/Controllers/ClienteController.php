<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreClienteRequest;
use App\Http\Requests\UpdateClienteRequest;
use App\Models\Cliente;
use App\Models\Pessoa;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ClienteController extends Controller
{
    public function index(): View
    {
        $clientes = Cliente::query()
            ->with('pessoa')
            ->orderBy('pessoa_codigo')
            ->paginate(10);

        return view('clientes.index', [
            'clientes' => $clientes,
        ]);
    }

    public function create(): View
    {
        return view('clientes.create', [
            'cliente' => new Cliente(['nivel_fidelidade' => 1]),
            'pessoa' => new Pessoa(['status' => 1]),
        ]);
    }

    public function store(StoreClienteRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $cliente = DB::transaction(function () use ($data): Cliente {
            $pessoa = Pessoa::create([
                'nome' => $data['nome'],
                'cpf' => $data['cpf'],
                'status' => 1,
            ]);

            return Cliente::create([
                'pessoa_codigo' => $pessoa->codigo,
                'nivel_fidelidade' => 1,
            ]);
        });

        return redirect()
            ->route('clientes.show', $cliente)
            ->with('status', 'Cliente criado com sucesso.');
    }

    public function show(Cliente $cliente): View
    {
        $cliente->load('pessoa');

        return view('clientes.show', [
            'cliente' => $cliente,
        ]);
    }

    public function edit(Cliente $cliente): View
    {
        $cliente->load('pessoa');

        return view('clientes.edit', [
            'cliente' => $cliente,
            'pessoa' => $cliente->pessoa,
        ]);
    }

    public function update(UpdateClienteRequest $request, Cliente $cliente): RedirectResponse
    {
        $cliente->pessoa()->update($request->validated());

        return redirect()
            ->route('clientes.show', $cliente)
            ->with('status', 'Cliente atualizado com sucesso.');
    }

    public function destroy(Cliente $cliente): RedirectResponse
    {
        $cliente->pessoa()->update(['status' => 2]);

        return redirect()
            ->route('clientes.index')
            ->with('status', 'Cliente inativado com sucesso.');
    }
}
