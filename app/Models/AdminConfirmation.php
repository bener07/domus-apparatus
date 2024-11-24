<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminConfirmation extends Model
{
    protected $table = 'admin_confirmation';

    protected $fillable = [
        'requisicao_id',
        'admin_id',
        'status',
        'token'
    ];

    public function admin(){
        return $this->belongsTo(Admin::class);
    }

    public function requisicao(){
        return $this->belongsTo(Requisicao::class);
    }

    public function confirm(){
        $this->status = 'confirmado';
        $this->save();
    }

    public function rejeitar(){
        $this->status ='rejeitado';
        $this->save();
    }

    public function getAdminConfirmado(){
        return $this->status === 'confirmado';
    }
}
