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

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (self::where('name', $model->name)->exists()) {
                throw new \Exception('The name must be unique.');
            }
        });
    }

    public function products(){
        return $this->belongsToMany(Product::class, 'product_tag');
    }

    public static function findTag($tag_name){
        return Tags::where('name', $tag_name)->first();
    }

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }
}
