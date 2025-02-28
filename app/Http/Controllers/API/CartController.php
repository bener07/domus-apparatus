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
use App\Http\Requests\Cart\CartCheckoutRequest;
use App\Models\Requisicao;
use App\Models\Calendar;
use App\Models\Cart;

class CartController extends Controller
{
    public function index(Request $request){
        $cart = $request->user()->cart;
        return ApiResponseClass::sendResponse(new CartResource($cart), '', 200);
    }

    /**
     * Add a new item to the cart
     * @return ApiResponseClass
     */
    public function store(AddToCartRequest $request){
        $cart = $request->user()->cart;
        $product = BaseProducts::where('id', $request->product_id)->get()->first();
        $requisicao = Cart::addToCart($product, $request->quantity, $request);
        return ApiResponseClass::sendResponse(new CartResource($cart), 'Product added to cart', 201);
    }

    /**
     * Change quantities in the cart
     * @return ApiResponseClass
     */
    public function update(AddToCartRequest $request){
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
        $cart->updateTotal();
        return ApiResponseClass::sendResponse(new CartResource($cart), 'Cart updated', 200);
    }

    /**
     * Remove items from the cart
     * @return ApiResponseClass
     */
    public function destroy($requisicaoId){
        $cart = auth()->user()->cart;
        $cart->remove($requisicaoId);
        return ApiResponseClass::sendResponse(new CartResource($cart), 'Product removed from cart', 200);
    }

    /**
     * Register cart date
     * @return Response
     */
    public function registerDate(UpdateDateRequest $request){
        $cart = $request->user()->cart;
        $cart->updateDate($request->start, $request->end);
        return back();
        // return ApiResponseClass::sendResponse(new CartResource($cart), 'Date registered successfully', 200);
    }

    /**
     * Checkout cart and send it for confirmation
     * @return ApiResponseClass
     */
    public function checkout(CartCheckoutRequest $request){
        $cart = $request->user()->cart;
        $cart->checkout($request);
        return ApiResponseClass::sendResponse([], 'A sua requisição foi enviada para confirmação', 200);
    }
}