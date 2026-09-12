@extends('layouts.app')

@section('content')
    <section class="vstack gap-4">
        <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-3">
            <div>
                <span class="text-uppercase text-secondary small fw-semibold">Cliente</span>
                <h1 class="h3 fw-semibold mt-1 mb-0">{{ $cliente->pessoa?->nome ?? 'Cliente' }}</h1>
            </div>

            <div class="d-flex gap-2">
                <a href="{{ route('clientes.index') }}" class="btn btn-outline-secondary">
                    Voltar
                </a>
                @if (auth()->user()->isAdm())
                    <a href="{{ route('clientes.edit', $cliente) }}" class="btn btn-primary">
                        Editar
                    </a>
                @endif
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <h2 class="h5 fw-semibold mb-4">Dados do cliente</h2>
                <dl class="row gy-3 mb-0">
                    <div class="col-md-6">
                        <dt class="text-secondary fw-medium">Codigo da pessoa</dt>
                        <dd class="mb-0">{{ $cliente->pessoa_codigo }}</dd>
                    </div>
                    <div class="col-md-6">
                        <dt class="text-secondary fw-medium">Nome</dt>
                        <dd class="mb-0">{{ $cliente->pessoa?->nome ?? 'Nao informado' }}</dd>
                    </div>
                    <div class="col-md-6">
                        <dt class="text-secondary fw-medium">CPF</dt>
                        <dd class="mb-0">{{ $cliente->pessoa?->cpf ?? 'Nao informado' }}</dd>
                    </div>
                    <div class="col-md-6">
                        <dt class="text-secondary fw-medium">Nivel de fidelidade</dt>
                        <dd class="mb-0">{{ $cliente->nivelFidelidadeLabel() }}</dd>
                    </div>
                    <div class="col-md-6">
                        <dt class="text-secondary fw-medium">Status</dt>
                        <dd class="mb-0">
                            <span class="badge {{ $cliente->pessoa?->status === 1 ? 'text-bg-success' : 'text-bg-secondary' }}">
                                {{ $cliente->pessoa?->status === 1 ? 'Ativo' : 'Inativo' }}
                            </span>
                        </dd>
                    </div>
                </dl>
            </div>
        </div>
    </section>
@endsection
