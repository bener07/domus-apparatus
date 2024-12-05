<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\UserAdminController;
use App\Http\Controllers\API\RoleAdminController;
use App\Classes\ApiResponseClass;
use App\Http\Resources\UserResource;
use App\Models\Roles;
use App\Http\Resources\RolesResource;




// ação de utilizadores
Route::group([
    'middleware' => 'auth:sanctum',        // Apply middleware
    'prefix' => 'user',           // URL prefix
    'namespace' => 'user',        // Controller namespace
    'name' => 'user.',             // Name prefix for route names
], function () {
    Route::delete('requisicao', [UserController::class, 'deliverRequisicao']);
    Route::post('requisicao', [UserController::class, 'addRequisicao']);
    Route::get('requisitados', [UserController::class, 'getRequisicoes']);
    Route::get('entregues', [UserController::class, 'getEntregues']);
    Route::get('pendentes', [UserController::class, 'getPendentes']);
    Route::get('/', function (Request $request) {
        return ApiResponseClass::sendResponse(UserResource::make($request->user()), '', 200);
    });
});

Route::group([
    'middleware' => ['auth:sanctum', 'ApiAdmin'],
    'prefix' => 'admin',
    'name' => 'admin.',
], function(){
    Route::apiResource('requisicao', ProductController::class); // admin edit of product\
    Route::apiResource('users', UserAdminController::class);
});
Route::apiResource('roles', RoleAdminController::class)->middleware(['auth:sanctum', 'ApiAdmin']);



// gestão de equipamento
Route::get('/product', [ProductController::class, 'index'])->name('products.index');

// Sanctum Autentication
// Route::post('register',[AuthController::class,'register']);
Route::post('login',[AuthController::class,'login']);
Route::post('logout',[AuthController::class,'logout'])->middleware('auth:sanctum');