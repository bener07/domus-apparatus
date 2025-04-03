<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ClassroomResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'department_id' => $this->department_id,
            'location' => $this->location,
            'capacity' => $this->capacity,
            'disciplines' => $this->disciplines,
            'department' => new DepartmentResource($this->department) // eager load the related user
        ];
    }
}
