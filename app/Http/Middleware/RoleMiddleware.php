<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $roles): Response
    {

        if (!$request->user()->hasRole($roles)) {
            return abort(403, 'Unauthorized');
        }

        // Jika user memiliki role yang dibutuhkan, izinkan request untuk dilanjutkan
        return $next($request); // Diasumsikan ini ada di dalam middleware
    }
}
