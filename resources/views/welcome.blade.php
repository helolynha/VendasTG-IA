<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'VendasTG') }}</title>

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9Oer+R4zWReHsu8H2nA6j3h6Iw1p5Y8h3y8VnKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous">

        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endif
    </head>
    <body class="bg-body-tertiary">
        <main class="min-vh-100 d-flex align-items-center py-5">
            <section class="container">
                <div class="row justify-content-center text-center">
                    <div class="col-12 col-md-8 col-lg-6">
                        <span class="badge text-bg-primary mb-3">VendasTG</span>
                        <h1 class="display-6 fw-semibold mb-3">Sistema de gerenciamento de vendas</h1>
                        <p class="lead text-secondary mb-4">Acesse o painel para gerenciar clientes e usuarios.</p>

                        @if (Route::has('login'))
                            @auth
                                <a href="{{ url('/dashboard') }}" class="btn btn-primary btn-lg">Ir para o painel</a>
                            @else
                                <a href="{{ route('login') }}" class="btn btn-primary btn-lg">Entrar</a>
                            @endauth
                        @endif
                    </div>
                </div>
            </section>
        </main>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    </body>
</html>
