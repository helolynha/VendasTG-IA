@csrf

<div class="row g-3">
    <div class="col-12">
        <label for="pessoa_codigo" class="form-label">Vendedor</label>
        <select id="pessoa_codigo" name="pessoa_codigo" required class="form-select @error('pessoa_codigo') is-invalid @enderror">
            <option value="">Selecione um vendedor</option>
            @foreach ($vendedores as $vendedor)
                <option value="{{ $vendedor->pessoa_codigo }}" @selected((int) old('pessoa_codigo', $usuario->pessoa_codigo) === $vendedor->pessoa_codigo)>
                    {{ $vendedor->pessoa?->nome ?? 'Vendedor #'.$vendedor->pessoa_codigo }} - codigo {{ $vendedor->pessoa_codigo }}
                </option>
            @endforeach
        </select>
        @error('pessoa_codigo')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-12">
        <label for="email" class="form-label">Email</label>
        <input id="email" name="email" type="email" value="{{ old('email', $usuario->email) }}" required class="form-control @error('email') is-invalid @enderror">
        @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="password" class="form-label">Senha</label>
        <input id="password" name="password" type="password" @if (! $usuario->exists) required @endif class="form-control @error('password') is-invalid @enderror">
        @if ($usuario->exists)
            <div class="form-text">Deixe em branco para manter a senha atual.</div>
        @endif
        @error('password')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="nivel" class="form-label">Nivel</label>
        <select id="nivel" name="nivel" required class="form-select @error('nivel') is-invalid @enderror">
            <option value="normal" @selected(old('nivel', $usuario->nivel) === 'normal')>Normal</option>
            <option value="adm" @selected(old('nivel', $usuario->nivel) === 'adm')>Administrador</option>
        </select>
        @error('nivel')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="status" class="form-label">Status</label>
        <select id="status" name="status" required class="form-select @error('status') is-invalid @enderror">
            <option value="1" @selected((int) old('status', $usuario->status ?? 1) === 1)>Ativo</option>
            <option value="2" @selected((int) old('status', $usuario->status ?? 1) === 2)>Inativo</option>
        </select>
        @error('status')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="d-flex flex-wrap align-items-center gap-2 mt-4">
    <button type="submit" class="btn btn-primary">
        Salvar
    </button>
    <a href="{{ route('usuarios.index') }}" class="btn btn-outline-secondary">
        Cancelar
    </a>
</div>
