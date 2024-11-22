<?php

namespace App\Observers;
use App\Models\Requisicao;
use Illuminate\Support\Facades\Log;

class RequisicaoObserver
{
    public function created(Requisicao $requisicao){
        $product = $requisicao->product;
        Log::info("Um pedido para requisitar o produto ".$product->name." foi feito!");
    }
}
