<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Multi-Tenancy Enabled
    |--------------------------------------------------------------------------
    |
    | Enable or disable multi-tenancy features globally.
    |
    */
    'enabled' => env('MULTITENANCY_ENABLED', true),

    /*
    |--------------------------------------------------------------------------
    | Default Site
    |--------------------------------------------------------------------------
    |
    | The default site slug to use when domain detection fails or for local
    | development.
    |
    */
    'default_site' => env('DEFAULT_SITE', 'bagsandtea'),

    /*
    |--------------------------------------------------------------------------
    | Domain Mapping
    |--------------------------------------------------------------------------
    |
    | Map domains to site slugs. Useful for local development and testing.
    |
    */
    'domain_mapping' => [
        'bagsandtea.com' => 'bagsandtea',
        'www.bagsandtea.com' => 'bagsandtea',
        'shoesandtea.com' => 'shoesandtea',
        'www.shoesandtea.com' => 'shoesandtea',
        'localhost' => env('LOCAL_SITE', 'bagsandtea'),
        '127.0.0.1' => env('LOCAL_SITE', 'bagsandtea'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Site-Aware Tables
    |--------------------------------------------------------------------------
    |
    | List of database tables that have site_id column and should be filtered
    | by the current site context.
    |
    */
    'site_aware_tables' => [
        'products',
        'categories',
        'brands',
        'attributes',
        'qualities',
        'blog_articles',
        'blog_categories',
        'orders',
        'carts',
        'crm_form_submissions',
    ],
];
