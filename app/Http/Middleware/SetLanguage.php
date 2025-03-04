<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

use Illuminate\Support\Facades\App;
class SetLanguage
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {   
        if ($request->is('livewire/*')) {
            return $next($request); // Skip the language prefix for Livewire routes
        }
        
        /*$locale = $request->segment(1);

        if (session()->has('language')) {
            
            session(['language' => $locale]);
            app()->setLocale(locale: $locale);

        }else{

            session(['language' => $locale]);
            app()->setLocale(locale: $locale);

        }*/
        
        return $next($request);

    }
}
