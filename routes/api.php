<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\Admin\ProductsAdminController;
use App\Http\Controllers\API\Admin\UserAdminController;
use App\Http\Controllers\API\Admin\RoleAdminController;
use App\Http\Controllers\API\Admin\DepartmentAdminController;
use App\Http\Controllers\API\Admin\TagsAdminController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\CartController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\ProductController;
use App\Http\Resources\UserResource;
use App\Http\Resources\RolesResource;
use App\Classes\ApiResponseClass;
use App\Models\Cart;
use App\Events\CartEvent;




// Sanctum Autentication
// Route::post('register',[AuthController::class,'register']);
Route::post('login',[AuthController::class,'login']);
Route::post('logout',[AuthController::class,'logout'])->middleware('auth:sanctum');


// gerais só com autenticação
Route::middleware('auth:sanctum')->group(function () {

    // informações gerais só com autenticação
    Route::get('products', [ProductController::class, 'index']);
    Route::get('roles', [RoleAdminController::class, 'index']);
    Route::get('departments', [DepartmentAdminController::class, 'index']);
    Route::get('tags', [TagsAdminController::class, 'index']);
    Route::get('user', function (Request $request) {
        return ApiResponseClass::sendResponse(UserResource::make($request->user()), '', 200);
    });

    // Utilizador
    Route::group(['prefix' => 'user', 'name' => 'user.'], function () {

        // requisicões
        Route::delete('requisicao', [UserController::class, 'deliverRequisicao']);
        Route::post('requisicao', [UserController::class, 'addRequisicao']);
        Route::get('requisicao', [UserController::class, 'getRequisicao']);
        Route::get('entregues', [UserController::class, 'getEntregues']);
        Route::get('pendentes', [UserController::class, 'getPendentes']);
        Route::post('cart-date', [CartController::class, 'registerDate'])->name('request.products');

        // Carrinho do utilizador
        Route::group(['prefix' => 'cart', 'name' => 'cart'], function (){
            Route::get('/', [CartController::class, 'index']);
            Route::post('/', [CartController::class, 'store']);
            Route::put('/', [CartController::class, 'update']);
            Route::delete('/{id}', [CartController::class, 'destroy']);
        });
    });

    Route::get('/test-cart', function (Request $request){
        $cart = auth()->user()->cart;
        event(new CartEvent($cart, "Testing"));
        return 'Event BroadCasted';
    });
});

/**
 * API Resources:
 *   - METHOD -> function
 *   - GET -> index
 *   - POST -> create
 *   - PUT -> update
 *   - DELETE -> delete
 */

// adminitração
Route::group([
    'middleware' => ['auth:sanctum', 'ApiAdmin'],
    'prefix' => 'admin',
    'name' => 'admin.',
], function(){
    Route::apiResource('users', UserAdminController::class);
    Route::apiResource('roles', RoleAdminController::class);
    Route::apiResource('tags', TagsAdminController::class);
    Route::apiResource('products', ProductsAdminController::class);
    Route::apiResource('departments', DepartmentAdminController::class);
});