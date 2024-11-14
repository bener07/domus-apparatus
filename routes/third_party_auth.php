<?php

use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;
use App\Models\SocialLinks;

Route::prefix('auth')->group(function () {
    Route::prefix('github')->group(function () {
        Route::get('redirect', function () {
            return Socialite::driver('github')->redirect();
        })->name('auth.github');
         
        Route::get('callback', function () {return SocialLinks::handleCallback('github');});
    });
    Route::prefix('google')->group(function () {

        Route::get('redirect', function(){
            return Socialite::driver('google')->redirect();
        })->name('auth.google');

        Route::get('callback', function (){return SocialLinks::handleCallback('google');});
    });
});
