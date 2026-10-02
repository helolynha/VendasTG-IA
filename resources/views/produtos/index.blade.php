@extends('layouts.app')
@section('content')
    <section class="vstack gap-4">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
            <h1 class="h3 fw-semibold mb-0">Produtos</h1>
            <a href="{{ route('produtos.create') }}" class="btn btn-primary">Novo produto</a>
        </div>
        <div class="card border-0 shadow-sm">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr><th scope="col">Código</th><th scope="col">Descrição</th><th scope="col">Categoria</th><th scope="col">Preço</th><th scope="col">Estoque</th><th scope="col">Status</th><th scope="col" class="text-end">Ações</th></tr>
                    </thead>
                    <tbody>
                        @forelse ($produtos as $produto)
                            <tr>
                                <td>{{ $produto->codigo }}</td>
                                <td class="fw-semibold">{{ $produto->descricao }}</td>
                                <td>{{ $produto->categoria?->nome ?? 'Não informado' }}</td>
                                <td class="text-nowrap">R$ {{ number_format((float) $produto->preco, 2, ',', '.') }}</td>
                                <td>{{ $produto->estoque }}</td>
                                <td><span class="badge {{ $produto->status === 1 ? 'text-bg-success' : ($produto->status === 2 ? 'text-bg-warning' : 'text-bg-secondary') }}">{{ $produto->statusLabel() }}</span></td>
                                <td>
                                    <div class="d-flex justify-content-end gap-2">
                                        <a href="{{ route('produtos.show', $produto) }}" class="btn btn-outline-secondary btn-sm">Ver</a>
                                        @if (auth()->user()->isAdm() && $produto->status !== 3)
                                            <a href="{{ route('produtos.edit', $produto) }}" class="btn btn-outline-primary btn-sm">Editar</a>
                                            <form method="POST" action="{{ route('produtos.destroy', $produto) }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger btn-sm">Inativar</button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="py-4 text-center text-secondary">Nenhum produto encontrado.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div>{{ $produtos->links('pagination::bootstrap-5') }}</div>
    </section>
@endsection
