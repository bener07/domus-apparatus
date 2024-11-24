<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use App\Models\Requisicao;
use App\Observers\RequisicaoObserver;
use App\Observers\UserObserver;
use App\Observers\AdminConfirmationObserver;
use App\Policies\UserPolicy;
use App\Models\User;
use App\Models\AdminConfirmation;

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
        // set the observers
        AdminConfirmation::observe(AdminConfirmationObserver::class);
        User::observe(UserObserver::class);
        Requisicao::observe(RequisicaoObserver::class);
        
        // Register the user Policy
        Gate::policy(User::class, UserPolicy::class);

        Gate::define('isHost', function($user, $Product){
            return $user->id === $Product->owner_id;
        });

        Blade::if('isHosting', function(){
            $user = Auth::user();

            return $user && $user->ownedProducts()->exists();
        });
        Blade::if('isAdmin', function () {
            $user = Auth::user();
            return $user && $user->isAdmin();
        });
    }
}
