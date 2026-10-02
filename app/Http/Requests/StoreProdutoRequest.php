<?php

namespace App\Http\Requests;

use App\Models\Usuario;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreProdutoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() instanceof Usuario;
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'descricao' => ['required', 'string', 'max:100'],
            'preco' => ['required', 'numeric', 'min:0', 'max:99999999.99', 'decimal:0,2'],
            'estoque' => ['required', 'integer', 'min:0', 'max:2147483647'],
            'categoria_codigo' => ['required', 'integer', Rule::exists('categorias', 'codigo')],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'required' => 'O campo :attribute é obrigatório.',
            'descricao.string' => 'A descrição deve ser um texto.',
            'descricao.max' => 'A descrição deve ter no máximo 100 caracteres.',
            'preco.numeric' => 'Informe um preço válido.',
            'preco.min' => 'O preço não pode ser negativo.',
            'preco.max' => 'O preço deve ser de no máximo 99999999.99.',
            'preco.decimal' => 'O preço deve ter no máximo duas casas decimais.',
            'estoque.integer' => 'O estoque deve ser um número inteiro.',
            'estoque.min' => 'O estoque não pode ser negativo.',
            'estoque.max' => 'O estoque deve ser de no máximo 2147483647.',
            'categoria_codigo.integer' => 'Selecione uma categoria válida.',
            'categoria_codigo.exists' => 'A categoria selecionada não existe.',
            'status.integer' => 'Selecione um status válido.',
            'status.in' => 'Selecione um status válido.',
        ];
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'descricao' => 'descrição',
            'preco' => 'preço',
            'estoque' => 'estoque',
            'categoria_codigo' => 'categoria',
            'status' => 'status',
        ];
    }
}
