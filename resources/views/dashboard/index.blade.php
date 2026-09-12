@extends('layouts.app')

@section('content')
    <section class="vstack gap-4">
        <div>
            <span class="text-uppercase text-secondary small fw-semibold">Painel</span>
            <h1 class="h3 fw-semibold mt-1 mb-0">Bem-vindo, {{ auth()->user()->email }}</h1>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <p class="mb-0 text-secondary">Use o menu para acessar o gerenciamento de clientes e usuarios.</p>
            </div>
        </div>
    </section>
@endsection
