<?php
namespace App\Http\Requests\Cart;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateDateRequest extends FormRequest
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
            'start' => 'required|date_format:Y-m-d\TH:i|after_or_equal:today',
            'end' => 'required|date_format:Y-m-d\TH:i|after_or_equal:start',
        ];
    }

    public function messages(): array
    {
        return [
            'start.required' => 'Data de requisição é obrigatória',
            'start.date' => 'Data de requisição tem de ser uma data válida',
            'start.after_or_equal' => 'Data de requisição tem de ser posterior a hoje!',
            'end.required' => 'Data de entrega é obrigatória',
            'end.date' => 'Data de entrega tem de ser uma data válida',
            'end.after_or_equal' => 'Data de entrega tem de ser posterior à Data de requisição!',
        ];
    }

    /**
     * Handle a failed validation attempt.
     *
     * @param  \Illuminate\Contracts\Validation\Validator  $validator
     * @throws \Illuminate\Http\Exceptions\HttpResponseException
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            redirect('/requisitar') // Redirect to your custom page
                ->withErrors($validator) // Pass validation errors
                ->withInput() // Keep old input
        );
    }
}
