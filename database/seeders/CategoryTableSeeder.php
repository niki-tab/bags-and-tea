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
                'slug' => json_encode([
                    'en' => 'color',
                    'es' => 'color'
                ]),
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
                'slug' => json_encode([
                    'en' => 'material',
                    'es' => 'material'
                ]),
                'description_1' => json_encode([
                    'en' => 'Explore our collection of luxury handbags crafted from the finest materials.',
                    'es' => 'Explora nuestra colección de bolsos de lujo elaborados con los mejores materiales.'
                ]),
                'description_2' => json_encode([
                    'en' => 'Each material tells a story of craftsmanship and elegance.',
                    'es' => 'Cada material cuenta una historia de artesanía y elegancia.'
                ]),
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
                'slug' => json_encode([
                    'en' => 'collection',
                    'es' => 'coleccion'
                ]),
                'parent_id' => null,
                'display_order' => 3,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Bag Type Category
            [
                'id' => (string) Str::uuid(),
                'name' => json_encode([
                    'en' => 'Bag Type',
                    'es' => 'Tipo de Bolso'
                ]),
                'slug' => json_encode([
                    'en' => 'bag-type',
                    'es' => 'tipo-de-bolso'
                ]),
                'description_1' => json_encode([
                    'en' => 'Discover the perfect handbag style for every occasion and lifestyle.',
                    'es' => 'Descubre el estilo de bolso perfecto para cada ocasión y estilo de vida.'
                ]),
                'description_2' => json_encode([
                    'en' => 'From elegant clutches to practical totes, find your signature style.',
                    'es' => 'Desde elegantes clutches hasta prácticos totes, encuentra tu estilo distintivo.'
                ]),
                'parent_id' => null,
                'display_order' => 4,
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
        $colorCategory = \DB::table('categories')->where('slug->en', 'color')->first();
        $materialCategory = \DB::table('categories')->where('slug->en', 'material')->first();
        $collectionCategory = \DB::table('categories')->where('slug->en', 'collection')->first();
        $bagTypeCategory = \DB::table('categories')->where('slug->en', 'bag-type')->first();

        // Color children
        $colorChildren = [
            [
                'id' => (string) Str::uuid(),
                'name' => json_encode(['en' => 'Brown', 'es' => 'Marrón']),
                'slug' => json_encode(['en' => 'brown', 'es' => 'marron']),
                'parent_id' => $colorCategory->id,
                'display_order' => 1,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => (string) Str::uuid(),
                'name' => json_encode(['en' => 'Black', 'es' => 'Negro']),
                'slug' => json_encode(['en' => 'black', 'es' => 'negro']),
                'parent_id' => $colorCategory->id,
                'display_order' => 2,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => (string) Str::uuid(),
                'name' => json_encode(['en' => 'Red', 'es' => 'Rojo']),
                'slug' => json_encode(['en' => 'red', 'es' => 'rojo']),
                'parent_id' => $colorCategory->id,
                'display_order' => 3,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => (string) Str::uuid(),
                'name' => json_encode(['en' => 'Blue', 'es' => 'Azul']),
                'slug' => json_encode(['en' => 'blue', 'es' => 'azul']),
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
                'slug' => json_encode(['en' => 'leather', 'es' => 'cuero']),
                'description_1' => json_encode([
                    'en' => 'Luxurious leather handbags crafted from the finest materials.',
                    'es' => 'Lujosos bolsos de piel elaborados con los mejores materiales.'
                ]),
                'description_2' => json_encode([
                    'en' => 'Timeless elegance meets superior craftsmanship.',
                    'es' => 'La elegancia atemporal se encuentra con la artesanía superior.'
                ]),
                'parent_id' => $materialCategory->id,
                'display_order' => 1,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => (string) Str::uuid(),
                'name' => json_encode(['en' => 'Canvas', 'es' => 'Lona']),
                'slug' => json_encode(['en' => 'canvas', 'es' => 'lona']),
                'description_1' => json_encode([
                    'en' => 'Premium canvas handbags with timeless appeal and exceptional durability.',
                    'es' => 'Bolsos de lona premium con atractivo atemporal y durabilidad excepcional.'
                ]),
                'description_2' => json_encode([
                    'en' => 'Canvas bags offer versatility and style for every occasion.',
                    'es' => 'Los bolsos de lona ofrecen versatilidad y estilo para cada ocasión.'
                ]),
                'parent_id' => $materialCategory->id,
                'display_order' => 2,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => (string) Str::uuid(),
                'name' => json_encode(['en' => 'Patent Leather', 'es' => 'Cuero Charol']),
                'slug' => json_encode(['en' => 'patent-leather', 'es' => 'cuero-charol']),
                'description_1' => json_encode([
                    'en' => 'Glossy patent leather handbags that make a bold statement.',
                    'es' => 'Bolsos de cuero charol brillante que hacen una declaración audaz.'
                ]),
                'description_2' => json_encode([
                    'en' => 'Modern sophistication with a touch of glamour.',
                    'es' => 'Sofisticación moderna con un toque de glamour.'
                ]),
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
                'slug' => json_encode(['en' => 'monogram', 'es' => 'monogram']),
                'parent_id' => $collectionCategory->id,
                'display_order' => 1,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => (string) Str::uuid(),
                'name' => json_encode(['en' => 'Damier', 'es' => 'Damier']),
                'slug' => json_encode(['en' => 'damier', 'es' => 'damier']),
                'parent_id' => $collectionCategory->id,
                'display_order' => 2,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => (string) Str::uuid(),
                'name' => json_encode(['en' => 'Epi', 'es' => 'Epi']),
                'slug' => json_encode(['en' => 'epi', 'es' => 'epi']),
                'parent_id' => $collectionCategory->id,
                'display_order' => 3,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        // Bag Type children
        $bagTypeChildren = [
            [
                'id' => (string) Str::uuid(),
                'name' => json_encode(['en' => 'Tote', 'es' => 'Tote']),
                'slug' => json_encode(['en' => 'tote', 'es' => 'tote']),
                'description_1' => json_encode([
                    'en' => 'Spacious and versatile tote bags for everyday elegance.',
                    'es' => 'Bolsos tote espaciosos y versátiles para elegancia diaria.'
                ]),
                'description_2' => json_encode([
                    'en' => 'Perfect for work, travel, and sophisticated daily use.',
                    'es' => 'Perfectos para el trabajo, viajes y uso diario sofisticado.'
                ]),
                'parent_id' => $bagTypeCategory->id,
                'display_order' => 1,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => (string) Str::uuid(),
                'name' => json_encode(['en' => 'Shoulder Bag', 'es' => 'Bolso de Hombro']),
                'slug' => json_encode(['en' => 'shoulder-bag', 'es' => 'bolso-de-hombro']),
                'description_1' => json_encode([
                    'en' => 'Classic shoulder bags combining comfort and style.',
                    'es' => 'Bolsos de hombro clásicos que combinan comodidad y estilo.'
                ]),
                'description_2' => json_encode([
                    'en' => 'Timeless designs for effortless sophistication.',
                    'es' => 'Diseños atemporales para sofisticación sin esfuerzo.'
                ]),
                'parent_id' => $bagTypeCategory->id,
                'display_order' => 2,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => (string) Str::uuid(),
                'name' => json_encode(['en' => 'Clutch', 'es' => 'Clutch']),
                'slug' => json_encode(['en' => 'clutch', 'es' => 'clutch']),
                'description_1' => json_encode([
                    'en' => 'Elegant clutches for special occasions and evening events.',
                    'es' => 'Clutches elegantes para ocasiones especiales y eventos nocturnos.'
                ]),
                'description_2' => json_encode([
                    'en' => 'Sophisticated minimalism for your most important moments.',
                    'es' => 'Minimalismo sofisticado para tus momentos más importantes.'
                ]),
                'parent_id' => $bagTypeCategory->id,
                'display_order' => 3,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => (string) Str::uuid(),
                'name' => json_encode(['en' => 'Crossbody', 'es' => 'Bandolera']),
                'slug' => json_encode(['en' => 'crossbody', 'es' => 'bandolera']),
                'description_1' => json_encode([
                    'en' => 'Hands-free crossbody bags for active lifestyles.',
                    'es' => 'Bolsos bandolera manos libres para estilos de vida activos.'
                ]),
                'description_2' => json_encode([
                    'en' => 'Perfect blend of functionality and fashion-forward design.',
                    'es' => 'Mezcla perfecta de funcionalidad y diseño vanguardista.'
                ]),
                'parent_id' => $bagTypeCategory->id,
                'display_order' => 4,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => (string) Str::uuid(),
                'name' => json_encode(['en' => 'Backpack', 'es' => 'Mochila']),
                'slug' => json_encode(['en' => 'backpack', 'es' => 'mochila']),
                'description_1' => json_encode([
                    'en' => 'Luxury backpacks merging style with practical functionality.',
                    'es' => 'Mochilas de lujo que fusionan estilo con funcionalidad práctica.'
                ]),
                'description_2' => json_encode([
                    'en' => 'Contemporary elegance for the modern woman.',
                    'es' => 'Elegancia contemporánea para la mujer moderna.'
                ]),
                'parent_id' => $bagTypeCategory->id,
                'display_order' => 5,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => (string) Str::uuid(),
                'name' => json_encode(['en' => 'Wallet', 'es' => 'Cartera']),
                'slug' => json_encode(['en' => 'wallet', 'es' => 'cartera']),
                'description_1' => json_encode([
                    'en' => 'Luxury wallets and small leather goods for everyday elegance.',
                    'es' => 'Carteras de lujo y pequeños artículos de piel para elegancia diaria.'
                ]),
                'description_2' => json_encode([
                    'en' => 'Exquisite craftsmanship in every detail.',
                    'es' => 'Artesanía exquisita en cada detalle.'
                ]),
                'parent_id' => $bagTypeCategory->id,
                'display_order' => 6,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => (string) Str::uuid(),
                'name' => json_encode(['en' => 'Belt Bag', 'es' => 'Riñonera']),
                'slug' => json_encode(['en' => 'belt-bag', 'es' => 'rinonera']),
                'description_1' => json_encode([
                    'en' => 'Modern belt bags for contemporary style and convenience.',
                    'es' => 'Riñoneras modernas para estilo contemporáneo y conveniencia.'
                ]),
                'description_2' => json_encode([
                    'en' => 'Fashion-forward functionality for the style-conscious.',
                    'es' => 'Funcionalidad vanguardista para los conscientes del estilo.'
                ]),
                'parent_id' => $bagTypeCategory->id,
                'display_order' => 7,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => (string) Str::uuid(),
                'name' => json_encode(['en' => 'Handbag', 'es' => 'Bolso de Mano']),
                'slug' => json_encode(['en' => 'handbag', 'es' => 'bolso-de-mano']),
                'description_1' => json_encode([
                    'en' => 'Classic handbags embodying timeless luxury and sophistication.',
                    'es' => 'Bolsos de mano clásicos que encarnan lujo atemporal y sofisticación.'
                ]),
                'description_2' => json_encode([
                    'en' => 'The epitome of refined elegance and quality.',
                    'es' => 'El epítome de elegancia refinada y calidad.'
                ]),
                'parent_id' => $bagTypeCategory->id,
                'display_order' => 8,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        // Insert all children
        foreach (array_merge($colorChildren, $materialChildren, $collectionChildren, $bagTypeChildren) as $child) {
            \DB::table('categories')->insert($child);
        }
    }
}