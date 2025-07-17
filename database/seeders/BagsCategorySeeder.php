<?php

namespace Database\Seeders;

use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class BagsCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all brands from the database
        $brands = \DB::table('brands')->select('name')->get();
        
        // Create parent category "Bags"
        $parentCategoryId = (string) Str::uuid();
        $parentCategory = [
            'id' => $parentCategoryId,
            'name' => json_encode([
                'en' => 'Bags',
                'es' => 'Bolsos'
            ]),
            'slug' => json_encode([
                'en' => 'bags',
                'es' => 'bolsos'
            ]),
            'description_1' => json_encode([
                'en' => 'Discover our exquisite collection of luxury bags from the world\'s most prestigious brands.',
                'es' => 'Descubre nuestra exquisita colección de bolsos de lujo de las marcas más prestigiosas del mundo.'
            ]),
            'description_2' => json_encode([
                'en' => 'From timeless classics to contemporary designs, find the perfect bag for every occasion.',
                'es' => 'Desde clásicos atemporales hasta diseños contemporáneos, encuentra el bolso perfecto para cada ocasión.'
            ]),
            'parent_id' => null,
            'display_order' => 1,
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ];
        
        \DB::table('categories')->insert($parentCategory);
        
        // Create child categories for each brand
        $displayOrder = 1;
        foreach ($brands as $brand) {
            $brandName = json_decode($brand->name, true);
            $brandNameEn = $brandName['en'];
            $brandNameEs = $brandName['es'];
            
            // Create slug from brand name
            $slugEn = Str::slug($brandNameEn . ' bags');
            $slugEs = Str::slug($brandNameEs . ' bolsos');
            
            $childCategory = [
                'id' => (string) Str::uuid(),
                'name' => json_encode([
                    'en' => $brandNameEn . ' Bags',
                    'es' => 'Bolsos ' . $brandNameEs
                ]),
                'slug' => json_encode([
                    'en' => $slugEn,
                    'es' => $slugEs
                ]),
                'description_1' => json_encode([
                    'en' => 'Explore our curated selection of ' . $brandNameEn . ' bags, featuring iconic designs and exceptional craftsmanship.',
                    'es' => 'Explora nuestra selección curada de bolsos ' . $brandNameEs . ', con diseños icónicos y artesanía excepcional.'
                ]),
                'description_2' => json_encode([
                    'en' => 'Each ' . $brandNameEn . ' bag represents the pinnacle of luxury and style.',
                    'es' => 'Cada bolso ' . $brandNameEs . ' representa la cúspide del lujo y el estilo.'
                ]),
                'parent_id' => $parentCategoryId,
                'display_order' => $displayOrder,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ];
            
            \DB::table('categories')->insert($childCategory);
            $displayOrder++;
        }
    }
}