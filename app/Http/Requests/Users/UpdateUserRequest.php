<?php

namespace App\Http\Requests\Users;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Classes\ApiResponseClass;
use App\Http\Requests\ApiRequest;

class UpdateUserRequest extends ApiRequest
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
            'name' => ['string','max:255'],
            'email' => ['string','email'],
            'avatar' => ['nullable','image','mimes:jpeg,png,jpg','max:2048'],
            'password' => ['nullable','string','min:8','confirmed'],
            'role' => ['nullable','string','in:user,admin'],
        ];
    }
}
