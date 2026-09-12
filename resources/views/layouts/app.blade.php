<!DOCTYPE html>
<html lang="pt-BR">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'VendasTG') }}</title>

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9Oer+R4zWReHsu8H2nA6j3h6Iw1p5Y8h3y8VnKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous">

        @if (file_exists(public_path('hot')) || file_exists(public_path('build/manifest.json')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endif
    </head>
    <body class="bg-body-tertiary text-body">
        @auth
            <div class="min-vh-100">
                <nav class="navbar navbar-expand-lg bg-white border-bottom shadow-sm">
                    <div class="container py-2">
                        <a href="{{ route('dashboard') }}" class="navbar-brand fw-semibold text-primary">
                            VendasTG
                        </a>

                        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavigation" aria-controls="mainNavigation" aria-expanded="false" aria-label="Abrir menu">
                            <span class="navbar-toggler-icon"></span>
                        </button>

                        <div id="mainNavigation" class="collapse navbar-collapse">
                            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                                <li class="nav-item">
                                    <a href="{{ route('dashboard') }}" class="nav-link">Inicio</a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('clientes.index') }}" class="nav-link">Clientes</a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('usuarios.index') }}" class="nav-link">Usuarios</a>
                                </li>
                            </ul>

                            <div class="d-flex flex-column flex-lg-row align-items-lg-center gap-2">
                                <span class="badge text-bg-light border text-secondary fw-medium px-3 py-2">
                                    {{ auth()->user()->email }}
                                </span>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-primary btn-sm">
                                        Sair
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </nav>

                <main class="container py-4 py-lg-5">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    @yield('content')
                </main>
            </div>
        @else
            @yield('content')
        @endauth

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    </body>
</html>
