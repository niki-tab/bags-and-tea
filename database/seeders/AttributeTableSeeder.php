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
                'slug' => 'size',
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
        $sizeAttribute = \DB::table('attributes')->where('slug', 'size')->first();

        // Size children
        $sizeChildren = [
            [
                'id' => (string) Str::uuid(),
                'name' => json_encode(['en' => 'PM (Small)', 'es' => 'PM (Pequeño)']),
                'slug' => 'pm-small',
                'parent_id' => $sizeAttribute->id,
                'display_order' => 1,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => (string) Str::uuid(),
                'name' => json_encode(['en' => 'MM (Medium)', 'es' => 'MM (Mediano)']),
                'slug' => 'mm-medium',
                'parent_id' => $sizeAttribute->id,
                'display_order' => 2,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => (string) Str::uuid(),
                'name' => json_encode(['en' => 'GM (Large)', 'es' => 'GM (Grande)']),
                'slug' => 'gm-large',
                'parent_id' => $sizeAttribute->id,
                'display_order' => 3,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => (string) Str::uuid(),
                'name' => json_encode(['en' => 'One Size', 'es' => 'Talla Única']),
                'slug' => 'one-size',
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