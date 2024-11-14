<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\ProductResource;
use App\Classes\ApiResponseClass;
use App\Models\User;

class UserController extends Controller
{
    public function getUserProducts(Request $request){
        $request->user()->products();
        $user_products = $request->user()->products;
        return ApiResponseClass::sendResponse(ProductResource::collection($user_products), '', 200);
    }

    public function getUserEvents(Request $request){
        return ApiResponseClass::sendResponse(ProductResource::collection($request->user()->events), '', 200);
    }

    public function addUserToProduct(Request $request){
        $status = $request->user()->addProduct($request->ProductId);
        if($status)
            return ApiResponseClass::sendResponse([], 'Successfuly added to Product list', 200);
        else
            return ApiResponseClass::sendResponse([], 'You are already in this Product list!', 409);
            
    }

    public function detachFromProduct(Request $request){
        $request->user()->removeProduct($request->ProductId);
        return ApiResponseClass::sendResponse([], 'User removed from Product', 200);
    }
}
