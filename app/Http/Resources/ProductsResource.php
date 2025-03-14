<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;
use App\Models\Calendar;
use App\Models\CartItem;

class ProductsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $cart = $request->user()->cart;
        $products_requested_on_date = Calendar::productsRequestedOnDate($this->id, $cart->start, $cart->end)->sum('quantity');
        $availability = $this->quantity - $products_requested_on_date;
        return [
            'id' => $this->id,
            'name' => $this->name,
            'details' => $this->details,
            'img' => $this->images,
            'featured_image' => Arr::first($this->images),
            'tags' => $this->tags->pluck('name'),
            'status' => $availability > 0 ? 'disponivel' : 'indisponivel',
            'quantity' => $availability,
            'non_confirmed_requisicoes' => $products_requested_on_date,
            'products' => ProductResource::collection($this->products)
        ];
    }
}
