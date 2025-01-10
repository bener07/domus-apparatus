<?php

namespace App\Http\Requests\Cart;

use Illuminate\Foundation\Http\FormRequest;
use App\Http\Requests\ApiRequest;

class AddToCartRequest extends ApiRequest
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
            'product_id' => 'required|exists:base_products,id',
            'quantity' => 'required|integer|min:1',
        ];
    }

    public function messages(): array
    {
        return [
            'product_id.required' => 'O campo de ID do produto é obrigatório.',
            'product_id.exists' => 'O produto selecionado não é válido.',
            'quantity.required' => 'O campo de quantidade é obrigatório.',
            'quantity.integer' => 'A quantidade deve ser um número inteiro.',
            'quantity.min' => 'A quantidade deve ser pelo menos 1.',
            'start.required' => 'O campo de início é obrigatório.',
            'start.dateTime' => 'A data e hora de início devem ser válidas.',
            'end.required' => 'O campo de término é obrigatório.',
            'end.dateTime' => 'A data e hora de término devem ser válidas.',
            'end.after' => 'A data e hora de término devem ser posteriores à data e hora de início.',
        ];
    }
}
