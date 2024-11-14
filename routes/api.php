<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\PartyController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\UserAuthController;

// user group
Route::group([
    'middleware' => 'auth:sanctum',        // Apply middleware
    'prefix' => 'user',           // URL prefix
    'namespace' => 'user',        // Controller namespace
    'name' => 'user.',             // Name prefix for route names
], function () {
    Route::delete('party', [UserController::class, 'detachFromParty']);
    Route::post('party', [UserController::class, 'addUserToParty']);
    Route::get('parties', [UserController::class, 'getUserParties']);
    Route::get('events', [userController::class, 'getUserEvents']);
    Route::get('/', function (Request $request) {return $request->user();});
});

Route::get('/party', [PartyController::class, 'index'])->name('parties.index');

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/party', [PartyController::class, 'store'])->name('parties.store');
    Route::get('/party/{party}', [PartyController::class, 'show'])->name('parties.show');
    Route::put('/party/{party}', [PartyController::class, 'update'])->name('parties.update');
    Route::delete('/party/{party}', [PartyController::class, 'destroy'])->name('parties.destroy');
});

// Sanctum Autentication
// Route::post('register',[UserAuthController::class,'register']);
Route::post('login',[UserAuthController::class,'login']);
Route::post('logout',[UserAuthController::class,'logout'])->middleware('auth:sanctum');