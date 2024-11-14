<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use App\Observers\UserObserver;
use App\Models\User;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        User::observe(UserObserver::class);
        // Register the user Policy
        Gate::policy(User::class, UserPolicy::class);

        Gate::define('isHost', function($user, $party){
            return $user->id === $party->owner_id;
        });

        Blade::if('isHosting', function(){
            $user = Auth::user();

            return $user && $user->ownedParties()->exists();
        });
        Blade::if('isAdmin', function () {
            $user = Auth::user();
            return $user && $user->isAdmin();
        });
    }
}
