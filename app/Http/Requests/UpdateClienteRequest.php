<?php

namespace App\Http\Requests;

use App\Models\Cliente;
use App\Models\Usuario;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateClienteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() instanceof Usuario && $this->user()->isAdm();
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $cliente = $this->route('cliente');
        $pessoaCodigo = $cliente instanceof Cliente ? $cliente->pessoa_codigo : null;

        return [
            'nome' => ['required', 'string', 'max:50'],
            'cpf' => [
                'required',
                'string',
                'max:15',
                Rule::unique('pessoas', 'cpf')->ignore($pessoaCodigo, 'codigo'),
            ],
            'status' => ['required', 'integer', Rule::in([1, 2])],
        ];
    }
}
