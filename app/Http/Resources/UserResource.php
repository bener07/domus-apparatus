<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'email' => $this->email,
            'is_admin' => $this->isAdmin(),
            'roles' => $this->roles->pluck('name'),
            'links' => $this->socialLinks->pluck('name'),
            'avatar' => $this->avatar ? asset('storage/' . $this->avatar) : null,
        ];
    }
}