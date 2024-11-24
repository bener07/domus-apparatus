<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Models\User;
use App\Models\AdminConfirmation;

class Admin extends User
{
    protected $table = 'users';

    protected static function booted()
    {
        static::addGlobalScope('admin', function (Builder $builder) {
            $builder->whereHas('roles', function ($query) {
                $query->where('name', 'admin');
            });
        });
    }

    public function adminConfirmations(){
        return $this->hasMany(AdminConfirmation::class);
    }

    public function confirmacoes(){
        return $this->hasMany(AdminConfirmation::class);
    }

    public function confirmAdmin($admin_id){
        $admin = AdminConfirmation::find('admin_id',$admin_id);
        $admin->confirm();
    }

    public function rejeitarAdmin($admin_id){
        $admin = AdminConfirmation::find('admin_id', $admin_id);
        $admin->rejeitar();
    }

    public function getAdminConfirmados(){
        return $this->adminConfirmations()->where('status', 'confirmado')->count();
    }

    public function requisicoes(){
        return $this->hasMany(Requisicao::class);
    }

    public function save(array $options = [])
    {
        parent::save($options);

        if (!$this->roles()->where('name', 'admin')->exists()) {
            $this->roles()->attach(Role::where('name', 'admin')->firstOrFail());
        }
    }
}
