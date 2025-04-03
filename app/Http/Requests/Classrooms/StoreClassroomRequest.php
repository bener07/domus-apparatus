<?php

namespace App\Http\Requests\Classrooms;

use Illuminate\Foundation\Http\FormRequest;
use App\Http\Requests\ApiRequest;

class StoreClassroomRequest extends ApiRequest
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
            'name' => 'required|string|max:255',
            'location' => 'required|string',
            'capacity' => 'required|integer',
            'department_id' => 'required|exists:departments,id',
            'discipline_ids' => 'required|exists:disciplines,id'
        ];
    }
}
