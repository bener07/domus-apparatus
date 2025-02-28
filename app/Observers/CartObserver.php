<?php
namespace App\Observers;

use App\Events\CartEvent;
use App\Models\Cart;

class CartObserver
{
    public function updated(Cart $cart)
    {
        $originalTotal = $cart->getOriginal('total'); // The value before the update
        $currentTotal = $cart->total; // The new value after the update

        if ($currentTotal < $originalTotal) {
            $message = 'Equipamento removido do carrinho!';
            $color = 'danger';
        } elseif ($currentTotal > $originalTotal) {
            $message = 'Equipamento adicionado ao carrinho!';
            $color = 'success';
        } else {
            // If no change occurred, no need to fire the event
            \Log::info("No change in total detected");
            return;
        }

        // Fire the event with the message
        event(new CartEvent($cart, $message, $color));
    }
}
