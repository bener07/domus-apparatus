<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Product;
use App\Models\Admin;
use App\Models\AdminConfirmation;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Arr;

define("MAX_REQUISICAO_PER_USER","10");

class Requisicao extends Model
{
    protected $table ='requisicoes';
    
    protected $fillable = [
        'title',
        'status',
        'admin_id',
        'user_id',
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

    public function calendar(){
        return $this->belongsToMany(Calendar::class, 'requisicoes_id')->withPivot('start', 'end');
    }
    
    public function products()
    {
        return $this->belongsToMany(Product::class, 'calendar', 'requisicoes_id', 'product_id')
            ->using(Calendar::class)
            ->withPivot('start', 'end', 'quantity', 'status', 'base_product_id');
    }

    public function getUniqueBaseProducts()
    {
        $requisicao = Requisicao::with('products.base')->find($this->id);

        $uniqueBaseProducts = $requisicao->products
            ->map(fn($product) => $product->base) // Mapeia para o modelo baseProduct
            ->unique('id') // Filtra objetos únicos com base no ID
            ->values();
        $output = [];

        foreach($uniqueBaseProducts as $base){
            $baseOnDate = Calendar::where('requisicoes_id', $this->id)
                            ->where('base_product_id', $base->id)
                            ->get()
                            ->unique('product_id')
                            ->first();
            $output[] = [
                'id' => $base->id,
                'nome' => $base->name,
                'quantity' => $baseOnDate->quantity,
                'details' => $base->details,
                'img' => Arr::first($base->images)
            ];
        }

        return $uniqueBaseProducts;
    }


    public static function generateToken($user_id, $cart_id, $start){
        return md5($user_id. $cart_id. $start);
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
        $confirmation = $this->confirmacao()->create([
            'admin_id' => $admin->id,
            'status' => $status,
            'token' => $this->token
        ]);
        return $confirmation;
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

    public static function choseAdmin(){
        $adminWithFewestRequisicoes = Admin::withCount(['requisicoes' => function ($query) {
            $query->where('requisicoes.status', 'pendente');
        }])->orderBy('requisicoes_count', 'asc')->get()->first();
        if (!$adminWithFewestRequisicoes) {
            $adminWithFewestRequisicoes = Admin::all()->get()->first();
        }
        return $adminWithFewestRequisicoes;
    }
}