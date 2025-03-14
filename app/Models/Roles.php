<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Roles extends Model
{
    protected $table = 'roles';

    protected $fillable = [
        'name',
        'description'
    ];
    
    public static function findRole($role_name=''){
        return Roles::where('name', $role_name)->first();
    }
    /**
     * The users that belong to the Roles
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'roles_user', 'user_id', 'role_id');
    }
}
