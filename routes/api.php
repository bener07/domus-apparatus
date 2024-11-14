<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\UserAuthController;

// user group
Route::group([
    'middleware' => 'auth:sanctum',        // Apply middleware
    'prefix' => 'user',           // URL prefix
    'namespace' => 'user',        // Controller namespace
    'name' => 'user.',             // Name prefix for route names
], function () {
    Route::delete('Product', [UserController::class, 'detachFromProduct']);
    Route::post('Product', [UserController::class, 'addUserToProduct']);
    Route::get('products', [UserController::class, 'getUserProducts']);
    Route::get('events', [userController::class, 'getUserEvents']);
    Route::get('/', function (Request $request) {return $request->user();});
});

Route::get('/Product', [ProductController::class, 'index'])->name('products.index');

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/Product', [ProductController::class, 'store'])->name('products.store');
    Route::get('/Product/{Product}', [ProductController::class, 'show'])->name('products.show');
    Route::put('/Product/{Product}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('/Product/{Product}', [ProductController::class, 'destroy'])->name('products.destroy');
});

// Sanctum Autentication
// Route::post('register',[UserAuthController::class,'register']);
Route::post('login',[UserAuthController::class,'login']);
Route::post('logout',[UserAuthController::class,'logout'])->middleware('auth:sanctum');