<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        if ($request->expectsJson()) {
            return null;
        }

        // Get the current locale or default to app locale
        $locale = app()->getLocale();
        
        // Redirect to the login page with the current locale
        return route('login.show.en-es', ['locale' => $locale]);
    }
}
