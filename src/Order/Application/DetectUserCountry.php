<?php

namespace Src\Order\Application;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class DetectUserCountry
{
    public function __invoke(?string $ipAddress = null): string
    {
        // Check if user has given consent for location tracking
        if (!$this->hasLocationConsent()) {
            return 'DEFAULT'; // Use special code for default shipping
        }

        // Get IP address from request if not provided
        if (!$ipAddress) {
            $ipAddress = $this->getClientIpAddress();
        }

        // Default to Spain if no IP detected or localhost
        if (!$ipAddress || $this->isLocalIp($ipAddress)) {
            return 'ES';
        }

        // Try to get country from cache first
        $cacheKey = "country_detection_{$ipAddress}";
        $cachedCountry = Cache::get($cacheKey);
        
        if ($cachedCountry) {
            return $cachedCountry;
        }

        // Try different IP geolocation services
        $country = $this->detectCountryFromIp($ipAddress);
        
        // Cache the result for 24 hours
        if ($country) {
            Cache::put($cacheKey, $country, 60 * 60 * 24);
        }

        return $country ?: 'ES'; // Default to Spain
    }

    private function hasLocationConsent(): bool
    {
        // Since localStorage is client-side, we need to pass this from the frontend
        // Check the cookie that the CookieBanner component sets
        $consentCookie = request()->cookie('cookie_preferences');
        
        if ($consentCookie) {
            // The cookie is base64 encoded, so decode it first
            $decodedCookie = base64_decode($consentCookie);
            $decoded = json_decode($decodedCookie, true);
            if (is_array($decoded)) {
                // Check for location consent
                return $decoded['location'] ?? false;
            }
        }

        // Fallback: check if location consent was passed as a header or session
        if (session()->has('location_consent')) {
            return session()->get('location_consent') === true;
        }

        return false; // No consent found
    }

    private function getClientIpAddress(): ?string
    {
        // Check for IP from various headers (in order of preference)
        $ipKeys = [
            'HTTP_CF_CONNECTING_IP',     // Cloudflare
            'HTTP_X_FORWARDED_FOR',      // Load balancer/proxy
            'HTTP_X_FORWARDED',
            'HTTP_X_CLUSTER_CLIENT_IP',
            'HTTP_FORWARDED_FOR',
            'HTTP_FORWARDED',
            'HTTP_CLIENT_IP',
            'REMOTE_ADDR'                // Standard
        ];

        foreach ($ipKeys as $key) {
            if (!empty($_SERVER[$key])) {
                $ip = $_SERVER[$key];
                
                // Handle comma-separated IPs (X-Forwarded-For can contain multiple IPs)
                if (strpos($ip, ',') !== false) {
                    $ip = trim(explode(',', $ip)[0]);
                }
                
                // Validate IP
                if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
                    return $ip;
                }
            }
        }

        return request()->ip(); // Laravel fallback
    }

    private function isLocalIp(string $ip): bool
    {
        // Check if IP is localhost, private, or reserved
        return !filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) ||
               in_array($ip, ['127.0.0.1', '::1', 'localhost']);
    }

    private function detectCountryFromIp(string $ip): ?string
    {
        try {
            // Try ip-api.com (free, no API key required, 1000 requests/month)
            $response = Http::timeout(3)->get("http://ip-api.com/json/{$ip}?fields=countryCode");
            
            if ($response->successful()) {
                $data = $response->json();
                if (isset($data['countryCode']) && !empty($data['countryCode'])) {
                    return strtoupper($data['countryCode']);
                }
            }
        } catch (\Exception $e) {
            // Log error but don't fail
            \Log::warning("IP geolocation failed for {$ip}: " . $e->getMessage());
        }

        try {
            // Fallback: Try ipinfo.io (free tier: 50,000 requests/month)
            $response = Http::timeout(3)->get("https://ipinfo.io/{$ip}/json");
            
            if ($response->successful()) {
                $data = $response->json();
                if (isset($data['country']) && !empty($data['country'])) {
                    return strtoupper($data['country']);
                }
            }
        } catch (\Exception $e) {
            // Log error but don't fail
            \Log::warning("IP geolocation fallback failed for {$ip}: " . $e->getMessage());
        }

        return null;
    }
}