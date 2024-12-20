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
        $this->requisicao->authorize();
        $this->status = 'confirmado';
        $this->save();
    }

    public function deny(){
        $this->requisicao->deny();
        $this->status ='rejeitado';
        $this->save();
    }

    public static function getByToken($token){
        return self::where('token', $token)->first();
    }

    public function isConfirmado(){
        return $this->status === 'confirmado';
    }

    public function isDenied(){
        return $this->status ==='rejeitado';
    }
}
