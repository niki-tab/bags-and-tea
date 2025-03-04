<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfNoLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {   
        if ($request->is('livewire/*') || $request->is('api/*')) {
            return $next($request); // Skip the language prefix for Livewire routes
        }

        /*$locale = $request->segment(1); 

        // Check if the first segment is a valid locale ('en' or 'es')
        if (!in_array($locale, ['en', 'es'])) {
            // Redirect to the default locale (e.g., 'es') if no valid locale is found
            return redirect('/es' . $request->getRequestUri());
        }*/

        return $next($request);
    }
}
