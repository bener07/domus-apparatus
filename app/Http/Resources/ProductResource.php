<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $base = $this->base;
        return [
            'id' => $this->id,
            'name' => $base->name,
            'details' => $base->details,
            'img' => $base->images,
            'featured_image' => Arr::first($base->images),
            'tags' => $base->tags->pluck('name'),
            'status' => $this->status
        ];
    }
}
