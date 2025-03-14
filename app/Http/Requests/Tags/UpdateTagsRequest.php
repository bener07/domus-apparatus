<?php

namespace App\Http\Requests\Tags;

use Illuminate\Foundation\Http\FormRequest;
use App\Http\Requests\ApiRequest;

class UpdateTagsRequest extends ApiRequest
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
            'id' => 'required|integer|exists:tags,id',
            'name' => 'required|string|max:255',
            'details' => 'nullable|string',
            'description' => 'nullable|string',
            'owner_id' => 'required|exists:users,id'
        ];
    }
}
