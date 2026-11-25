<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegistroServicoRequest extends FormRequest
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
            'pet_id'             => ['required', 'exists:pets,id'],
            'servico_id'         => ['required', 'exists:servicos,id'],
            'forma_pagamento_id' => ['required', 'exists:formas_pagamento,id'],
            'data'               => ['required', 'date'],
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
            'pet_id.required'             => 'Selecione o pet.',
            'pet_id.exists'               => 'O pet selecionado não existe no sistema.',
            'servico_id.required'         => 'Selecione o serviço realizado.',
            'servico_id.exists'           => 'O serviço selecionado não existe no cadastro.',
            'forma_pagamento_id.required' => 'Selecione a forma de pagamento.',
            'forma_pagamento_id.exists'   => 'A forma de pagamento selecionada é inválida.',
            'data.required'               => 'Informe a data do atendimento.',
            'data.date'                   => 'A data do atendimento deve ser uma data válida.',
        ];
    }
}
