<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    protected $fillable = [
        'title',
        'base_product_id',
        'cart_id',
        'quantity',
    ];

    public function product(){
        return $this->belongsTo(BaseProducts::class, 'base_product_id');
    }

    public function cart(){
        return $this->belongsTo(Cart::class, 'cart_id');
    }

    public function updateQuantity($quantity){
        $this->quantity = $quantity;
        $this->save();
    }

    public static function itemsOnDate($baseId, $start, $end){
        return self::whereHas('cart', function ($query) use ($start, $end) {
            $query->where('start', '<=', $start)
                  ->where('end', '>=', $end);
        })->where('base_product_id', $baseId)->get();
    }
}
