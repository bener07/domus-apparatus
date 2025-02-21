<?php

namespace App\Classes;
use App\Models\User;
use App\Models\Admin;
use App\Mail\Admin\SendConfirmationRequest as NotifyAdminOnConfirmationRequest;
use App\Mail\Admin\NotifyAdminOnDenial as NotifyAdminOnDenialRequest;
use App\Mail\User\NotifyUserOnRequest as NotifyUserOnConfirmationRequest;
use App\Mail\User\NotifyUserOnConfirmation as NotifyUserOnConfirmation;
use App\Mail\User\NotifyUserOnDenial;
use Illuminate\Support\Facades\Mail;

class Notifications
{
    /**
     * Create a new class instance.
     */
    public $cart;

    public function __construct(
        public $action,
        public User $user,
        public Admin $admin,
        public $requisicao=null,
        public $adminOnly=false,
        public $userOnly=false,
    ){
        $this->cart = auth()->user()->cart;
    }

    public function send(){
        // Send notification to user
        if (!$this->adminOnly){
            $this->notifyUser($this->action);
        }

        // Send notification to admi
        if(!$this->userOnly){
            $this->notifyAdmin($this->action);
        }
    }

    public function notifyAdmin($action){
        switch($action){
            case 'em confirmacao':
                $email = new NotifyAdminOnConfirmationRequest($this);
                break;
            case 'rejeitado':
                $email = new NotifyAdminOnDenial($this);
                break;
            case 'confirmado':
                $email = new NotifyAdminOnConfirmation($this);
                break;
            default:
                return;
        }
        return Mail::to($this->admin->email)->send($email);
    }

    public function notifyUser($action){
        switch($action){
            case 'em confirmacao':
                $email = new NotifyUserOnConfirmationRequest($this);
                break;
            case 'rejeitado':
                $email = new NotifyUserOnDenial($this);
                break;
            case 'confirmado':
                $email = new NotifyUserOnConfirmation($this);
                break;
            default:
                return;
        }
        return Mail::to($this->user->email)->send($email);
    }

}
