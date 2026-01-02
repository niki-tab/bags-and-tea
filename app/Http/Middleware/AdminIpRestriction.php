<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminIpRestriction
{
    /**
     * Whitelisted IP addresses that can access the admin panel.
     */
    protected array $allowedIps = [
        '88.8.189.253',
    ];

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $clientIp = $request->ip();

        if (!in_array($clientIp, $this->allowedIps)) {
            abort(403, 'Access denied. Your IP address is not authorized.');
        }

        return $next($request);
    }
}
