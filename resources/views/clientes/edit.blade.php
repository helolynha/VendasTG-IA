@extends('layouts.app')

@section('content')
    <section class="mx-auto form-page">
        <div class="mb-4">
            <span class="text-uppercase text-secondary small fw-semibold">Clientes</span>
            <h1 class="h3 fw-semibold mt-1 mb-0">Editar cliente</h1>
        </div>

        <form method="POST" action="{{ route('clientes.update', $cliente) }}" class="card border-0 shadow-sm">
            <div class="card-body p-4">
                @method('PUT')
                @include('clientes._form')
            </div>
        </form>
    </section>
@endsection
