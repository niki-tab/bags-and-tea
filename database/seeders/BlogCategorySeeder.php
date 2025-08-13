<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Src\Blog\Categories\Infrastructure\Eloquent\BlogCategoryEloquentModel;
use Illuminate\Support\Str;

class BlogCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => [
                    'es' => 'Autenticidad',
                    'en' => 'Authenticity'
                ],
                'slug' => [
                    'es' => 'autenticidad',
                    'en' => 'authenticity'
                ],
                'description_1' => [
                    'es' => 'Artículos sobre la autenticidad de los productos de lujo y cómo verificar su procedencia.',
                    'en' => 'Articles about the authenticity of luxury products and how to verify their origin.'
                ],
                'description_2' => [
                    'es' => 'Consejos y guías para identificar productos auténticos en el mercado de segunda mano.',
                    'en' => 'Tips and guides to identify authentic products in the second-hand market.'
                ],
                'display_order' => 1,
                'color' => '#3B82F6'
            ],
            [
                'name' => [
                    'es' => 'Moda',
                    'en' => 'Fashion'
                ],
                'slug' => [
                    'es' => 'moda',
                    'en' => 'fashion'
                ],
                'description_1' => [
                    'es' => 'Tendencias actuales en moda y accesorios de lujo.',
                    'en' => 'Current trends in fashion and luxury accessories.'
                ],
                'description_2' => [
                    'es' => 'Guías de estilo y consejos sobre cómo combinar piezas de lujo.',
                    'en' => 'Style guides and tips on how to combine luxury pieces.'
                ],
                'display_order' => 2,
                'color' => '#EF4444'
            ]
        ];

        foreach ($categories as $categoryData) {
            BlogCategoryEloquentModel::create([
                'id' => (string) Str::uuid(),
                'name' => $categoryData['name'],
                'slug' => $categoryData['slug'],
                'description_1' => $categoryData['description_1'],
                'description_2' => $categoryData['description_2'],
                'parent_id' => null,
                'display_order' => $categoryData['display_order'],
                'is_active' => true,
                'color' => $categoryData['color']
            ]);
        }
    }
}
