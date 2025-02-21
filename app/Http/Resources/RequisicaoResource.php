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
        $product = BaseProducts::find($this->product_id);
        return [
            'id' => $this->id,
            'status' => $this->status,
            'user' => $this->user->name, // Access the name directly
            'title' => $this->title,
            'products' => ProductResource::collection($this->products()->get()), // Access the name directly
            'base_products' => $this->getUniqueBaseProducts(), // Access the name directly
            'admin' => $this->admin->name, // Access the name directly
            'requisicao' => $this->start, // Access the name directly
            'entrega_prevista' => $this->end,
            'quantity' => $this->quantity,
            'entrega_real' => $this->entrega_real,
            'requisitado' => $this->created_at,
            'autorizacao' => $this->confirmacao->pluck('status')[0],
            'qrCode' => $this->token,
            'aditional_info' => $this->aditionalInfo,
            'room' => $this->room,
            'discipline' => $this->discipline,
        ];
    }
}
