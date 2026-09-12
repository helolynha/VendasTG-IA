@csrf

<div class="row g-3">
    <div class="col-12">
        <label for="nome" class="form-label">Nome</label>
        <input id="nome" name="nome" type="text" value="{{ old('nome', $pessoa->nome) }}" required maxlength="50" class="form-control @error('nome') is-invalid @enderror">
        @error('nome')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="cpf" class="form-label">CPF</label>
        <input id="cpf" name="cpf" type="text" value="{{ old('cpf', $pessoa->cpf) }}" required maxlength="15" class="form-control @error('cpf') is-invalid @enderror">
        @error('cpf')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="nivel_fidelidade" class="form-label">Nivel de fidelidade</label>
        <input id="nivel_fidelidade" type="text" value="{{ $cliente->nivelFidelidadeLabel() }}" disabled class="form-control">
    </div>

    @if ($cliente->exists)
        <div class="col-md-6">
            <label for="status" class="form-label">Status</label>
            <select id="status" name="status" required class="form-select @error('status') is-invalid @enderror">
                <option value="1" @selected((int) old('status', $pessoa->status ?? 1) === 1)>Ativo</option>
                <option value="2" @selected((int) old('status', $pessoa->status ?? 1) === 2)>Inativo</option>
            </select>
            @error('status')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    @endif
</div>

<div class="d-flex flex-wrap align-items-center gap-2 mt-4">
    <button type="submit" class="btn btn-primary">
        Salvar
    </button>
    <a href="{{ route('clientes.index') }}" class="btn btn-outline-secondary">
        Cancelar
    </a>
</div>
