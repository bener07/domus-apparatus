<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\RequisicaoResource;
use App\Http\Resources\ProductResource;
use App\Http\Resources\GestorResource;
use App\Http\Resources\CartResource;
use App\Http\Resources\UserResource;
use App\Classes\ApiResponseClass;
use App\Classes\GestorDeRequisicoes;
use Illuminate\Http\Request;
use App\Models\Requisicao;
use App\Models\User;
use App\Models\BaseProducts;
use App\Models\Cart;
use App\Http\Requests\Cart\AddToCartRequest;
use App\Http\Requests\Users\UploadAvatarRequest;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function getCart(Request $request){
	    $user_products = $request->user()->cart;
        return ApiResponseClass::sendResponse(new CartResource($user_products), '', 200);
    }

    public function addRequisicao(AddToCartRequest $request){
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

    public function getRequisicoes(Request $request){
        return ApiResponseClass::sendResponse(RequisicaoResource::collection($request->user()->allRequisicoes()->get()), '', 200);
    }

    public function getEntregues(Request $request){
        return ApiResponseClass::sendResponse(RequisicaoResource::collection($request->user()->entregues()->get()), '', 200);
    }
    
    public function getPendentes(Request $request){
        return ApiResponseClass::sendResponse(RequisicaoResource::collection($request->user()->pendentes()->get()), '', 200);
    }

    public function retrieveRequisicao($id, Request $request){
        $requisicao = Requisicao::find($id);

        if(!$requisicao){
            return ApiResponseClass::sendResponse([], 'Requisição não encontrada!', 404);
        }

        if($request->user()->id != $requisicao->user_id && !$request->user()->isAdmin()){
            return ApiResponseClass::sendResponse([], 'Requisição não pertence ao utilizador!', 403);
        }

        $requisicao->delete();

        return ApiResponseClass::sendResponse(RequisicaoResource::make($requisicao), '', 200);
    }

    public function updateUserDeliveryMessage(Request $request){
        if(isset($request->showDeliveryMessage)){
            $request->user()->showDeliveryMessage = $request->showDeliveryMessage == 'true' ? 1 : 0;
            $request->user()->save();
        }else{
            return ApiResponseClass::sendResponse([], 'Parâmetro inválido!', 400);
        }
        return ApiResponseClass::sendResponse(new UserResource(auth()->user()), '', 200);
    }

    public function avatar(UploadAvatarRequest $request, $userId){
        // Find the user
        $user = User::find($userId);

        // If the user doesn't exist, return a 404 error
        if (!$user) {
            return ApiResponseClass::sendResponse([], 'User not found!', 404);
        }

        // Store the file
        if ($request->hasFile('avatar')) {
            // Delete the old avatar if it exists
            if ($user->avatar && Storage::exists($user->avatar)) {
                Storage::delete($user->avatar);
            }
    
            // Store the new avatar
            $path = $request->file('avatar')->store('avatars', 'public');
    
            // Update the user's avatar field with the relative path
            $user->avatar = Storage::url($path); // Store as
            $user->save();
    
            // Return a success response with the public URL
            return ApiResponseClass::sendResponse(
                ['avatar_url' => Storage::url($path)],
                'File Uploaded Successfully!',
                200
            );
        }
    

        // If no file was uploaded, return an error
        return ApiResponseClass::sendResponse([], 'No file specified!', 200);
    }
}
