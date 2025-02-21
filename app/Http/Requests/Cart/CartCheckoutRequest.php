<?php

namespace App\Http\Requests\Cart;

use Illuminate\Foundation\Http\FormRequest;

class CartCheckoutRequest extends FormRequest
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
            'room' => 'required|integer|exists:classrooms,id',
            'discipline' => 'required|string|exists:disciplines,id',
            'observations' => 'nullable|string'
        ];
    }

    public function messages(): array
    {
        return [
            'room.required' => 'O campo de sala é obrigatório.',
            'room.integer' => 'O campo de sala deve ser um número inteiro.',
            'room.exists' => 'Sala não encontrada.',
            'discipline.required' => 'O campo de disciplina é obrigatório.',
            'discipline.string' => 'O campo de disciplina deve ser texto.',
            'discipline.exists' => 'Disciplina não encontrada.',
            'observations.string' => 'As observações devem ser texto.'
        ];
    }
}
