<?php

namespace App\Http\Requests;

use App\Models\Usuario;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreUsuarioRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() instanceof Usuario && $this->user()->isAdm();
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        return [
            'pessoa_codigo' => ['required', 'integer', Rule::exists('vendedores', 'pessoa_codigo')],
            'email' => ['required', 'email', 'max:100', Rule::unique('usuarios', 'email')],
            'password' => ['required', 'string', 'min:8', 'max:255'],
            'nivel' => ['required', Rule::in(['adm', 'normal'])],
            'status' => ['required', 'integer', Rule::in([1, 2])],
        ];
    }
}
