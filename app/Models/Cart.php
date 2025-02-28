<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Classes\GestorDeRequisicoes;
use App\Exceptions\ProductException;
use App\Classes\Notifications;

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
        return $this->hasMany(CartItem::class);
    }

    public function products(){
        return $this->items()->with('baseProduct');
    }

    public static function addToCart($base_product, $quantity, $request){
        // Add base_product to cart
        $user = auth()->user();
        $cart = $user->cart;

        $title = $base_product->name . " - ". $user->name;

        // searches for existing products wth the same title, user, and cart id
        $existingCartItem = $cart->items()->where('title', $title)->get();
        // in case the array is empty it creates the item
        if(!$existingCartItem->isEmpty()){
            $existingCartItem = $existingCartItem->first();
            $futureQuantity = $existingCartItem->quantity + $quantity;

            $productQuantityOnDate = $base_product->quantity - Calendar::productsRequestedOnDate($base_product->id, $cart->start, $cart->end)->sum('quantity');

            if($productQuantityOnDate < $futureQuantity){
                throw new ProductException("Não há equipamentos suficientes para a data pedida.", 400);
            }

            if($futureQuantity > $existingCartItem->product->total){
                throw new ProductException("Quantidade pedida excede a quantidade de equipamentos em stock.", 400);
            }
            $existingCartItem->updateQuantity($futureQuantity);
            $item = $existingCartItem;
        }else{
            // items in the cart are the requisicoes
            $item = $cart->items()->create([
                'title' => $title,
                'quantity' => $quantity,
                'base_product_id' => $base_product->id
            ]);
        }
        $cart->updateTotal();
        if($item){
            return $item;
        }
    }

    public function remove($requisicaoId){
        $item = $this->items()->find($requisicaoId);
        if($item){
            $item->delete();
            $this->updateTotal();
        }
        return $this;
    }

    public function isEmpty() {
        return !$this->items()->exists();
    }

    function updateTotal(){
        $totalQuantity = 0;
        foreach ($this->items as $item) {
            $totalQuantity += $item->quantity; // Add up the quantities of each base_product
        }
        $this->total = $totalQuantity; // Update the total in the cart
        $this->save();
    }
    
    public function updateDate($start, $end){
        $this->start = $start;
        $this->end = $end;
        $this->save();
    }

    public function isExpired(){
        return $this->end < now();
    }

    public function loadToCalendar($requisicao){
        foreach($this->items as $item){
            $products = BaseProducts::find($item->base_product_id)->products()->limit($item->quantity)->get();
            foreach($products as $product){
                Calendar::create([
                    'requisicoes_id' => $requisicao->id,
                    'base_product_id' => $item->base_product_id,
                    'product_id' => $product->id,
                    'quantity' => $item->quantity,
                    'start' => $this->start,
                    'end' => $this->end,
                    'status' => 'em confirmacao',
                ]);
            }
        }
    }

    public function checkout($request){
        // notify User and Admin and get admin confirmation
        $choosenAdmin = Requisicao::choseAdmin();
        $user = auth()->user();
        $requisicao = Requisicao::create([
            'title' => $user->name. ' ' . $this->start. ' - ' . $this->end,
            'status' => 'em confirmacao',
            'admin_id' => $choosenAdmin->id,
            'user_id' => $user->id,
            'total' => $this->total,
            'start' => $this->start,
            'end' => $this->end,
            'quantity' => $this->total,
            'token' => Requisicao::generateToken($user->id, $this->id, $this->start),
            'discipline_id' => $request->discipline,
            'room_id' => $request->room,
            'aditionalInfo' => $request->optional_text
        ]);
        $requisicao->pedirConfirmacao($choosenAdmin);
        $this->loadToCalendar($requisicao);
        $this->delete();
    }
}
