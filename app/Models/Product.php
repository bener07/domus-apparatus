<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'details',
        'requisicao_id'
    ];

    protected $casts = [
        'images' => 'array'
    ];

    public function base(){
        return $this->belongsTo(BaseProducts::class, 'base_id');
    }

    public function requisicao(){
        return $this->belongsTo(Requisicao::class, 'requisicao_id');
    }

    public function updateStatus($status){
        $this->status = $status;
        $this->save();
    }

    public function disponivel(){
        return $this->status == 'disponivel';
    }

    public function emConfirmacao(){
        return $this->status == 'em confirmacao';
    }
}
