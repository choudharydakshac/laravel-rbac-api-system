<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\RateLimiter;

class ApiRateLimitMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        $key = $user
            ? 'api-user-'.$user->id
            : 'api-ip-'.$request->ip();

        $maxAttempts = $user && $user->hasRole('admin') ? 200 : 60;

        if (RateLimiter::tooManyAttempts($key, $maxAttempts)) {
            return response()->json([
                'message' => 'Too many requests. Please slow down.'
            ], 429);
        }

        RateLimiter::hit($key, 60);
        
        return $next($request);
    }
}
