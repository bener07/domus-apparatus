<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Party extends Model
{
    protected $fillable = [
        'name',
        'description',
        'details',
        'price',
        'location',
        'images',
        'featured_image',
        'owner_id'
    ];

    protected $casts = [
        'images' => 'array'
    ];

    public function addTag($tag_id){
        if (!$this->tags->contains($tag_id))
            $this->tags()->attach($tag_id);
    }

    public function removeTag($tag_id){
        if($this->tags->contains($tag_id)){
            $this->tags()->detach($tag_id);
        }
    }

    public function users(){
        return $this->belongsToMany(User::class, 'party_users');
    }
    
    public function owner(){
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function tags(){
        return $this->belongsToMany(Tags::class, 'party_tag');
    }
}
