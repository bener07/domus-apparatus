<?php

namespace App\Http\Middleware\API;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Classes\ApiResponseClass;

class CartDateMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(is_null(auth()->user()->cart->start) || is_null(auth()->user()->cart->end)){
            return ApiResponseClass::sendResponse([], 'A data de requisição e de entrega precisam de estar preenchidas', 200);
        }
        return $next($request);
    }
}
