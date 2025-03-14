<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

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
            'roles' => RolesResource::collection($this->roles),
            'departments' => UserDepartmentResource::make($this->department),
            'links' => $this->socialLinks->pluck('platform'),
            'avatar' => $this->avatar ? $this->avatar : '/storage/images/avatar.png',
            'show_delivery_message' => $this->showDeliveryMessage
        ];
    }
}
