<?php

namespace App;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Admin;
use App\Models\Product;
use App\Models\Requisicao;
use App\Models\AdminConfirmation;
use App\Mail\Admin\SendConfirmationRequest as NotifyAdminOnConfirmationRequest;
use App\Mail\Admin\NotifyAdminOnDenial as NotifyAdminOnDenialRequest;
use App\Mail\User\NotifyUserOnRequest as NotifyUserOnConfirmationRequest;
use App\Mail\User\NotifyUserOnConfirmation as NotifyUserOnConfirmation;

class GestorDeRequisicoes
{
    /**
     * Create a new class instance.
     */

    protected Admin $admin;
    protected User $user;
    protected Requisicao $requisicao;

    protected function __construct(Requisicao $requisicao, User $user, Admin $admin)
    {
        $this->admin = $admin;
        $this->user = $user;
        $this->requisicao = $requisicao;
    }

    /**
     * Notifications section of the manager
     */
    public function notifyOnAction($action, $notifyUser=true, $notifyAdmin=true){
        if($notifyUser){
            self::notifyUser($action);
        }
        if($notifyAdmin){
            self::notifyAdmin($action);
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
        if($user->pendentes->count() >= 10){
            return 0;
        }
        if(Requisicao::emRequisicao($product)){
            return 1;
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

    public static function requisitar(User $user, Product $product, Request $request){
        $chosenAdmin = self::chooseAdmin();
        $requisicao = Requisicao::create([
            'status' => 'pendente',
            'admin_id' => $chosenAdmin->id,
            'user_id' => $user->id,
            'entrega_prevista' => $request->previsto,
            'date_of_pickup' => $request->date_of_pickup,
            'token' => md5(now().$chosenAdmin->name.$chosenAdmin->id)
        ]);
        $requisicao->pedirConfirmacao($chosenAdmin);
        return $requisicao;
    }
}
