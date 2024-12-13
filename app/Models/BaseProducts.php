<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BaseProducts extends Model
{
    protected $fillable = [
        'quantity',
        'name',
        'description'
    ];
    public function products(){
        return $this->hasMany(Product::class);
    }
    public function requisicoes(){
        return $this->hasMany(Requisicao::class);
    }
    public function tags(){
        return $this->belongsToMany(Tags::class);
    }
    
    public function isAvailable($quantity){
        return $this->availability() >= 0;
    }
    
    public function availability($quantity){
        return $this->quantity - $quantity;
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
}
