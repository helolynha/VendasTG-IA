@extends('layouts.app')

@section('content')
    <section class="vstack gap-4">
        <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-3">
            <div>
                <span class="text-uppercase text-secondary small fw-semibold">Gerenciamento</span>
                <h1 class="h3 fw-semibold mt-1 mb-0">Usuarios</h1>
            </div>

            @if (auth()->user()->isAdm())
                <a href="{{ route('usuarios.create') }}" class="btn btn-primary">
                    Novo usuario
                </a>
            @endif
        </div>

        <div class="card border-0 shadow-sm">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th scope="col">Email</th>
                            <th scope="col">Nivel</th>
                            <th scope="col">Status</th>
                            <th scope="col">Vendedor</th>
                            <th scope="col" class="text-end">Acoes</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($usuarios as $usuario)
                            <tr>
                                <td class="fw-semibold">{{ $usuario->email }}</td>
                                <td class="text-secondary">{{ $usuario->nivel }}</td>
                                <td>
                                    <span class="badge {{ $usuario->status === 1 ? 'text-bg-success' : 'text-bg-secondary' }}">
                                        {{ $usuario->status === 1 ? 'Ativo' : 'Inativo' }}
                                    </span>
                                </td>
                                <td class="text-secondary">{{ $usuario->vendedor?->pessoa?->nome ?? 'Sem vendedor vinculado' }}</td>
                                <td>
                                    <div class="d-flex justify-content-end gap-2">
                                        <a href="{{ route('usuarios.show', $usuario) }}" class="btn btn-outline-secondary btn-sm">
                                            Ver
                                        </a>
                                        @if (auth()->user()->isAdm())
                                            <a href="{{ route('usuarios.edit', $usuario) }}" class="btn btn-outline-primary btn-sm">
                                                Editar
                                            </a>
                                            @if ($usuario->status === 1)
                                                <form method="POST" action="{{ route('usuarios.destroy', $usuario) }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-outline-danger btn-sm">
                                                        Inativar
                                                    </button>
                                                </form>
                                            @endif
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-4 text-center text-secondary">Nenhum usuario encontrado.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div>
            {{ $usuarios->links('pagination::bootstrap-5') }}
        </div>
    </section>
@endsection
