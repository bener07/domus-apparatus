<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\UserAuthController;
use App\Classes\ApiResponseClass;
use App\Http\Resources\UserResource;




// ação de utilizadores
Route::group([
    'middleware' => 'auth:sanctum',        // Apply middleware
    'prefix' => 'user',           // URL prefix
    'namespace' => 'user',        // Controller namespace
    'name' => 'user.',             // Name prefix for route names
], function () {
    Route::delete('requisicao', [UserController::class, 'deliverRequisicao']);
    Route::post('requisicao', [UserController::class, 'addRequisicao']);
    Route::get('requisicoes', [UserController::class, 'getRequisicoes']);
    Route::get('entregues', [UserController::class, 'getEntregues']);
    Route::get('pendentes', [UserController::class, 'getPendentes']);
    Route::get('/', function (Request $request) {return ApiResponseClass::sendResponse(UserResource::make($request->user()), '', 200);});
});



// gestão de equipamento
Route::get('/product', [ProductController::class, 'index'])->name('products.index');

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/product', [ProductController::class, 'store'])->name('products.store');
    Route::get('/product/{product}', [ProductController::class, 'show'])->name('products.show');
    Route::put('/product/{product}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('/product/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
});

// Sanctum Autentication
// Route::post('register',[UserAuthController::class,'register']);
Route::post('login',[UserAuthController::class,'login']);
Route::post('logout',[UserAuthController::class,'logout'])->middleware('auth:sanctum');