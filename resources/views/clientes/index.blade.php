@extends('layouts.app')

@section('content')
    <section class="vstack gap-4">
        <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-3">
            <div>
                <span class="text-uppercase text-secondary small fw-semibold">Gerenciamento</span>
                <h1 class="h3 fw-semibold mt-1 mb-0">Clientes</h1>
            </div>

            <a href="{{ route('clientes.create') }}" class="btn btn-primary">
                Novo cliente
            </a>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th scope="col">Nome</th>
                            <th scope="col">CPF</th>
                            <th scope="col">Fidelidade</th>
                            <th scope="col">Status</th>
                            <th scope="col" class="text-end">Acoes</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($clientes as $cliente)
                            <tr>
                                <td class="fw-semibold">{{ $cliente->pessoa?->nome ?? 'Nao informado' }}</td>
                                <td class="text-secondary">{{ $cliente->pessoa?->cpf ?? 'Nao informado' }}</td>
                                <td class="text-secondary">{{ $cliente->nivelFidelidadeLabel() }}</td>
                                <td>
                                    <span class="badge {{ $cliente->pessoa?->status === 1 ? 'text-bg-success' : 'text-bg-secondary' }}">
                                        {{ $cliente->pessoa?->status === 1 ? 'Ativo' : 'Inativo' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex justify-content-end gap-2">
                                        <a href="{{ route('clientes.show', $cliente) }}" class="btn btn-outline-secondary btn-sm">
                                            Ver
                                        </a>
                                        @if (auth()->user()->isAdm())
                                            <a href="{{ route('clientes.edit', $cliente) }}" class="btn btn-outline-primary btn-sm">
                                                Editar
                                            </a>
                                            @if ($cliente->pessoa?->status === 1)
                                                <form method="POST" action="{{ route('clientes.destroy', $cliente) }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-outline-danger btn-sm">
                                                        Inativar
                                                    </button>
                                                </form>
                                            @endif
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-4 text-center text-secondary">Nenhum cliente encontrado.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div>
            {{ $clientes->links('pagination::bootstrap-5') }}
        </div>
    </section>
@endsection
