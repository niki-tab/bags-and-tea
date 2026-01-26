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
        // Create root category "Popular Models"
        $popularModelsCategoryId = (string) Str::uuid();

        DB::table('categories')->insert([
            'id' => $popularModelsCategoryId,
            'name' => json_encode([
                'en' => 'Popular Models',
                'es' => 'Modelos Populares'
            ]),
            'slug' => json_encode([
                'en' => 'popular-models',
                'es' => 'modelos-populares'
            ]),
            'description_1' => json_encode([
                'en' => 'Discover our selection of the most iconic and sought-after bag models. Speedy, Neverfull, Alma, Baguette and more legendary designs from the best luxury brands.',
                'es' => 'Descubre nuestra selección de los modelos de bolsos más icónicos y buscados. Speedy, Neverfull, Alma, Baguette y más diseños legendarios de las mejores marcas de lujo.'
            ]),
            'description_2' => json_encode([
                'en' => '',
                'es' => ''
            ]),
            'meta_title' => json_encode([
                'en' => 'Popular Bag Models | Bags & Tea',
                'es' => 'Modelos Populares de Bolsos | Bags & Tea'
            ]),
            'meta_description' => json_encode([
                'en' => 'Shop the most iconic luxury bag models. Find Speedy, Neverfull, Alma, Baguette and other legendary designs from Louis Vuitton, Gucci, Fendi and more.',
                'es' => 'Compra los modelos de bolsos de lujo más icónicos. Encuentra Speedy, Neverfull, Alma, Baguette y otros diseños legendarios de Louis Vuitton, Gucci, Fendi y más.'
            ]),
            'parent_id' => null,
            'display_order' => 100, // High order to keep it at the end
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Delete the Popular Models category by slug
        DB::table('categories')
            ->whereRaw('JSON_UNQUOTE(JSON_EXTRACT(slug, "$.en")) = ?', ['popular-models'])
            ->delete();
    }
};
