<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;
use App\Models\User;
use App\Models\BaseProducts;

class RequisicaoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $quantity = array_sum(self::quantityOnDate(
            $this->product_id,
            $this->start,
            $this->end
        ));
        $product = BaseProducts::find($this->product_id);
        return [
            'id' => $this->id,
            'status' => $this->status,
            'user' => $this->user->name, // Access the name directly
            'product' => $product->name, // Access the name directly
            'description' => $product->details, // Access the description directly
            'admin' => $this->admin->name, // Access the name directly
            'requisicao' => $this->start, // Access the name directly
            'entrega_prevista' => $this->end,
            'quantity' => $quantity,
            'total_product_quantity' => $product->total,
            'entrega_real' => $this->entrega_real,
            'img' => Arr::first($this->product->images),
            'requisitado' => $this->created_at,
            'autorizacao' => $this->confirmacao->pluck('status')
        ];
    }
}
