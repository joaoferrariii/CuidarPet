<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VacinacaoRequest extends FormRequest
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
        if ($this->validade === '') {
            $this->merge(['validade' => null]);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'pet_id'    => ['required', 'exists:pets,id'],
            'vacina_id' => ['required', 'exists:vacinas,id'],
            'data'      => ['required', 'date'],
            'validade'  => ['nullable', 'date', 'after_or_equal:data'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'pet_id.required'         => 'Selecione o pet.',
            'pet_id.exists'           => 'O pet selecionado não existe no sistema.',
            'vacina_id.required'      => 'Selecione a vacina aplicada.',
            'vacina_id.exists'        => 'A vacina selecionada não existe no cadastro.',
            'data.required'           => 'Informe a data de aplicação.',
            'data.date'               => 'A data de aplicação deve ser uma data válida.',
            'validade.date'           => 'A data de validade deve ser uma data válida.',
            'validade.after_or_equal' => 'A validade deve ser uma data posterior ou igual à aplicação.',
        ];
    }
}
