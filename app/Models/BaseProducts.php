<?php

namespace App\Models;

use App\Exceptions\ArgumentsException;
use Illuminate\Database\Eloquent\Model;
use App\Classes\ApiResponseClass;
use App\Models\Requisicao;

class BaseProducts extends Model
{
    protected $fillable = [
        'quantity',
        'name',
        'description',
        'total'
    ];

    protected $casts = [
        'images' => 'array'
    ];

    protected function create($data){
        if(!isset($data['isbns'])){
            throw new ArgumentsException('Estão em falta os ISBNs para registo dos equipamentos', 1);
        }
        if(!is_array($data['isbns'])){
            throw new ArgumentsException('Os ISBNs devem ser um array', 1);
        }
        $quantity = sizeof($data['isbns']);
        $data['quantity'] = $quantity;
        $base = parent::create([
            'name' => $data['name'],
            'details' => $data['details'],
            'images' => $data['images'],
            'quantity' => $quantity,
            'total' => $quantity
        ]);

        // loop trough all the products and create them
        for ($product=0; $product < $quantity; $product++) {
            $base->products()->create([
                'name' => $base->name,
                'details' => $base->details,
                'isbn' => $data['isbns'][$product]
            ]);
        }
        return $base;
    }
    public function products(){
        return $this->hasMany(Product::class, 'base_id');
    }
    public function requisicoes(){
        return $this->hasMany(Requisicao::class, 'product_id');
    }
    public function tags(){
        return $this->belongsToMany(Tags::class, 'base_product_tag');
    }
    
    public function isAvailable($quantity){
        return $this->availability() - $quantity> 0;
    }
    
    public function availabilityOnDate(){
        $cart = auth()->user()->cart;
        $quantity = $this->quantity - array_sum(Requisicao::quantityOnDate(
            $this->id,
            $cart->start,
            $cart->end
        ));
        return $quantity;
    }

    public function addQuantity($quantity){
        $this->quantity += $quantity;
        $this->save();
    }
    public function removeQuantity($quantity){
        $this->quantity -= $quantity;
        $this->save();
    }

    public function updateQuantity($quantity){
        $this->quantity = $quantity;
        $this->save();
    }

    public function updateTotal($quantity){
        $this->total = $quantity;
        $this->save();
    }

    public function requisicao(){
        return $this->belongsTo(Requisicao::class, 'product_id');
    }
}
