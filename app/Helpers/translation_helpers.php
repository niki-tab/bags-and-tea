<?php

if (!function_exists('site_trans')) {
    /**
     * Translate with site-specific override support.
     *
     * Looks for translations in: lang/{locale}/sites/{site_slug}/{key}
     * Falls back to: lang/{locale}/{key}
     *
     * @param string $key
     * @param array $replace
     * @param string|null $locale
     * @return string
     */
    function site_trans(string $key, array $replace = [], ?string $locale = null): string
    {
        $siteSlug = site_slug();
        $locale = $locale ?: app()->getLocale();

        if ($siteSlug) {
            // Use the key as-is with slashes for Laravel's namespace translation system
            // Format: site_{slug}::path/to/file.key
            $siteKey = "site_{$siteSlug}::{$key}";

            // Attempt to translate with site-specific key
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

if (!function_exists('__site')) {
    /**
     * Alias for site_trans() to match Laravel's __() convention
     */
    function __site(string $key, array $replace = [], ?string $locale = null): string
    {
        return site_trans($key, $replace, $locale);
    }
}
