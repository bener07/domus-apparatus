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
        'title',
        'status',
        'admin_id',
        'user_id',
        'product_id',
        'start',
        'end',
        'entrega_real',
        'quantity',
        'token',
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
        return $this->belongsTo(Admin::class, 'admin_id');
    }

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }

    public function cart(){
        return $this->belongsTo(Cart::class, 'cart_id');
    }
    
    public function products(){
        return $this->hasMany(Product::class);
    }

    public function product(){
        return $this->belongsTo(BaseProducts::class, 'product_id');
    }

    public static function emRequisicao($product){
        if(!$product->requisicao)
            return false;
        $status = $product->requisicao->status;
        return $status=='confirmado' || $status == 'pendente' || $status == 'em confirmacao';
    }

    public function getEntregaPrevista(){
        return $this->end;
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

    public function getConfirmationToken(){
        return $this->token;
    }

    public function updateQuantity($quantity){
        $this->quantity = $quantity;
        $this->save();
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

    public static function quantityOnDate($product_id, $start, $end){
        return BaseProducts::find($product_id)
            ->requisicoes()
            ->whereBetween('start', [$start, $end])
            ->pluck('quantity')->toArray();
    }

    public static function futureQuantityOnDate($product_id, $start, $end, $extraQuantity){
        $requisicoes = self::quantityOnDate($product_id, $start, $end);
        array_push($requisicoes, $extraQuantity);
        return array_sum($requisicoes);
    }

    public function authorization_url(){
        return route('confirmation', ['token' => $this->token]);
    }
    
    public function denial_url(){
        return route('denial', ['token' => $this->token]);
    }

    public function authorize(){
        $this->updateStatus('confirmado');
    }

    public function deny(){
        $this->updateStatus('rejeitado');
    }

    public function isConfirmedForPickUp(){
        $confirmation = $this->confirmacao();
        return $this->status == 'confirmado' && 
               $this->getOriginal('status') == 'em confirmacao' &&
               $confirmation->isConfirmed();
    }
}