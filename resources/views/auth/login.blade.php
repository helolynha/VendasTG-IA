@extends('layouts.app')

@section('content')
    <main class="min-vh-100 d-flex align-items-center bg-body-tertiary py-5">
        <section class="container">
            <div class="row justify-content-center">
                <div class="col-12 col-sm-10 col-md-7 col-lg-5 col-xl-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-4 p-md-5">
                            <div class="mb-4">
                                <span class="badge text-bg-primary mb-3">VendasTG</span>
                                <h1 class="h3 fw-semibold mb-1">Entrar no sistema</h1>
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
