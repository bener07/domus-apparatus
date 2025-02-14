<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Product;
use App\Models\BaseProducts;
use Illuminate\Support\Arr;

class CartItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $baseProduct = BaseProducts::find($this->base_product_id);
        return [
            'id' => $this->id,
            'cart_id' => $this->cart_id,
            'base_id' => $this->base_product_id,
            'title' => $this->title,
            'name' => $this->product->name,
            'quantity' => $this->quantity,
            'isbn' => $this->product->isbn,
            'description' => $this->product->details,
            'base' => $baseProduct->name,
            'adicionado' => $this->updated_at,
            'img' => Arr::first($baseProduct->images),
            'total_product_quantity' => $baseProduct->total,
            'total_cart_quantity' => $this->quantity * $this->product->total,
        ];
    }
}
