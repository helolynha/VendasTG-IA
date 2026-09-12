@extends('layouts.app')

@section('content')
    <section class="vstack gap-4">
        <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-3">
            <div>
                <span class="text-uppercase text-secondary small fw-semibold">Usuario</span>
                <h1 class="h3 fw-semibold mt-1 mb-0">{{ $usuario->email }}</h1>
            </div>

            <div class="d-flex gap-2">
                <a href="{{ route('usuarios.index') }}" class="btn btn-outline-secondary">
                    Voltar
                </a>
                @if (auth()->user()->isAdm())
                    <a href="{{ route('usuarios.edit', $usuario) }}" class="btn btn-primary">
                        Editar
                    </a>
                @endif
            </div>
        </div>

        <div class="row g-4">
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-4">
                        <h2 class="h5 fw-semibold mb-4">Dados do usuario</h2>
                        <dl class="row gy-3 mb-0">
                            <div class="col-12">
                                <dt class="text-secondary fw-medium">Codigo</dt>
                                <dd class="mb-0">{{ $usuario->codigo }}</dd>
                            </div>
                            <div class="col-12">
                                <dt class="text-secondary fw-medium">Email</dt>
                                <dd class="mb-0">{{ $usuario->email }}</dd>
                            </div>
                            <div class="col-12">
                                <dt class="text-secondary fw-medium">Nivel</dt>
                                <dd class="mb-0">{{ $usuario->nivel }}</dd>
                            </div>
                            <div class="col-12">
                                <dt class="text-secondary fw-medium">Status</dt>
                                <dd class="mb-0">
                                    <span class="badge {{ $usuario->status === 1 ? 'text-bg-success' : 'text-bg-secondary' }}">
                                        {{ $usuario->status === 1 ? 'Ativo' : 'Inativo' }}
                                    </span>
                                </dd>
                            </div>
                        </dl>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-4">
                        <h2 class="h5 fw-semibold mb-4">Vendedor vinculado</h2>
                        <dl class="row gy-3 mb-0">
                            <div class="col-12">
                                <dt class="text-secondary fw-medium">Codigo da pessoa</dt>
                                <dd class="mb-0">{{ $usuario->vendedor?->pessoa_codigo ?? 'Nao informado' }}</dd>
                            </div>
                            <div class="col-12">
                                <dt class="text-secondary fw-medium">Nome</dt>
                                <dd class="mb-0">{{ $usuario->vendedor?->pessoa?->nome ?? 'Nao informado' }}</dd>
                            </div>
                            <div class="col-12">
                                <dt class="text-secondary fw-medium">CPF</dt>
                                <dd class="mb-0">{{ $usuario->vendedor?->pessoa?->cpf ?? 'Nao informado' }}</dd>
                            </div>
                            <div class="col-12">
                                <dt class="text-secondary fw-medium">Salario</dt>
                                <dd class="mb-0">{{ $usuario->vendedor?->salario ?? 'Nao informado' }}</dd>
                            </div>
                            <div class="col-12">
                                <dt class="text-secondary fw-medium">Comissao</dt>
                                <dd class="mb-0">{{ $usuario->vendedor?->comissao ?? 'Nao informado' }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
