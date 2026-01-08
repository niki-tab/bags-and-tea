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
        '88.8.191.153',
        '79.116.237.154',
    ];

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $clientIp = $this->getClientIp($request);

        if (!in_array($clientIp, $this->allowedIps)) {
            abort(403, 'Access denied.');
        }

        return $next($request);
    }

    /**
     * Get the real client IP address, checking forwarded headers.
     */
    protected function getClientIp(Request $request): string
    {
        // Check X-Forwarded-For header first (most common for proxies)
        $forwardedFor = $request->header('X-Forwarded-For');
        if ($forwardedFor) {
            // X-Forwarded-For can contain multiple IPs, the first one is the original client
            $ips = array_map('trim', explode(',', $forwardedFor));
            return $ips[0];
        }

        // Check other common headers
        $headers = [
            'X-Real-IP',
            'CF-Connecting-IP', // Cloudflare
            'True-Client-IP',   // Akamai
        ];

        foreach ($headers as $header) {
            $ip = $request->header($header);
            if ($ip) {
                return $ip;
            }
        }

        // Fall back to standard method
        return $request->ip();
    }
}
