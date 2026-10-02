@csrf
<div class="row g-3">
    <div class="col-12">
        <label for="descricao" class="form-label">Descrição</label>
        <input id="descricao" name="descricao" type="text" value="{{ $produto->exists ? $produto->descricao : old('descricao', $produto->descricao) }}" @disabled($produto->exists) required maxlength="100" class="form-control @error('descricao') is-invalid @enderror">
        @error('descricao') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-6">
        <label for="preco" class="form-label">Preço (R$)</label>
        <input id="preco" name="preco" type="number" min="0" max="99999999.99" step="0.01" value="{{ old('preco', $produto->preco) }}" required class="form-control @error('preco') is-invalid @enderror">
        @error('preco') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-6">
        <label for="estoque" class="form-label">Estoque</label>
        <input id="estoque" name="estoque" type="number" min="0" max="2147483647" step="1" value="{{ old('estoque', $produto->estoque) }}" required class="form-control @error('estoque') is-invalid @enderror">
        @error('estoque') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-6">
        <label for="categoria_codigo" class="form-label">Categoria</label>
        <select id="categoria_codigo" name="categoria_codigo" required class="form-select @error('categoria_codigo') is-invalid @enderror">
            <option value="">Selecione uma categoria</option>
            @foreach ($categorias as $categoria)
                <option value="{{ $categoria->codigo }}" @selected((string) old('categoria_codigo', $produto->categoria_codigo) === (string) $categoria->codigo)>{{ $categoria->nome }}</option>
            @endforeach
        </select>
        @error('categoria_codigo') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    @if ($produto->exists)
        <div class="col-md-6">
            <label for="status" class="form-label">Status</label>
            <select id="status" name="status" required class="form-select @error('status') is-invalid @enderror">
                <option value="1" @selected((int) old('status', $produto->status) === 1)>Ativo</option>
                <option value="2" @selected((int) old('status', $produto->status) === 2)>Em falta</option>
                <option value="3" @selected((int) old('status', $produto->status) === 3)>Inativo</option>
            </select>
            @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    @endif
</div>
<p class="text-secondary small mt-3">Estoque zerado define o status como Em falta. Ao repor o estoque de um produto em falta, ele volta a Ativo. Produtos inativos permanecem inativos e não podem mais ser editados.</p>
@if ($categorias->isEmpty())
    <div class="alert alert-warning">Não há categorias cadastradas no banco de dados. Cadastre uma categoria no banco antes de salvar um produto.</div>
@endif
<div class="d-flex flex-wrap gap-2 mt-4">
    <button type="submit" class="btn btn-primary" @disabled($categorias->isEmpty())>Salvar</button>
    <a href="{{ route('produtos.index') }}" class="btn btn-outline-secondary">Cancelar</a>
</div>
