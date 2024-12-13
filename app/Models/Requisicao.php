<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Product;
use App\Models\Admin;
use App\Models\AdminConfirmation;
use Illuminate\Support\Facades\Hash;

define("MAX_REQUISICAO_PER_USER","10");

class Requisicao extends Model
{
    protected $table ='requisicoes';
    
    protected $fillable = [
        'status',
        'admin_id',
        'user_id',
        'product_id',
        'entrega_prevista',
        'entrega_real',
        'date_of_pickup',
        'token'
    ];

    protected static function booted()
    {
        static::creating(function ($requisicao) {
            if (is_null($requisicao->status)) {
                unset($requisicao->status); // Allow the database default to take effect
            }
        });
    }

    public function confirmacao(){
        return $this->hasMany(AdminConfirmation::class);
    }

    public function admin(){
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }
    
    public function products(){
        return $this->hasMany(Product::class, 'product_id');
    }

    public static function emRequisicao($product){
        if(!$product->requisicao)
            return false;
        $status = $product->requisicao->status;
        return $status=='confirmado' || $status == 'pendente' || $status == 'em confirmacao';
    }

    public function updateStatus($status){
        $this->status = $status;
        $this->save();
    }

    public function updateEntregaReal($entrega_real){
        $this->entrega_real = $entrega_real;
        $this->save();
    }
    
    public function getRequisitante(){
        return $this->user->name;
    }

    public function getAdministrador(){
        return $this->admin->name;
    }

    public function getNomeDoProduto(){
        return $this->product->name;
    }

    public function getEntregaPrevista(){
        return $this->entrega_prevista;
    }

    public function getEntregaReal(){
        return $this->entrega_real;
    }

    public function getConfirmationToken(){
        return $this->token;
    }

    /**
     * Update the status of the products associated with this requisition
     */
    public function updateProductsStatus($status){
        $this->products->each(function ($product) use ($status) {
            $product->updateStatus($status);
        });
    }

    /**
     * Ask for confirmation and create a token
     */
    public function pedirConfirmacao($admin, $status='em confirmacao'){
        $this->updateProductsStatus($status);
        $confirmation = AdminConfirmation::create([
            'requisicao_id' => $this->id,
            'admin_id' => $admin->id,
            'status' => $status,
            'token' => $this->token
        ]);
        return $confirmation;
    }

    public function authorization_url(){
        return route('confirmation', ['id' => $this->id, 'token' => $this->token]);
    }
    
    public function denial_url(){
        return route('denial', ['id' => $this->id, 'token' => $this->token]);
    }

    public function authorize($confirmation){
        $this->updateStatus('confirmado');
        $confirmation->confirm();
    }

    public function isConfirmedForPickUp(){
        $confirmation = $this->confirmacao();
        return $this->status == 'confirmado' && 
               $this->getOriginal('status') == 'em confirmacao' &&
               $confirmation->isConfirmed();
    }
}