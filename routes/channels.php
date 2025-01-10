<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('cart.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});
