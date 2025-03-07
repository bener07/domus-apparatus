<?php

namespace App\Http\Resources\Charts;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\ProductResource;
use Illuminate\Support\Arr;

class ProductsResource extends JsonResource
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
            'details' => $this->details,
            'img' => $this->images,
            'featured_image' => Arr::first($this->images),
            'tags' => $this->tags->pluck('name'),
            'quantity' => $this->quantity,
            'products' => ProductResource::collection($this->products)
        ];
    }
}
