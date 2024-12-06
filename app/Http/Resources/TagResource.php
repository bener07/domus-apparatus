<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TagResource extends JsonResource
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
            'color' => $this->color,
            'name' => $this->name,
            'details' => $this->details,
            'image' => $this->image ? asset('storage/' . $this->image) : null,
            'description' => $this->description,
            'products' => $this->products->pluck('name'),
            'owner' => $this->user
        ];
    }
}
