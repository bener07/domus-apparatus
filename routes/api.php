<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\Admin\ProductsAdminController;
use App\Http\Controllers\API\Admin\UserAdminController;
use App\Http\Controllers\API\Admin\RoleAdminController;
use App\Http\Controllers\API\Admin\DepartmentAdminController;
use App\Http\Controllers\API\Admin\TagsAdminController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\ProductController;
use App\Http\Resources\UserResource;
use App\Http\Resources\RolesResource;
use App\Classes\ApiResponseClass;



// Sanctum Autentication
// Route::post('register',[AuthController::class,'register']);
Route::post('login',[AuthController::class,'login']);
Route::post('logout',[AuthController::class,'logout'])->middleware('auth:sanctum');


// gerais só com autenticação
Route::middleware('auth:sanctum')->group(function () {
    Route::get('products', [ProductController::class, 'index']);
    Route::get('roles', [RoleAdminController::class, 'index']);
    Route::get('departments', [DepartmentAdminController::class, 'index']);
    Route::get('tags', [TagsAdminController::class, 'index']);
    Route::get('user', function (Request $request) {
        return ApiResponseClass::sendResponse(UserResource::make($request->user()), '', 200);
    });

    // routes do utilizador
    Route::group(['prefix' => 'user','namespace' => 'user', 'name' => 'user.'], function () {
        Route::delete('requisicao', [UserController::class, 'deliverRequisicao']);
        Route::post('requisicao', [UserController::class, 'addRequisicao']);
        Route::get('requisitados', [UserController::class, 'getRequisicoes']);
        Route::get('entregues', [UserController::class, 'getEntregues']);
        Route::get('pendentes', [UserController::class, 'getPendentes']);
    });
});

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