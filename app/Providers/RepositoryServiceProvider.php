<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Interfaces\PartyRepositoryInterface;
use App\Repositories\PartyRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(PartyRepositoryInterface::class, PartyRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
