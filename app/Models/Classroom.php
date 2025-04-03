<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Classroom extends Model
{
    protected $fillable = [
        'name',
        'capacity',
        'location',
        'department_id'
    ];
    protected $table = 'classrooms';

    public function department(){
        return $this->belongsTo(Department::class, 'department_id');
    }

    public function disciplines(){
        return $this->belongsToMany(Discipline::class, 'classroom_disciplines', 'classroom_id', 'discipline_id');
    }
}
