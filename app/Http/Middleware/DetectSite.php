<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Src\Sites\Infrastructure\Eloquent\SiteEloquentModel;
use Illuminate\Support\Facades\Log;

class DetectSite
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Skip if multi-tenancy is disabled
        if (!config('multitenancy.enabled', true)) {
            return $next($request);
        }

        // Get the domain from the request
        $domain = $request->getHost();

        // Try to find site by domain
        $site = SiteEloquentModel::where('domain', $domain)
                                  ->where('is_active', true)
                                  ->first();

        // If not found, try domain mapping from config
        if (!$site) {
            $domainMapping = config('multitenancy.domain_mapping', []);
            $slug = $domainMapping[$domain] ?? null;

            if ($slug) {
                $site = SiteEloquentModel::where('slug', $slug)
                                          ->where('is_active', true)
                                          ->first();
            }
        }

        // If still not found, use default site (this handles localhost, 127.0.0.1, etc.)
        if (!$site) {
            $defaultSlug = config('multitenancy.default_site', 'bagsandtea');
            $site = SiteEloquentModel::where('slug', $defaultSlug)
                                      ->where('is_active', true)
                                      ->first();

            if (!$site) {
                Log::warning("Multi-tenancy: Could not find site for domain '{$domain}' or default site '{$defaultSlug}'");
            }
        }

        // Store site in service container
        if ($site) {
            app()->instance('current_site', $site);

            // Also set in config for easy access
            config(['app.current_site' => $site]);

            // Share with views
            view()->share('currentSite', $site);

            // Register site-specific translation namespace
            $this->registerSiteTranslations($site);

            // Share site-specific configuration with all views
            view()->share('siteConfig', $this->getSiteConfig($site));
        }

        return $next($request);
    }

    /**
     * Register site-specific translations
     */
    protected function registerSiteTranslations($site): void
    {
        $sitePath = resource_path("lang/sites/{$site->slug}");

        if (is_dir($sitePath)) {
            // Add the site-specific path to Laravel's translation loader
            app('translator')->addNamespace("site_{$site->slug}", $sitePath);
        }
    }

    /**
     * Get site-specific configuration for header, footer, etc.
     */
    protected function getSiteConfig($site): array
    {
        // Common menu structure (same for both sites initially)
        $menuItems = [
            [
                'label' => 'components/header.menu-option-1',
                'route_es' => 'repair-your-bag.show.es',
                'route_en' => 'repair-your-bag.show.en',
            ],
            [
                'label' => 'components/header.menu-option-2',
                'route_es' => 'we-buy-your-bag.show.es',
                'route_en' => 'we-buy-your-bag.show.en',
            ],
            [
                'label' => 'components/header.menu-option-3',
                'route_es' => 'shop.show.es',
                'route_en' => 'shop.show.en',
                'has_dropdown' => true,
            ],
            [
                'label' => 'components/header.menu-option-4',
                'route_es' => 'about-us.show.es',
                'route_en' => 'about-us.show.en',
            ],
            [
                'label' => 'components/header.menu-option-5',
                'route_es' => 'blog.show.en-es',
                'route_en' => 'blog.show.en-es',
            ],
            [
                'label' => 'components/header.menu-option-6',
                'route_es' => 'contact.send.es',
                'route_en' => 'contact.send.en',
            ],
        ];

        return match($site->slug) {
            'walletsandtea' => [
                'logo' => asset('images/sites/walletsandtea/logo.svg'),
                'social' => [
                    'instagram' => 'https://www.instagram.com/bags.and.tea?igsh=NTgwcGU2a21paGxk&utm_source=qr',
                ],
                'menu' => [
                    'parent_category' => 'Wallets',
                    'items' => $menuItems, // You can customize this array later
                ],
                'button' => [
                    'route_es' => 'we-buy-your-bag.show.es',
                    'route_en' => 'we-buy-your-bag.show.en',
                ],
            ],
            default => [ // bagsandtea
                'logo' => asset('images/logo/bags_and_tea_logo_new.svg'),
                'social' => [
                    'instagram' => 'https://www.instagram.com/bags.and.tea?igsh=NTgwcGU2a21paGxk&utm_source=qr',
                ],
                'menu' => [
                    'parent_category' => 'Bags',
                    'items' => $menuItems, // You can customize this array later
                ],
                'button' => [
                    'route_es' => 'we-buy-your-bag.show.es',
                    'route_en' => 'we-buy-your-bag.show.en',
                ],
            ],
        };
    }
}
