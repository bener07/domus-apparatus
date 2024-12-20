<?php

namespace App\Models;

use App\Exceptions\ArgumentsException;
use Illuminate\Database\Eloquent\Model;
use App\Classes\ApiResponseClass;

class BaseProducts extends Model
{
    protected $fillable = [
        'quantity',
        'name',
        'description'
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
            'quantity' => $quantity
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
        return $this->hasMany(Requisicao::class, 'base_product_id');
    }
    public function tags(){
        return $this->belongsToMany(Tags::class, 'base_product_tag');
    }
    
    public function isAvailable($quantity){
        return $this->availability() - $quantity> 0;
    }
    
    public function availability(){
        return $this->quantity;
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
