<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Discipline extends Model
{
    protected $fillable = [
        'name',
        'details',
    ];
    protected $table = 'disciplines';
}
