@extends('layouts.app')
@section('content')
    <section class="mx-auto form-page">
        <h1 class="h3 fw-semibold mb-4">Editar produto</h1>
        <form method="POST" action="{{ route('produtos.update', $produto) }}" class="card border-0 shadow-sm">
            @method('PUT')
            <div class="card-body p-4">@include('produtos._form')</div>
        </form>
    </section>
@endsection
