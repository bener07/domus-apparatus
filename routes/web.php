<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TagsController;
use App\Http\Controllers\ConfirmationController;
use App\Http\Resources\CartResource;
use App\Http\Resources\RequisicaoResource;

Route::middleware(['auth'])->group(function () {
    Route::get('/', function () {
        return view('pages.index');
    })->name('home');

    Route::get('/entregar', function (){
        return view('entregar');
    });
    
    Route::get('/requisitar', function (){
        return view('requisitar');
    });

    Route::get('/admin', function () {
        return view('admin');
    })->middleware('isAdmin');

    Route::get('/cart', function (){
        $cart = auth()->user()->cart;
        $data_de_reserva = $cart->start ?? 'sem data';
        $data_de_entrega_prevista = $cart->end ?? 'sem data';
        // return new CartResource($cart);
        return view('pages.cart', compact('data_de_reserva', 'data_de_entrega_prevista', 'cart'));
    });

    Route::get('/cart/checkout', function (){
        $cart = auth()->user()->cart;
        $items = RequisicaoResource::collection($cart->items);

        return view('checkout', compact('items', 'cart'));
    })->name('cart-checkout');
});


Route::middleware(['auth', 'verified', 'isAdmin', 'confirmation'])->group(function (){
    Route::get('/confirmation', [ConfirmationController::class, 'confirmation'])->name('confirmation');
    Route::get('/denial', [ConfirmationController::class, 'denial'])->name('denial');
});


Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/', function () {return view('pages.index');})->name('dashboard');
});


Route::group([
    'middleware' => ['auth', 'verified', 'isAdmin'],        // Apply middleware
    'prefix' => 'dashboard',           // URL prefix
    'name' => 'dashboard',
], function () {
    Route::get('/users', function(){return view('dashboard.users.gestao');})->name('admin.users');
    Route::get('/users/add', function(){return view('dashboard.users.add');})->name('admin.users.add');
    
    Route::get('/roles', function(){return view('dashboard.roles.gestao');})->name('admin.roles');
    Route::get('/roles/add', function(){return view('dashboard.roles.add');})->name('admin.roles.add');
    Route::get('/department', function(){return view('dashboard.departments.gestao');})->name('admin.departments');
    Route::get('/department/add', function(){return view('dashboard.departments.add');})->name('admin.departments.add');
    Route::get('/products', function(){return view('dashboard.products.gestao');})->name('admin.products');
    Route::get('/products/add', function(){return view('dashboard.products.add');})->name('admin.products.add');
});


require __DIR__.'/auth.php';
require __DIR__.'/third_party_auth.php';

Route::get('/{page}', function($page) {
    $user = Auth::user();
    return view('pages.'.$page, compact('user'));
})->middleware('auth');