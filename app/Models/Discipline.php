<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Discipline extends Model
{
    protected $fillable = [
        'name',
        'details',
        'department_id',
    ];
    protected $table = 'disciplines';

    public function department(){
        return $this->belongsTo(Department::class, 'department_id');
    }
}
