<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TagsController;


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

    
    Route::resource('product', ProductController::class);
    Route::resource('tag', TagsController::class);
    Route::get('/admin', function () {
        return view('admin');
    })->middleware('isAdmin');
});

Route::group([
    'middleware' => ['auth', 'verified', 'isAdmin'],        // Apply middleware
    'prefix' => 'dashboard',           // URL prefix
    'name' => 'dashboard',
], function () {
    Route::get('/', function () {return view('pages.index');})->name('dashboard');
    Route::get('/users', function(){return view('dashboard.users.gestao');})->name('admin.users');
    Route::get('/users/add', function(){return view('dashboard.users.add');})->name('admin.users.add');
    Route::middleware('auth')->group(function () {
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });
});


require __DIR__.'/auth.php';
require __DIR__.'/third_party_auth.php';

Route::get('/{page}', function($page) {
    $user = Auth::user();
    return view('pages.'.$page, compact('user'));
})->middleware('auth');