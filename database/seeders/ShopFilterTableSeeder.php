<?php

namespace Database\Seeders;

use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ShopFilterTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $shopFilters = [
            [
                'id' => (string) Str::uuid(),
                'name' => json_encode([
                    'en' => 'Brand',
                    'es' => 'Marca'
                ]),
                'type' => 'brand',
                'reference_table' => 'brands',
                'product_column' => null,
                'config' => json_encode([
                    'multiple' => true,
                    'display_type' => 'checkbox'
                ]),
                'display_order' => 1,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => (string) Str::uuid(),
                'name' => json_encode([
                    'en' => 'Color',
                    'es' => 'Color'
                ]),
                'type' => 'category',
                'reference_table' => 'categories',
                'product_column' => null,
                'config' => json_encode([
                    'multiple' => true,
                    'display_type' => 'checkbox',
                    'filter_slug' => 'color'
                ]),
                'display_order' => 2,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => (string) Str::uuid(),
                'name' => json_encode([
                    'en' => 'Quality',
                    'es' => 'Condición'
                ]),
                'type' => 'quality',
                'reference_table' => 'qualities',
                'product_column' => 'quality_id',
                'config' => json_encode([
                    'multiple' => true,
                    'display_type' => 'checkbox'
                ]),
                'display_order' => 3,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => (string) Str::uuid(),
                'name' => json_encode([
                    'en' => 'Material',
                    'es' => 'Material'
                ]),
                'type' => 'category',
                'reference_table' => 'categories',
                'product_column' => null,
                'config' => json_encode([
                    'multiple' => true,
                    'display_type' => 'checkbox',
                    'filter_slug' => 'material'
                ]),
                'display_order' => 4,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => (string) Str::uuid(),
                'name' => json_encode([
                    'en' => 'Size',
                    'es' => 'Talla'
                ]),
                'type' => 'attribute',
                'reference_table' => 'attributes',
                'product_column' => null,
                'config' => json_encode([
                    'multiple' => true,
                    'display_type' => 'checkbox',
                    'filter_slug' => 'size'
                ]),
                'display_order' => 5,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => (string) Str::uuid(),
                'name' => json_encode([
                    'en' => 'Price',
                    'es' => 'Precio'
                ]),
                'type' => 'price',
                'reference_table' => null,
                'product_column' => 'price',
                'config' => json_encode([
                    'multiple' => false,
                    'display_type' => 'range',
                    'ranges' => [
                        ['min' => 0, 'max' => 100, 'label' => '€0 - €100'],
                        ['min' => 100, 'max' => 500, 'label' => '€100 - €500'],
                        ['min' => 500, 'max' => 1000, 'label' => '€500 - €1,000'],
                        ['min' => 1000, 'max' => 2000, 'label' => '€1,000 - €2,000'],
                        ['min' => 2000, 'max' => null, 'label' => '€2,000+'],
                    ]
                ]),
                'display_order' => 6,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($shopFilters as $filter) {
            \DB::table('shop_filters')->insert($filter);
        }
    }
}