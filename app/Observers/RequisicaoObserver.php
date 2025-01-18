<?php

namespace App\Observers;
use App\Models\Requisicao;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendConfirmationRequest;
use App\Mail\NotifyUserOnRequest;
use App\Mail\NotifyUserOnConfirmation;
use App\Classes\GestorDeRequisicoes;
use App\Events\CartEvent;

class RequisicaoObserver
{
    public function created(Requisicao $requisicao){
        \Log::info("Nova requisicao feita");
    }

    public function handleStatus(Requisicao $requisicao){
        switch ($requisicao->status) {
            case 'em confirmacao':
                \Log::error("Requisicao ". $requisicao->title . " em confirmacao");
                break;
            case 'confirmado':
                \Log::error("Requisicao ". $requisicao->title . " confirmada");
                break;
            case 'entregue':
                \Log::error("Requisicao ". $requisicao->title . " entregue");
                break;
            case 'pendente':
                \Log::error("Requisicao ". $requisicao->title . " pendente");
                break;
            case 'rejeitado': 
                \Log::error("Requisicao ". $requisicao->title . " rejeitado");
                break;
            default:
                \Log::error("No action on requisicao status");
                return ;
        }
    }

    public function updated(Requisicao $requisicao){
        if($requisicao->isDirty('quantity')){
            $cart = auth()->user()->cart;
            event(new CartEvent($cart, 'Carrinho atualizado!'));
        }
        if($requisicao->isDirty('status')){
            GestorDeRequisicoes::notifyUser($requisicao->status);
            $this->handleStatus($requisicao);
        }
    }
}
