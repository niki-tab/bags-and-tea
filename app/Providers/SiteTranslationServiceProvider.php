<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Translation\Translator;

class SiteTranslationServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Extend the translator to support site-specific translations
        $this->app->resolving('translator', function (Translator $translator) {
            $translator->addNamespace('site', function ($locale) {
                $siteSlug = site_slug();

                if (!$siteSlug) {
                    return [];
                }

                $sitePath = resource_path("lang/{$locale}/sites/{$siteSlug}");

                if (!is_dir($sitePath)) {
                    return [];
                }

                return $sitePath;
            });
        });
    }

    /**
     * Get a translation with site-specific override support.
     *
     * @param string $key
     * @param array $replace
     * @param string|null $locale
     * @return string
     */
    public static function getSiteTranslation(string $key, array $replace = [], ?string $locale = null): string
    {
        $siteSlug = site_slug();

        if (!$siteSlug) {
            return trans($key, $replace, $locale);
        }

        // Try site-specific translation first
        $siteKey = "sites.{$siteSlug}.{$key}";
        $sitePath = resource_path('lang/' . ($locale ?: app()->getLocale()) . "/sites/{$siteSlug}");

        if (is_dir($sitePath)) {
            $translation = trans($siteKey, $replace, $locale);

            // If translation found (not same as key), return it
            if ($translation !== $siteKey) {
                return $translation;
            }
        }

        // Fall back to default translation
        return trans($key, $replace, $locale);
    }
}
