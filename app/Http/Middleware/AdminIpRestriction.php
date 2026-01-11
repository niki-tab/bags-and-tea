<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class AdminIpRestriction
{
    /**
     * Whitelisted IP addresses that can access the admin panel.
     * Add your static IPs here. Consider using environment variables for flexibility.
     */
    protected array $allowedIps = [
        '88.8.192.109',
        '79.116.237.154',
        
    ];

    /**
     * Handle an incoming request.
     *
     * SECURITY: This middleware uses Laravel's $request->ip() which respects
     * the TrustProxies middleware configuration. Ensure TrustProxies is properly
     * configured with your actual proxy IPs (e.g., Cloudflare, AWS ELB).
     *
     * DO NOT set TrustProxies $proxies = '*' in production as it allows
     * attackers to spoof their IP address via X-Forwarded-For headers.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Use Laravel's built-in IP detection which respects TrustProxies
        $clientIp = $request->ip();

        // Log access attempts for security auditing
        if (!in_array($clientIp, $this->allowedIps)) {
            Log::warning('Admin panel access denied', [
                'ip' => $clientIp,
                'path' => $request->path(),
                'user_agent' => $request->userAgent(),
            ]);

            abort(403, 'Access denied.');
        }

        return $next($request);
    }
}
