<?php

namespace Database\Seeders;

use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BagSearchQuerySeeder extends Seeder
{
    public function run(): void
    {
        $queries = [
            [
                'id' => (string) Str::uuid(),
                'name' => 'Neverfull MM',
                'brand' => 'Louis Vuitton',
                'ideal_price' => 500.00,
                'min_price' => 350.00,
                'max_price' => 700.00,
                'vinted_search_url' => 'https://www.vinted.es/catalog?search_text=neverfull+mm&order=newest_first',
                'platform' => 'vinted',
                'is_active' => true,
                'last_scanned_at' => null,
                'max_pages' => 5,
                'page_param' => 'page',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => (string) Str::uuid(),
                'name' => 'Neverfull PM',
                'brand' => 'Louis Vuitton',
                'ideal_price' => 500.00,
                'min_price' => 350.00,
                'max_price' => 700.00,
                'vinted_search_url' => 'https://www.vinted.es/catalog?search_text=neverfull+pm&order=newest_first',
                'platform' => 'vinted',
                'is_active' => true,
                'last_scanned_at' => null,
                'max_pages' => 5,
                'page_param' => 'page',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ];

        foreach ($queries as $query) {
            DB::table('bag_search_queries')->insert($query);
        }
    }
}
