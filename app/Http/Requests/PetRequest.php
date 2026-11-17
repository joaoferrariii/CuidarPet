<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PetRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'cliente_id' => ['required', 'exists:clientes,id'],
            'nome' => ['required', 'string', 'max:255'],
            'raca' => ['required', 'string', 'max:30'],
            'data_nascimento' => ['required', 'date', 'before_or_equal:today'],
            'genero' => ['required', 'in:M,F'],
            'castrado' => ['boolean'],
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
            'cliente_id' => 'tutor / cliente',
            'nome' => 'nome do pet',
            'raca' => 'raça',
            'data_nascimento' => 'data de nascimento',
            'genero' => 'gênero',
            'castrado' => 'castrado',
        ];
    }
}
