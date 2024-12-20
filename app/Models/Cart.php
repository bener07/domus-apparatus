<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $fillable = [
        'user_id',
        'start',
        'end'
    ];

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Items are the requisicoes, the cart is used to store them and then upload to the calendar or stack
     * @return HasMany
     */
    public function items(){
        return $this->hasMany(Requisicao::class);
    }

    public function addToCart(Requisicao $requisicao){
        // Add product to cart
        $user = auth()->user();
        $cart = $user->cart()->firstOrCreate();

        // items in the cart are the requisicoes
        $cart->items()->create($requisicao);
    }
}
