<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GestorResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "status" => $this->status,
            "products" => ProductResource::collection($this->products),
            "user" => $this->user->name,
            "admin" => $this->admin->name,
            "start" => $this->start,
            "end" => $this->end,
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at,
            // "confirmation_id" => $this->requisicao->confirmation_id,
        ];
    }
}
