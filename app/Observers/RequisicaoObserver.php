<?php

namespace App\Observers;
use App\Models\Requisicao;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendConfirmationRequest;
use App\Mail\NotifyUserOnRequest;
use App\Mail\NotifyUserOnConfirmation;

class RequisicaoObserver
{
    public function created(Requisicao $requisicao){
        Mail::to($requisicao->user->email)
            ->send(new NotifyUserOnRequest($requisicao->user, $requisicao, $requisicao->products));
    }

    public function updated(Requisicao $requisicao){
        if($requisicao->isDirty('status') && $requisicao->isConfirmedForPickUp()){
            Mail::to($requisicao->user())
                ->send(new NotifyUserOnConfirmation($requisicao->user, $requisicao, $requisicao->products));
        }
    }
}
