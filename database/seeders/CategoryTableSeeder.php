<?php

namespace Database\Seeders;

use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            // Color Category and its children
            [
                'id' => (string) Str::uuid(),
                'name' => json_encode([
                    'en' => 'Color',
                    'es' => 'Color'
                ]),
                'slug' => 'color',
                'parent_id' => null,
                'display_order' => 1,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Material Category and its children
            [
                'id' => (string) Str::uuid(),
                'name' => json_encode([
                    'en' => 'Material',
                    'es' => 'Material'
                ]),
                'slug' => 'material',
                'parent_id' => null,
                'display_order' => 2,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Collection Category
            [
                'id' => (string) Str::uuid(),
                'name' => json_encode([
                    'en' => 'Collection',
                    'es' => 'Colección'
                ]),
                'slug' => 'collection',
                'parent_id' => null,
                'display_order' => 3,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        // Insert parent categories first
        foreach ($categories as $category) {
            \DB::table('categories')->insert($category);
        }

        // Get parent IDs for children
        $colorCategory = \DB::table('categories')->where('slug', 'color')->first();
        $materialCategory = \DB::table('categories')->where('slug', 'material')->first();
        $collectionCategory = \DB::table('categories')->where('slug', 'collection')->first();

        // Color children
        $colorChildren = [
            [
                'id' => (string) Str::uuid(),
                'name' => json_encode(['en' => 'Brown', 'es' => 'Marrón']),
                'slug' => 'brown',
                'parent_id' => $colorCategory->id,
                'display_order' => 1,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => (string) Str::uuid(),
                'name' => json_encode(['en' => 'Black', 'es' => 'Negro']),
                'slug' => 'black',
                'parent_id' => $colorCategory->id,
                'display_order' => 2,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => (string) Str::uuid(),
                'name' => json_encode(['en' => 'Red', 'es' => 'Rojo']),
                'slug' => 'red',
                'parent_id' => $colorCategory->id,
                'display_order' => 3,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => (string) Str::uuid(),
                'name' => json_encode(['en' => 'Blue', 'es' => 'Azul']),
                'slug' => 'blue',
                'parent_id' => $colorCategory->id,
                'display_order' => 4,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        // Material children
        $materialChildren = [
            [
                'id' => (string) Str::uuid(),
                'name' => json_encode(['en' => 'Leather', 'es' => 'Cuero']),
                'slug' => 'leather',
                'parent_id' => $materialCategory->id,
                'display_order' => 1,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => (string) Str::uuid(),
                'name' => json_encode(['en' => 'Canvas', 'es' => 'Lona']),
                'slug' => 'canvas',
                'parent_id' => $materialCategory->id,
                'display_order' => 2,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => (string) Str::uuid(),
                'name' => json_encode(['en' => 'Patent Leather', 'es' => 'Cuero Charol']),
                'slug' => 'patent-leather',
                'parent_id' => $materialCategory->id,
                'display_order' => 3,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        // Collection children
        $collectionChildren = [
            [
                'id' => (string) Str::uuid(),
                'name' => json_encode(['en' => 'Monogram', 'es' => 'Monogram']),
                'slug' => 'monogram',
                'parent_id' => $collectionCategory->id,
                'display_order' => 1,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => (string) Str::uuid(),
                'name' => json_encode(['en' => 'Damier', 'es' => 'Damier']),
                'slug' => 'damier',
                'parent_id' => $collectionCategory->id,
                'display_order' => 2,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => (string) Str::uuid(),
                'name' => json_encode(['en' => 'Epi', 'es' => 'Epi']),
                'slug' => 'epi',
                'parent_id' => $collectionCategory->id,
                'display_order' => 3,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        // Insert all children
        foreach (array_merge($colorChildren, $materialChildren, $collectionChildren) as $child) {
            \DB::table('categories')->insert($child);
        }
    }
}