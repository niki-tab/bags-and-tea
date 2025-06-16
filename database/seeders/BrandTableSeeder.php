<?php

namespace Database\Seeders;

use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class BrandTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // First, we need to create the Brand model, but since it doesn't exist yet,
        // we'll use DB facade for now
        $brands = [
            [
                'id' => (string) Str::uuid(),
                'name' => json_encode([
                    'en' => 'Louis Vuitton',
                    'es' => 'Louis Vuitton'
                ]),
                'slug' => 'louis-vuitton',
                'logo_url' => null,
                'display_order' => 1,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => (string) Str::uuid(),
                'name' => json_encode([
                    'en' => 'Chanel',
                    'es' => 'Chanel'
                ]),
                'slug' => 'chanel',
                'logo_url' => null,
                'display_order' => 2,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => (string) Str::uuid(),
                'name' => json_encode([
                    'en' => 'Hermès',
                    'es' => 'Hermès'
                ]),
                'slug' => 'hermes',
                'logo_url' => null,
                'display_order' => 3,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => (string) Str::uuid(),
                'name' => json_encode([
                    'en' => 'Gucci',
                    'es' => 'Gucci'
                ]),
                'slug' => 'gucci',
                'logo_url' => null,
                'display_order' => 4,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => (string) Str::uuid(),
                'name' => json_encode([
                    'en' => 'Prada',
                    'es' => 'Prada'
                ]),
                'slug' => 'prada',
                'logo_url' => null,
                'display_order' => 5,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => (string) Str::uuid(),
                'name' => json_encode([
                    'en' => 'Dior',
                    'es' => 'Dior'
                ]),
                'slug' => 'dior',
                'logo_url' => null,
                'display_order' => 6,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => (string) Str::uuid(),
                'name' => json_encode([
                    'en' => 'Fendi',
                    'es' => 'Fendi'
                ]),
                'slug' => 'fendi',
                'logo_url' => null,
                'display_order' => 7,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => (string) Str::uuid(),
                'name' => json_encode([
                    'en' => 'Saint Laurent',
                    'es' => 'Saint Laurent'
                ]),
                'slug' => 'saint-laurent',
                'logo_url' => null,
                'display_order' => 8,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => (string) Str::uuid(),
                'name' => json_encode([
                    'en' => 'Balenciaga',
                    'es' => 'Balenciaga'
                ]),
                'slug' => 'balenciaga',
                'logo_url' => null,
                'display_order' => 9,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => (string) Str::uuid(),
                'name' => json_encode([
                    'en' => 'Bottega Veneta',
                    'es' => 'Bottega Veneta'
                ]),
                'slug' => 'bottega-veneta',
                'logo_url' => null,
                'display_order' => 10,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => (string) Str::uuid(),
                'name' => json_encode([
                    'en' => 'Céline',
                    'es' => 'Céline'
                ]),
                'slug' => 'celine',
                'logo_url' => null,
                'display_order' => 11,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => (string) Str::uuid(),
                'name' => json_encode([
                    'en' => 'Loewe',
                    'es' => 'Loewe'
                ]),
                'slug' => 'loewe',
                'logo_url' => null,
                'display_order' => 12,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => (string) Str::uuid(),
                'name' => json_encode([
                    'en' => 'Miu Miu',
                    'es' => 'Miu Miu'
                ]),
                'slug' => 'miu-miu',
                'logo_url' => null,
                'display_order' => 13,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => (string) Str::uuid(),
                'name' => json_encode([
                    'en' => 'Goyard',
                    'es' => 'Goyard'
                ]),
                'slug' => 'goyard',
                'logo_url' => null,
                'display_order' => 14,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => (string) Str::uuid(),
                'name' => json_encode([
                    'en' => 'Polène',
                    'es' => 'Polène'
                ]),
                'slug' => 'polene',
                'logo_url' => null,
                'display_order' => 15,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($brands as $brand) {
            \DB::table('brands')->insert($brand);
        }
    }
}