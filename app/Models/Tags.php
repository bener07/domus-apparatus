<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tags extends Model
{
    protected $fillable = [
        'name',
        'details',
        'image',
        'description'
    ];

    public function products(){
        return $this->belongsToMany(Product::class, 'Product_tag');
    }
}
