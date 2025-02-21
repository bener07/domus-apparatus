<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
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
            'base_product_id' => $this->base_id,
            'name' => $this->name,
            'details' => $this->details,
            'status' => $this->status,
            'isbn' => $this->isbn,
        ];
    }
}
