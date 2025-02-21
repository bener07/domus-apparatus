<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\isAdmin;
use App\Http\Middleware\CartRequired;
use App\Http\Middleware\API\IsAdmin as ApiAdmin;
use App\Http\Middleware\ConfirmationMiddleware as Confirmation;
use App\Http\Middleware\API\CartDateMiddleware as isDateChoosen;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        channels: __DIR__.'/../routes/channels.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->api(prepend:[
            \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
        ]);
        $middleware->alias([
            'isAdmin' => isAdmin::class,
            'ApiAdmin' => ApiAdmin::class,
            'confirmation' => Confirmation::class,
            'isDateChoosen' => isDateChoosen::class,
            'cart-required' => CartRequired::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
