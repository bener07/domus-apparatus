<?php

namespace App\Observers;
use App\Events\CartEvent;
use App\Models\Cart;

class CartObserver
{
    public function updated(Cart $cart)
    {
        \Log::info('Cart updated!', ['changes' => $cart->getDirty()]);
        if($cart->total > $cart->getDirty('total')){
            $message = 'Equipamento removido do carrinho!';
        }else if($cart->total < $cart->getDirty('total')){
            $message = 'Equipamento adicionado ao carrinho!';
        }
        event(new CartEvent($cart, $message));
    }

}
