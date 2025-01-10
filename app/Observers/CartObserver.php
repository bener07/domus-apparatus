<?php

namespace App\Observers;
use App\Events\CartEvent;
use App\Models\Cart;

class CartObserver
{
    public function updated(Cart $cart){
        event(new CartEvent($cart));
    }
}
