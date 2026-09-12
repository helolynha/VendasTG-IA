<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUsuarioRequest;
use App\Http\Requests\UpdateUsuarioRequest;
use App\Models\Usuario;
use App\Models\Vendedor;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class UsuarioController extends Controller
{
    public function index(): View
    {
        $usuarios = Usuario::query()
            ->with('vendedor.pessoa')
            ->orderBy('email')
            ->paginate(10);

        return view('usuarios.index', [
            'usuarios' => $usuarios,
        ]);
    }

    public function create(): View
    {
        $vendedores = Vendedor::query()
            ->with('pessoa')
            ->orderBy('pessoa_codigo')
            ->get();

        return view('usuarios.create', [
            'usuario' => new Usuario(['status' => 1, 'nivel' => 'normal']),
            'vendedores' => $vendedores,
        ]);
    }

    public function store(StoreUsuarioRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['senha_hash'] = Hash::make($data['password']);
        unset($data['password']);

        $usuario = Usuario::create($data);

        return redirect()
            ->route('usuarios.show', $usuario)
            ->with('status', 'Usuario criado com sucesso.');
    }

    public function show(Usuario $usuario): View
    {
        $usuario->load('vendedor.pessoa');

        return view('usuarios.show', [
            'usuario' => $usuario,
        ]);
    }

    public function edit(Usuario $usuario): View
    {
        $vendedores = Vendedor::query()
            ->with('pessoa')
            ->orderBy('pessoa_codigo')
            ->get();

        return view('usuarios.edit', [
            'usuario' => $usuario,
            'vendedores' => $vendedores,
        ]);
    }

    public function update(UpdateUsuarioRequest $request, Usuario $usuario): RedirectResponse
    {
        $data = $request->validated();

        if (! empty($data['password'])) {
            $data['senha_hash'] = Hash::make($data['password']);
        }

        unset($data['password']);

        $usuario->update($data);

        return redirect()
            ->route('usuarios.show', $usuario)
            ->with('status', 'Usuario atualizado com sucesso.');
    }

    public function destroy(Usuario $usuario): RedirectResponse
    {
        $usuario->update(['status' => 2]);

        return redirect()
            ->route('usuarios.index')
            ->with('status', 'Usuario inativado com sucesso.');
    }
}
