<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Classes\ApiResponseClass;

class ApiRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if(auth()->user()->isAdmin())
            return true;
        return false;
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(ApiResponseClass::sendResponse($validator->errors(), '', 422));
    }
}
