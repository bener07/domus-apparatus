<?php

namespace App\Observers;
use Illuminate\Support\Facades\Log;
use App\Models\AdminConfirmation;
use Illuminate\Support\Facades\Mail;
use App\Classes\Notifications;


class AdminConfirmationObserver
{
    public function created(AdminConfirmation $confirmation){
        Log::info("New confirmation made by: " . $confirmation . "");
        $requisicao = $confirmation->requisicao;
        $choosenAdmin = $requisicao->admin;
        $user = auth()->user();

        $notification = new Notifications('em confirmacao', $user, $choosenAdmin, $requisicao);
        $notification->send();
        // notifies user and administrator
    }
}
