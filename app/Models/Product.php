<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'details',
        'images',
    ];

    protected $casts = [
        'images' => 'array'
    ];

    public function users(){
        return $this->belongsToMany(User::class, 'requisicoes');
    }

    public function tags(){
        return $this->belongsToMany(Tags::class, 'product_tag');
    }

    public function addTag($tag_name){
        $tag_id = Tags::findTag($tag_name)->id;
        if (!$this->tags->contains($tag_id))
            $this->tags()->attach($tag_id);
    }

    public function removeTag($tag_id){
        if($this->tags->contains($tag_id)){
            $this->tags()->detach($tag_id);
        }
    }

    public function requisicoes(){
        return $this->hasMany(Requisicao::class);
    }

    public function updateStatus($status){
        $this->status = $status;
        $this->save();
    }

    public function disponivel(){
        return $this->status == 'disponivel';
    }

    public function emConfirmacao(){
        return $this->status == 'em confirmacao';
    }
}
