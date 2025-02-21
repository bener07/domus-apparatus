<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\AdminConfirmation;

class ConfirmationMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $confirmation = AdminConfirmation::getByToken($request->token);
        $request->confirmation = $confirmation;
        if(!$confirmation){
            abort(404);
        }
        if($confirmation->isConfirmado() || $confirmation->isDenied()){
            return redirect()->route('dashboard')->with('warning', 'Token já usado.');
        }
        return $next($request);
    }
}
