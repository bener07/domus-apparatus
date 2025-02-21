<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Auth\Events\Login;
use App\Events\CartEvent;

class LogSuccessfulLogin
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(Login $event): void
    {
        $user = $event->user;

        // Custom logic for handling login
        \Log::info('User logged in:', ['id' => $user->id, 'email' => $user->email]);

        // Example: Validate the user's cart
        if ($user->cart && $user->cart->isExpired()) {
            $user->cart->delete(); // Clear expired cart
            $user->cart()->create();
            event(new CartEvent($user->cart, '<a href="/requisitar">O seu carrinho expirou! Clique aqui para renovar</a>'));
        }
    }
}
