<?php

namespace App\Http\Requests\Cart;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCartRequest extends FormRequest
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
            'start' => 'nullable|date|before_or_equal:end', // Optional start date
            'end' => 'nullable|date|after_or_equal:start', // Optional end date
            'quantity' => 'nullable|integer|min:1|max:100', // Optional quantity
            'id' => 'required|integer|exists:requisicoes,id', // Product ID must exist in the requisicoes table
        ];
    }
}
