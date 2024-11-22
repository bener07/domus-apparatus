<?php

namespace App\Observers;
use Illuminate\Support\Facades\Log;
use App\Models\AdminConfirmation;

class AdminConfirmationObserver
{
    public function created(AdminConfirmation $confirmation){
        Log::info("New confirmation created please check your inbox");
    }
}
