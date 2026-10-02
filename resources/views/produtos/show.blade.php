@extends('layouts.app')
@section('content')
    <section class="vstack gap-4">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
            <h1 class="h3 fw-semibold mb-0">{{ $produto->descricao }}</h1>
            <div class="d-flex gap-2">
                <a href="{{ route('produtos.index') }}" class="btn btn-outline-secondary">Voltar</a>
                @if (auth()->user()->isAdm() && $produto->status !== 3)
                    <a href="{{ route('produtos.edit', $produto) }}" class="btn btn-primary">Editar</a>
                @endif
            </div>
        </div>
        @if ($produto->status === 3)
            <div class="alert alert-secondary">Produto inativo. Suas informações não podem ser editadas.</div>
        @endif
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <dl class="row gy-3 mb-0">
                    <div class="col-md-6"><dt>Código</dt><dd>{{ $produto->codigo }}</dd></div>
                    <div class="col-md-6"><dt>Categoria</dt><dd>{{ $produto->categoria?->nome ?? 'Não informado' }}</dd></div>
                    <div class="col-md-6"><dt>Preço</dt><dd>R$ {{ number_format((float) $produto->preco, 2, ',', '.') }}</dd></div>
                    <div class="col-md-6"><dt>Estoque</dt><dd>{{ $produto->estoque }}</dd></div>
                    <div class="col-md-6"><dt>Status</dt><dd>{{ $produto->statusLabel() }}</dd></div>
                </dl>
            </div>
        </div>
    </section>
@endsection
