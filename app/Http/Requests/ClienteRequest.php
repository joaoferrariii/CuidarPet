<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ClienteRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        if ($this->cpf) {
            $this->merge([
                'cpf' => preg_replace('/\D/', '', $this->cpf),
            ]);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $clienteId = $this->route('cliente')?->id ?? $this->route('cliente');

        return [
            'nome' => ['required', 'string', 'max:50'],
            'cpf' => [
                'required',
                'string',
                'size:11',
                Rule::unique('clientes', 'cpf')->ignore($clienteId),
            ],
            'email' => [
                'required',
                'string',
                'email',
                'max:100',
                Rule::unique('clientes', 'email')->ignore($clienteId),
            ],
            'endereco' => ['required', 'string', 'max:100'],
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'nome' => 'nome do cliente',
            'cpf' => 'CPF',
            'email' => 'e-mail',
            'endereco' => 'endereço',
        ];
    }
}
