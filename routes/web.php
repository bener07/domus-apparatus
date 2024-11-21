<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TagsController;


Route::middleware(['auth'])->group(function () {
    Route::get('/', function () {
        return view('pages.index');
    })->name('home');

    Route::get('/tables', function () {
        return view('pages.tables');
    });
    Route::get('/blank', function () {
        return view('pages.blank');
    });

    
    Route::resource('Product', ProductController::class);
    Route::resource('tag', TagsController::class);
    Route::get('/admin', function () {
        return view('admin');
    })->middleware('isAdmin');
});

Route::group([
    'middleware' => ['auth', 'verified'],        // Apply middleware
    'prefix' => 'dashboard',           // URL prefix
], function () {
    Route::get('/', function () {return view('pages.index');})->name('dashboard');
    Route::middleware('auth')->group(function () {
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });
});


require __DIR__.'/auth.php';
require __DIR__.'/third_party_auth.php';

Route::get('/{page}', function($page) {
    return view('pages.'.$page);
});