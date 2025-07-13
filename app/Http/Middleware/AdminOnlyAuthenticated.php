<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminOnlyAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        if (!$user || !$user->hasRole('admin')) {
            // Redirect to dashboard if user is vendor trying to access admin-only area
            return redirect()->route('admin.home')->with('error', 'Access denied. Admin privileges required.');
        }

        return $next($request);
    }
}
