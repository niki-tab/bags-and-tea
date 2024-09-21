<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

use Illuminate\Support\Facades\App;
class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {   

        $locale = $request->segment(1);

        if (in_array($locale, ['en', 'es'])) {
            App::setLocale($locale);
        } else {
            $locale = 'en';
            return redirect("/$locale" . $request->getRequestUri());
        }

        return $next($request);

    }
}
