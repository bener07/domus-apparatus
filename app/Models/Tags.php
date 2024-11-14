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

    public function parties(){
        return $this->belongsToMany(Party::class, 'party_tag');
    }
}
