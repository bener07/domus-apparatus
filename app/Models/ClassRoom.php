<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClassRoom extends Model
{
    protected $fillable = [
        'name',
        'capacity',
        'location',
        'department_id'
    ];
    protected $table = 'classrooms';
}
