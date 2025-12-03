<?php

use Src\Sites\Infrastructure\Eloquent\SiteEloquentModel;

if (!function_exists('current_site')) {
    /**
     * Get the current site instance.
     *
     * @return SiteEloquentModel|null
     */
    function current_site(): ?SiteEloquentModel
    {
        if (!app()->has('current_site')) {
            return null;
        }

        return app('current_site');
    }
}

if (!function_exists('site_id')) {
    /**
     * Get the current site ID.
     *
     * @return string|null
     */
    function site_id(): ?string
    {
        return current_site()?->id;
    }
}

if (!function_exists('site_slug')) {
    /**
     * Get the current site slug.
     *
     * @return string|null
     */
    function site_slug(): ?string
    {
        return current_site()?->slug;
    }
}

if (!function_exists('is_site')) {
    /**
     * Check if the current site matches the given slug.
     *
     * @param string $slug
     * @return bool
     */
    function is_site(string $slug): bool
    {
        return current_site()?->slug === $slug;
    }
}

if (!function_exists('site_setting')) {
    /**
     * Get a site-specific setting value.
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    function site_setting(string $key, $default = null)
    {
        $site = current_site();

        if (!$site) {
            return $default;
        }

        return data_get($site->settings, $key, $default);
    }
}
