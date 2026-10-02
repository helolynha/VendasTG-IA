@extends('layouts.app')

@section('content')
    <section class="vstack gap-4">
        <div class="dashboard-hero">
            <span class="eyebrow">Seu espaço de trabalho</span>
            <h1 class="fw-semibold mt-3 mb-3">Tudo pronto para um novo dia.</h1>
            <p class="mb-0">Organize seu catálogo, acompanhe seus clientes e mantenha sua equipe conectada. Escolha uma área para começar.</p>
        </div>
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
            <h2 class="h5 fw-semibold mb-0">Acesso rápido</h2>
            <span class="text-secondary small">{{ auth()->user()->isAdm() ? 'Perfil administrador' : 'Perfil de consulta e cadastro' }}</span>
        </div>
        <div class="row g-4">
            <div class="col-md-4">
                <a href="{{ route('produtos.index') }}" class="module-card">
                    <span class="module-symbol" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><path d="m12 3 9 5v8l-9 5-9-5V8l9-5Z M3 8l9 5 9-5 M12 13v8 M7.5 5.5l9 5"/></svg></span>
                    <h3 class="h5 fw-semibold">Produtos</h3>
                    <p class="mb-0">Consulte seu catálogo, confira o estoque e cadastre novos produtos.</p>
                    <span class="module-action">Acessar produtos <span aria-hidden="true">→</span></span>
                </a>
            </div>
            <div class="col-md-4">
                <a href="{{ route('clientes.index') }}" class="module-card">
                    <span class="module-symbol" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><circle cx="12" cy="8" r="4"/><path d="M4 21v-2a8 8 0 0 1 16 0v2"/></svg></span>
                    <h3 class="h5 fw-semibold">Clientes</h3>
                    <p class="mb-0">Encontre os dados dos clientes e acompanhe seus níveis de fidelidade.</p>
                    <span class="module-action">Acessar clientes <span aria-hidden="true">→</span></span>
                </a>
            </div>
            <div class="col-md-4">
                <a href="{{ route('usuarios.index') }}" class="module-card">
                    <span class="module-symbol" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><circle cx="9" cy="8" r="3"/><path d="M3 21v-3a6 6 0 0 1 12 0v3 M16 5a3 3 0 0 1 0 6 M18 15a5 5 0 0 1 3 5v1"/></svg></span>
                    <h3 class="h5 fw-semibold">Usuários</h3>
                    <p class="mb-0">Consulte a equipe e os perfis de acesso ao sistema.</p>
                    <span class="module-action">Acessar usuários <span aria-hidden="true">→</span></span>
                </a>
            </div>
        </div>
    </section>
@endsection
