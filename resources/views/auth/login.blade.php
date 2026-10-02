@extends('layouts.app')

@section('content')
    <main class="min-vh-100 d-flex align-items-center login-page py-5">
        <section class="container login-shell">
            <div class="row g-4 align-items-center justify-content-center">
                <div class="col-lg-6 d-none d-lg-block">
                    <div class="login-intro">
                        <div class="d-flex align-items-center gap-3 mb-5"><span class="brand-mark" aria-hidden="true">TG</span><span class="fw-semibold">VendasTG</span></div>
                        <h2 class="fw-semibold mb-4">Sua operação.<br>Mais organizada.</h2>
                        <p class="mb-5">Um só lugar para cuidar dos produtos, dos clientes e das pessoas que fazem suas vendas acontecerem.</p>
                        <span class="eyebrow">Gestão comercial simplificada</span>
                    </div>
                </div>
                <div class="col-12 col-sm-10 col-md-8 col-lg-6">
                    <div class="card border-0 shadow-sm login-card">
                        <div class="card-body p-4 p-md-5">
                            <div class="mb-4">
                                <span class="badge text-bg-primary mb-3">VendasTG</span>
                                <h1 class="h3 fw-semibold mb-2">Entrar no sistema</h1><p class="text-secondary small mb-0">Acesse sua conta para continuar.</p>
                            </div>

                            <form method="POST" action="{{ route('login.store') }}" class="vstack gap-3">
                                @csrf

                                <div>
                                    <label for="email" class="form-label">Email</label>
                                    <input id="email" name="email" type="email" value="{{ old('email') }}" autocomplete="email" autofocus required class="form-control @error('email') is-invalid @enderror">
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div>
                                    <label for="password" class="form-label">Senha</label>
                                    <input id="password" name="password" type="password" autocomplete="current-password" required class="form-control @error('password') is-invalid @enderror">
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <button type="submit" class="btn btn-primary w-100">
                                    Entrar
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection
