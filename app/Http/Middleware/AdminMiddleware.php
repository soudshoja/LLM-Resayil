<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is authenticated and is the admin
        if (auth()->check() && auth()->user()->isAdmin()) {
            return $next($request);
        }

        // Redirect to dashboard if not admin
        return redirect('/dashboard')->with('error', 'Unauthorized access.');
    }
}
