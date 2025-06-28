<?php

namespace Database\Seeders;

use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AttributeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $attributes = [
            // Size Attribute and its children
            [
                'id' => (string) Str::uuid(),
                'name' => json_encode([
                    'en' => 'Size',
                    'es' => 'Talla'
                ]),
                'slug' => json_encode([
                    'en' => 'size',
                    'es' => 'talla'
                ]),
                'description_1' => json_encode([
                    'en' => 'Find your perfect handbag size from our curated selection.',
                    'es' => 'Encuentra el tamaño perfecto de bolso en nuestra selección curada.'
                ]),
                'description_2' => json_encode([
                    'en' => 'From compact to spacious, choose the size that fits your lifestyle.',
                    'es' => 'Desde compacto hasta espacioso, elige el tamaño que se adapte a tu estilo de vida.'
                ]),
                'parent_id' => null,
                'display_order' => 1,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        // Insert parent attributes first
        foreach ($attributes as $attribute) {
            \DB::table('attributes')->insert($attribute);
        }

        // Get parent IDs for children
        $sizeAttribute = \DB::table('attributes')->where('slug->en', 'size')->first();

        // Size children
        $sizeChildren = [
            [
                'id' => (string) Str::uuid(),
                'name' => json_encode(['en' => 'PM (Small)', 'es' => 'PM (Pequeño)']),
                'slug' => json_encode(['en' => 'pm-small', 'es' => 'pm-pequeno']),
                'description_1' => json_encode([
                    'en' => 'Compact and elegant, perfect for everyday essentials.',
                    'es' => 'Compacto y elegante, perfecto para lo esencial del día a día.'
                ]),
                'description_2' => json_encode([
                    'en' => 'Small size handbags for sophisticated simplicity.',
                    'es' => 'Bolsos de tamaño pequeño para simplicidad sofisticada.'
                ]),
                'parent_id' => $sizeAttribute->id,
                'display_order' => 1,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => (string) Str::uuid(),
                'name' => json_encode(['en' => 'MM (Medium)', 'es' => 'MM (Mediano)']),
                'slug' => json_encode(['en' => 'mm-medium', 'es' => 'mm-mediano']),
                'description_1' => json_encode([
                    'en' => 'The perfect balance of style and functionality.',
                    'es' => 'El equilibrio perfecto entre estilo y funcionalidad.'
                ]),
                'description_2' => json_encode([
                    'en' => 'Medium size handbags for versatile elegance.',
                    'es' => 'Bolsos de tamaño mediano para elegancia versátil.'
                ]),
                'parent_id' => $sizeAttribute->id,
                'display_order' => 2,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => (string) Str::uuid(),
                'name' => json_encode(['en' => 'GM (Large)', 'es' => 'GM (Grande)']),
                'slug' => json_encode(['en' => 'gm-large', 'es' => 'gm-grande']),
                'description_1' => json_encode([
                    'en' => 'Spacious luxury handbags for the woman on the go.',
                    'es' => 'Bolsos de lujo espaciosos para la mujer en movimiento.'
                ]),
                'description_2' => json_encode([
                    'en' => 'Large size handbags for maximum style and storage.',
                    'es' => 'Bolsos de tamaño grande para máximo estilo y almacenamiento.'
                ]),
                'parent_id' => $sizeAttribute->id,
                'display_order' => 3,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => (string) Str::uuid(),
                'name' => json_encode(['en' => 'One Size', 'es' => 'Talla Única']),
                'slug' => json_encode(['en' => 'one-size', 'es' => 'talla-unica']),
                'parent_id' => $sizeAttribute->id,
                'display_order' => 4,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        // Insert all children
        foreach ($sizeChildren as $child) {
            \DB::table('attributes')->insert($child);
        }
    }
}