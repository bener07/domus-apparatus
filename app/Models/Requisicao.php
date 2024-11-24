<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Product;
use App\Models\Admin;
use App\Models\AdminConfirmation;
use Illuminate\Support\Facades\Hash;

define("MAX_REQUISICAO_PER_USER","3");

class Requisicao extends Model
{
    protected $table ='requisicoes';
    
    protected $fillable = [
        'status',
        'admin_id',
        'user_id',
        'product_id',
        'entrega_prevista',
        'entrega_real'
    ];

    protected static function booted()
    {
        static::creating(function ($requisicao) {
            if (is_null($requisicao->status)) {
                unset($requisicao->status); // Allow the database default to take effect
            }
        });
    }

    public static function requisitar(User $user, Product $product, $request){
        if($user->pendentes->count() >= MAX_REQUISICAO_PER_USER){
            return 0;
        }
        $chosenAdmin = Requisicao::chooseAdmin();
        if (!$chosenAdmin) {
            $chosenAdmin = Admin::all()->get()->first();
        }
        $requisicao = Requisicao::create([
            'status' => 'pendente',
            'admin_id' => $chosenAdmin->id,
            'user_id' => $user->id,
            'product_id' => $product->id,
            'entrega_prevista' => $request->previsto,
        ]);
        $requisicao->pedirConfirmacao($chosenAdmin);
        return $requisicao;
    }

    public function pedirConfirmacao($admin, $status='em confirmacao'){
        return AdminConfirmation::create([
            'requisicao_id' => $this->id,
            'admin_id' => $admin->id,
            'status' => $status,
            'token' => Hash::make(now().$admin->name.$admin->id)
        ]);
    }

    public function pedirEntrega($admin){
        $this->updateStatus('em confirmacao');
        $this->pedirConfirmacao($admin);
    }

    public function entregar(){
        $this->updateStatus('entregue');
        $this->entrega_real = now();
        $this->save();
        $product = Product::find($this->product_id);
        $product->status = 'disponivel';
        $product->save();
        return $this;
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
    
    public function product(){
        return $this->belongsTo(Product::class, 'product_id');
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

    public static function chooseAdmin(){
        $adminWithFewestRequisicoes = Admin::withCount(['requisicoes' => function ($query) {
            $query->where('requisicoes.status', 'pendente');
        }])->orderBy('requisicoes_count', 'asc')->get()->first();
        return $adminWithFewestRequisicoes;
    }
}
