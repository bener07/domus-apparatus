<?php

namespace App\Observers;
use Illuminate\Support\Facades\Log;
use App\Models\AdminConfirmation;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendConfirmationRequest;


class AdminConfirmationObserver
{
    public function created(AdminConfirmation $confirmation){
        Log::info("New confirmation created please check your inbox");
        $requisicao = $confirmation->requisicao;

        // enviar email para administrador
        Mail::to($requisicao->admin->email)
            ->send(new SendConfirmationRequest($requisicao, $requisicao->products));
        $requisicao->updateProductsStatus('em confirmacao');
    }
}
