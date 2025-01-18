<?php

namespace App\Classes;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Admin;
use App\Models\Product;
use App\Models\BaseProducts;
use App\Models\Requisicao;
use App\Models\AdminConfirmation;
use App\Mail\Admin\SendConfirmationRequest as NotifyAdminOnConfirmationRequest;
use App\Mail\Admin\NotifyAdminOnDenial as NotifyAdminOnDenialRequest;
use App\Mail\User\NotifyUserOnRequest as NotifyUserOnConfirmationRequest;
use App\Mail\User\NotifyUserOnConfirmation as NotifyUserOnConfirmation;
use App\Mail\User\NotifyUserOnDenial;
use App\Exceptions\UserException;
use App\Exceptions\ProductException;
use Illuminate\Support\Facades\Mail;
use App\Models\Cart;

class GestorDeRequisicoes
{
    /**
     * Create a new class instance.
     */

    public Admin $admin;
    public User $user;
    public Requisicao $requisicao;

    public function __construct(Requisicao $requisicao)
    {
        $this->admin = $requisicao->admin;
        $this->user = $requisicao->user;
        $this->requisicao = $requisicao;
        $this->products = $requisicao->products;
    }

    /**
     * Notifications section of the manager
     */
    public function notifyOnAction($action, $notifyUser=true, $notifyAdmin=true){
        if($notifyUser){
            $this->notifyUser($action);
        }
        if($notifyAdmin){
            $this->notifyAdmin($action);
        }
    }

    public function notifyAdmin($action){
        switch($action){
            case 'em confirmacao':
                $email = new NotifyAdminOnConfirmationRequest($this);
                break;
            case 'rejeitado':
                $email = new NotifyAdminOnDenial($this);
                break;
            case 'confirmado':
                $email = new NotifyAdminOnConfirmation($this);
                break;
            default:
                return;
        }
        return Mail::to($this->admin->email)->send($email);
    }

    public function notifyUser($action){
        switch($action){
            case 'em confirmacao':
                $email = new NotifyUserOnConfirmationRequest($this);
                break;
            case 'rejeitado':
                $email = new NotifyUserOnDenial($this);
                break;
            case 'confirmado':
                $email = new NotifyUserOnConfirmation($this);
                break;
            default:
                return;
        }
        return Mail::to($this->user->email)->send($email);
    }

    /**
     * Check if the requisition is available
     */
    public static function isAuthorized(User $user, Product $product){
        if($user->pendentes->count() >= App::environment('MAXIMUM_PER_USER')){
            throw new UserException("O utilizador excedeu o limite de requisições", 1);
        }
        if(!Requisicao::emRequisicao($product)){
            throw new UserException("Não há produtos disponíveis nessa data", 1);
        }
    }

    public static function chooseAdmin(){
        $adminWithFewestRequisicoes = Admin::withCount(['requisicoes' => function ($query) {
            $query->where('requisicoes.status', 'pendente');
        }])->orderBy('requisicoes_count', 'asc')->get()->first();
        if (!$adminWithFewestRequisicoes) {
            $adminWithFewestRequisicoes = Admin::all()->get()->first();
        }
        return $adminWithFewestRequisicoes;
    }

    public static function pedirEntrega(Requisicao $requisicao, Admin $admin){
        $requisicao->pedirConfirmacao($admin);
    }

    public static function emRequisicao(Product $product){
        return Requisicao::where('product_id', $product->id)->where('status', '!=','rejeitado')->exists();
    }
    public static function verifyDate($request){
        if($request->start > $request->end){
            throw new UserException("Data de requisicao posterior à data de entrega");
        }
    }
    public static function verifyRequest(Request $request, BaseProducts $product){
        self::verifyDate($request);

        $cart = $request->user()->cart;
        
        if(!$product->exists()){
            throw new UserException("O produto não existe", 404);
        }
        if($request->quantity <= 0){
            throw new UserException("Quantidade de equipamentos solicitada deve ser superior a 0", 400);
        }
        if($request->quantity > $product->quantity){
            throw new UserException("Quantidade de equipamentos solicitada é superior ao disponível", 400);
        }
        if ($product->quantity < Requisicao::futureQuantityOnDate(
                $request->product_id,
                $request->start ?? $cart->start,
                $request->end ?? $cart->end,
                $request->quantity
                ))
            throw new ProductException("Não há equipamentos suficientes para a data pedida", 400);
        return true;
    }

    public function entregar(Requisicao $requisicao){
        $requisicao->updateStatus('entregue');
        $requisicao->entrega_real = now();
        $requisicao->save();
        /* Update Status */
        return $requisicao;
    }

    public function rejeitar(Requisicao $requisicao){
        $requisicao->updateStatus('rejeitado');
        $requisicao->save();
        /* Update Status */
        return $requisicao;
    }

    public static function generateToken($user_id, $cart_id, $product_id){
        return md5($user_id. $cart_id. $product_id);
    }

    public static function requisitar(User $user, BaseProducts $product, Request $request){
        // adiciona a requisição ao carrinho e asseguir manda uma mensagem para o administrador
        $requisicao = Cart::addToCart($product, $request->quantity, $request);
        return $requisicao;
    }

    public static function confirmRequisicao(User $user, Product $product, Request $request){
        $requisicao = Requisicao::where('user_id', $user->id)->where('product_id', $product->id)->where('status', 'pendente')->first();
        if($requisicao){
            $requisicao->updateStatus('confirmado');
            $requisicao->save();
            /* Update Status */
            return $requisicao;
        }
        return null;
    }
}
