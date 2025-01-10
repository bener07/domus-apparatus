<?php

namespace App\Observers;
use Illuminate\Support\Facades\Log;
use App\Models\AdminConfirmation;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendConfirmationRequest;
use App\Classes\GestorDeRequisicoes;


class AdminConfirmationObserver
{
    public function created(AdminConfirmation $confirmation){
        Log::info("New confirmation created please check your inbox");
        $requisicao = $confirmation->requisicao;

        // send email to administrator with product information and other information
        $requisicao = new GestorDeRequisicoes($requisicao);
        // notifies administrator and user of the requisition
        $requisicao->notifyOnAction('em confirmacao', true, true);
    }
}
