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

    public static function addToCart($product, $quantity, $request){
        GestorDeRequisicoes::verifyRequest($request, $product);
        $chosenAdmin = GestorDeRequisicoes::chooseAdmin();
        // Add product to cart
        $user = auth()->user();
        $cart = $user->cart;
        $token = GestorDeRequisicoes::generateToken($user->id, $cart->id, $product->id);

        $title = $product->name . " - ". $user->name;

        // searches for existing products wth the same title, user, and cart id
        $existingCartItem = $cart->items()->where('title', $title)->get();
        if(!$existingCartItem->isEmpty()){
            dd($existingCartItem);
        }
        
        // items in the cart are the requisicoes
        $requisicao = $cart->items()->create([
            'title' => $title,
            'product_id' => $product->id,
            'quantity' => $quantity,
            'admin_id' => $chosenAdmin->id, // Admin not chosen yet
            'user_id' => $user->id, // User making the request
            'status' => 'pendente', // Request is pending
            'token' => $token, //
            'start' => $cart->start,
            'end' => $cart->end,
        ]);
        if($requisicao){
            $cart->updateTotal();
            return $requisicao;
        }
    }

    
    public function isEmpty() {
        return $this->items()->exists();
    }
    
    function updateTotal(){
        $totalQuantity = 0;
        foreach ($this->items as $item) {
            $totalQuantity += $item['quantity']; // Add up the quantities of each product
        }
        $this->total = $totalQuantity; // Update the total in the cart
        $this->save();
    }
    
    public function updateDate($start, $end){
        $this->start = $start;
        $this->end = $end;
        $this->save();
    }
}
