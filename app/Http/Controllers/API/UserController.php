<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\RequisicaoResource;
use App\Http\Resources\ProductResource;
use App\Http\Resources\GestorResource;
use App\Classes\ApiResponseClass;
use App\Classes\GestorDeRequisicoes;
use Illuminate\Http\Request;
use App\Models\Requisicao;
use App\Models\User;
use App\Models\BaseProducts;

class UserController extends Controller
{
    public function getRequisicoes(Request $request){
        $request->user()->requisicoes();
        $user_products = $request->user()->requisicoes;
        return ApiResponseClass::sendResponse(ProductResource::collection($user_products), '', 200);
    }

    public function addRequisicao(Request $request){
        try {
            $product = BaseProducts::find($request->product_id);
            $requisicao = GestorDeRequisicoes::requisitar($request->user(), $product, $request);
            return ApiResponseClass::sendResponse(GestorResource::make($requisicao), 'A espera de confirmacao!', 200);
        }
        catch (UserException $ue) {
            return ApiResponseClass::sendResponse([], $ue->getMessage(), 400);
        }
        catch (ArgumentException $ue) {
            return ApiResponseClass::sendResponse([], $ue->getMessage(), 409);
        }
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
