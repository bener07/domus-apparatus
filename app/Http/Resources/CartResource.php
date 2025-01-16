<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CartResource extends JsonResource
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
            'total' => $this->total,
            'requisicoes' => RequisicaoResource::collection($this->items),
            'user_id' => $this->user_id,
            'start' => $this->start,
            'end' => $this->end,
            'user' => new UserResource($this->whenLoaded('user')),
        ];
    }
}
