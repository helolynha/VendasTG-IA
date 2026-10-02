<?php

namespace App\Http\Requests;

use App\Models\Produto;
use App\Models\Usuario;
use Illuminate\Validation\Rule;

class UpdateProdutoRequest extends StoreProdutoRequest
{
    public function authorize(): bool
    {
        $produto = $this->route('produto');

        return $this->user() instanceof Usuario
            && $this->user()->isAdm()
            && $produto instanceof Produto
            && $produto->status !== 3;
    }

    public function rules(): array
    {
        return [
            ...parent::rules(),
            'descricao' => ['exclude'],
            'status' => ['required', 'integer', Rule::in([1, 2, 3])],
        ];
    }
}
