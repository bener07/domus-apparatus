<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Classes\GestorDeRequisicoes;

class Cart extends Model
{
    protected $fillable = [
        'user_id',
        'total',
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

    public static function addToCart($product, $quantity, $chosenAdmin){
        // Add product to cart
        $user = auth()->user();
        $cart = $user->cart()->firstOrCreate();
        $token = GestorDeRequisicoes::generateToken($user->id, $cart->id, $product->id);

        // items in the cart are the requisicoes
        return $cart->items()->create([
            'title' => $product->name . " - ". $user->name,
            'product_id' => $product->id,
            'quantity' => $quantity,
            'admin_id' => $chosenAdmin->id, // Admin not chosen yet
            'user_id' => $user->id, // User making the request
            'status' => 'pendente', // Request is pending
            'token' => $token, //
            'start' => $cart->start,
            'end' => $cart->end,
        ]);
    }

    public function isEmpty() {
        return $this->items()->exists();
    }
}
