<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'details',
        'requisicao_id',
        'isbn'
    ];

    public function base(){
        return $this->belongsTo(BaseProducts::class, 'base_id');
    }

    public function requisicoes(){
        return $this->belongsToMany(Requisicao::class, 'requisicao_id')
                ->using(Calendar::class)
                ->withPivot('start', 'end', 'quantity');
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
