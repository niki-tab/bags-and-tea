<?php

use Illuminate\Support\Str;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Create parent category "Wallets"
        $walletsCategoryId = (string) Str::uuid();

        DB::table('categories')->insert([
            'id' => $walletsCategoryId,
            'name' => json_encode([
                'en' => 'Wallets',
                'es' => 'Carteras'
            ]),
            'slug' => json_encode([
                'en' => 'wallets',
                'es' => 'carteras'
            ]),
            'description_1' => json_encode([
                'en' => 'Discover our exquisite collection of luxury wallets from the world\'s most prestigious brands.',
                'es' => 'Descubre nuestra exquisita colección de carteras de lujo de las marcas más prestigiosas del mundo.'
            ]),
            'description_2' => json_encode([
                'en' => 'From timeless classics to contemporary designs, find the perfect wallet for every style.',
                'es' => 'Desde clásicos atemporales hasta diseños contemporáneos, encuentra la cartera perfecta para cada estilo.'
            ]),
            'parent_id' => null,
            'display_order' => 2,
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Get all brands from the database
        $brands = DB::table('brands')->select('name')->get();

        // Create child categories for each brand
        $displayOrder = 1;
        foreach ($brands as $brand) {
            $brandName = json_decode($brand->name, true);
            $brandNameEn = $brandName['en'];
            $brandNameEs = $brandName['es'];

            // Create slug from brand name
            $slugEn = Str::slug($brandNameEn . ' wallets');
            $slugEs = Str::slug('carteras ' . $brandNameEs);

            DB::table('categories')->insert([
                'id' => (string) Str::uuid(),
                'name' => json_encode([
                    'en' => $brandNameEn . ' Wallets',
                    'es' => 'Carteras ' . $brandNameEs
                ]),
                'slug' => json_encode([
                    'en' => $slugEn,
                    'es' => $slugEs
                ]),
                'description_1' => json_encode([
                    'en' => 'Explore our curated selection of ' . $brandNameEn . ' wallets, featuring iconic designs and exceptional craftsmanship.',
                    'es' => 'Explora nuestra selección curada de carteras ' . $brandNameEs . ', con diseños icónicos y artesanía excepcional.'
                ]),
                'description_2' => json_encode([
                    'en' => 'Each ' . $brandNameEn . ' wallet represents the pinnacle of luxury and style.',
                    'es' => 'Cada cartera ' . $brandNameEs . ' representa la cúspide del lujo y el estilo.'
                ]),
                'parent_id' => $walletsCategoryId,
                'display_order' => $displayOrder,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $displayOrder++;
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // First get the Wallets parent category ID
        $walletsCategory = DB::table('categories')
            ->whereRaw('JSON_UNQUOTE(JSON_EXTRACT(name, "$.en")) = ?', ['Wallets'])
            ->first();

        if ($walletsCategory) {
            // Delete all children first
            DB::table('categories')
                ->where('parent_id', $walletsCategory->id)
                ->delete();

            // Then delete the parent
            DB::table('categories')
                ->where('id', $walletsCategory->id)
                ->delete();
        }
    }
};
