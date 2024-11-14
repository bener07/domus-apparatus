<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PartyController;
use App\Http\Controllers\TagsController;

Route::get('/', function () {
    return view('index');
})->name('home');

Route::resource('party', PartyController::class);
Route::resource('tag', TagsController::class);


Route::group([
    'middleware' => ['auth', 'verified'],        // Apply middleware
    'prefix' => 'dashboard',           // URL prefix
], function () {
    Route::get('/', function () {return view('dashboard.myParties');})->name('dashboard');
    Route::get('/new-event', function () {return view('dashboard.events.new');})->name('dashboard.newEvent');
    Route::middleware('isHost')->group(function () {
        Route::get('/events', function () {return view('dashboard.events');})->name('dashboard.events');
    });
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
require __DIR__.'/third_party_auth.php';