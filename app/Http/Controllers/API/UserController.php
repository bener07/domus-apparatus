<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\ProductResource;
use App\Classes\ApiResponseClass;
use App\Http\Resources\RequisicaoResource;
use App\Models\Requisicao;
use App\Models\User;
use App\Models\Product;

class UserController extends Controller
{
    public function getRequisicoes(Request $request){
        $request->user()->requisicoes();
        $user_products = $request->user()->requisicoes;
        return ApiResponseClass::sendResponse(ProductResource::collection($user_products), '', 200);
    }

    public function addRequisicao(Request $request){
        $requisicao = $request->user()->requisitar($request);
        if($requisicao)
            return ApiResponseClass::sendResponse(RequisicaoResource::make($requisicao), 'A espera de confirmacao!', 200);
        else
            return ApiResponseClass::sendResponse([], 'Utilizador já excedeu o seu máximo de requisicoes simultaneas!', 409);
    }

    public function deliverRequisicao(Request $request){
        $requisicao = Requisicao::find($request->input('requisicao_id'));

        // caso não encontre a requisicao
        if(!$requisicao){
            return ApiResponseClass::sendResponse([], 'Requisição não encontrada!', 404);
        }

        // caso a requisicao já tenha sido entregue
        if($requisicao->status == 'entregue') {
            return ApiResponseClass::sendResponse([], 'Requisição já entregue!', 409);
        }

        // pedir a entrega da requisicao
        $requisicao->pedirEntrega($requisicao->admin);

        return ApiResponseClass::sendResponse(RequisicaoResource::make($requisicao), 'Produto entregue com sucesso!', 200);
    }

    public function getEntregues(Request $request){
        return ApiResponseClass::sendResponse(RequisicaoResource::collection($request->user()->entregues), '', 200);
    }
    
    public function getPendentes(Request $request){
        return ApiResponseClass::sendResponse(RequisicaoResource::collection($request->user()->pendentes()->get()), '', 200);
    }
}
