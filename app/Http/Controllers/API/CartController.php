<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Classes\ApiResponseClass;
use App\Models\BaseProducts;
use App\Http\Resources\CartResource;
use App\Classes\GestorDeRequisicoes;
use App\Http\Requests\Cart\AddToCartRequest;
use App\Http\Requests\Cart\UpdateDateRequest;
use App\Http\Requests\Cart\UpdateCartRequest;

class CartController extends Controller
{
    public function index(Request $request){
        $cart = $request->user()->cart;
        // dd($cart->items);
        return ApiResponseClass::sendResponse(new CartResource($cart), '', 200);
    }

    public function store(AddToCartRequest $request){
        $cart = $request->user()->cart;
        $product = BaseProducts::find($request->product_id);
        GestorDeRequisicoes::requisitar($request->user(), $product, $request);
        return ApiResponseClass::sendResponse(new CartResource($cart), 'Product added to cart', 201);
    }

    public function update(UpdateCartRequest $request){
        $cart = $request->user()->cart;

        if (isset($request->quantity) && isset($request->id)) {
            // Find the item (Requisicao) associated with the cart and product_id
            $item = $cart->items()->where('id', $request->id)->first();
            if ($item) {
                // Update the quantity of the item
                $item->updateQuantity($request->quantity);
            } else {
                return ApiResponseClass::sendResponse([], 'Equipamento não encontrado!', 404);
            }
        }
        return ApiResponseClass::sendResponse(new CartResource($cart), 'Cart updated', 200);
    }

    public function destroy($requisicaoId){
        $cart = auth()->user()->cart;
        $cart->remove($requisicaoId);
        return ApiResponseClass::sendResponse(new CartResource($cart), 'Product removed from cart', 200);
    }

    public function registerDate(UpdateDateRequest $request){
        $cart = $request->user()->cart;
        $cart->updateDate($request->start, $request->end);
        return back();
        // return ApiResponseClass::sendResponse(new CartResource($cart), 'Date registered successfully', 200);
    }
}
