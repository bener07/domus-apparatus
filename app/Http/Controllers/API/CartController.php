<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Classes\ApiResponseClass;
use App\Models\BaseProducts;
use App\Http\Resources\CartResource;
use App\Classes\GestorDeRequisicoes;
use App\Http\Requests\Cart\AddToCartRequest;

class CartController extends Controller
{
    public function index(Request $request){
        $cart = $request->user()->cart;
        return ApiResponseClass::sendResponse(new CartResource($cart), '', 200);
    }

    public function store(AddToCartRequest $request){
        $cart = $request->user()->cart;
        $product = BaseProducts::find($request->product_id);
        GestorDeRequisicoes::requisitar($request->user(), $product, $request);
        return ApiResponseClass::sendResponse(new CartResource($cart), 'Product added to cart', 201);
    }

    public function update(Request $request){
        $cart = $request->user()->cart;
        $cart->update($request);
        return ApiResponseClass::sendResponse(new CartResource($cart), 'Cart updated', 200);
    }

    public function destroy($rowId){
        $cart = $request->user()->cart;
        $cart->remove($rowId);
        return ApiResponseClass::sendResponse(new CartResource($cart), 'Product removed from cart', 200);
    }
}
