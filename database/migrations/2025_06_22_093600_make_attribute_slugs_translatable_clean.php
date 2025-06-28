<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('attributes', function (Blueprint $table) {
            // Drop unique constraint first
            $table->dropUnique(['slug']);
        });

        Schema::table('attributes', function (Blueprint $table) {
            // Change slug column from VARCHAR to JSON for translations
            $table->json('slug')->change();
        });

        // Convert existing string slugs to JSON format
        $attributes = DB::table('attributes')->get();
        foreach ($attributes as $attribute) {
            // Convert string slug to JSON with both languages
            $slugData = [
                'en' => $attribute->slug,
                'es' => $attribute->slug // Keep same for attributes initially
            ];
            
            DB::table('attributes')
                ->where('id', $attribute->id)
                ->update(['slug' => json_encode($slugData)]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Convert JSON slugs back to string (using English version)
        $attributes = DB::table('attributes')->get();
        foreach ($attributes as $attribute) {
            $slugData = json_decode($attribute->slug, true);
            $stringSlug = is_array($slugData) ? ($slugData['en'] ?? $attribute->slug) : $attribute->slug;
            
            DB::table('attributes')
                ->where('id', $attribute->id)
                ->update(['slug' => $stringSlug]);
        }

        Schema::table('attributes', function (Blueprint $table) {
            // Change slug column back to VARCHAR
            $table->string('slug')->change();
            // Restore unique constraint
            $table->unique('slug');
        });
    }
};