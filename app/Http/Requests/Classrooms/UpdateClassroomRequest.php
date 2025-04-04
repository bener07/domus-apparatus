<?php

namespace App\Http\Requests\Classrooms;

use App\Http\Requests\ApiRequest;

class UpdateClassroomRequest extends ApiRequest
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
            'name' => 'string|required',
            'location' => 'string',
            'capacity' => 'integer',
            'department_id' => 'exists:departments,id',
            'discipline_ids' => 'exists:disciplines,id'
        ];
    }
}
