<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Contracts\Auth\Middleware\AuthenticatesRequests as MiddlewareAuthenticatesRequests;

class CheckUser implements MiddlewareAuthenticatesRequests
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() and auth()->user()->is_admin == true) {
            return redirect(route('dashboard'));
        }

        return $next($request);
    }
}
