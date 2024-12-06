<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $table = 'departments';

    protected $fillable = [
        'name',
        'details',
        'manager_id'
    ];

    public function manager(){
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function users(){
        return $this->hasMany(User::class, 'department_id');
    }

    public static function findDepartment($department_name){
        return Department::where('name', $department_name)->first();
    }
}
