<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

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
            // Check if slug is already JSON
            $existingSlugData = json_decode($attribute->slug, true);
            
            if (is_array($existingSlugData)) {
                // Already in JSON format, keep as is
                continue;
            }
            
            // Handle potentially corrupted data - if slug contains invalid characters for JSON
            $cleanSlug = is_string($attribute->slug) ? $attribute->slug : 'default-slug';
            
            // Convert string slug to JSON with both languages
            $slugData = [
                'en' => $cleanSlug,
                'es' => $cleanSlug // Keep same for attributes initially
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
        // First, change the column type back to string to allow string updates
        Schema::table('attributes', function (Blueprint $table) {
            // Change slug column back to VARCHAR
            $table->string('slug', 500)->change();
        });

        // Now convert JSON slugs back to string (using English version)
        $attributes = DB::table('attributes')->get();
        foreach ($attributes as $attribute) {
            $slugData = json_decode($attribute->slug, true);
            
            // Handle different cases of slug data
            if (is_array($slugData)) {
                // It's already proper JSON
                $stringSlug = $slugData['en'] ?? $slugData['es'] ?? 'default-slug';
            } else {
                // It's already a string or invalid JSON, keep as is
                $stringSlug = $attribute->slug;
            }
            
            DB::table('attributes')
                ->where('id', $attribute->id)
                ->update(['slug' => $stringSlug]);
        }

        // Finally, add the unique constraint
        Schema::table('attributes', function (Blueprint $table) {
            $table->unique('slug');
        });
    }
};