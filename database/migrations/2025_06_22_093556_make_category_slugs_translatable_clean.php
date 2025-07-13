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
        // Drop unique constraint first (if it exists)
        try {
            Schema::table('categories', function (Blueprint $table) {
                $table->dropUnique(['slug']);
            });
        } catch (\Exception $e) {
            // Constraint might not exist, continue
        }

        // Convert existing string slugs to JSON format first
        $categories = DB::table('categories')->get();
        foreach ($categories as $category) {
            // Only convert if it's not already JSON
            if (!is_null($category->slug) && !str_starts_with($category->slug, '{')) {
                // Convert string slug to JSON with both languages
                $slugData = [
                    'en' => $category->slug,
                    'es' => $category->slug === 'canvas' ? 'lona' : $category->slug
                ];
                
                DB::table('categories')
                    ->where('id', $category->id)
                    ->update(['slug' => json_encode($slugData)]);
            }
        }

        Schema::table('categories', function (Blueprint $table) {
            // Change slug column from VARCHAR to JSON for translations
            $table->json('slug')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // First change the column type back to string
        Schema::table('categories', function (Blueprint $table) {
            $table->string('slug', 500)->change();
        });

        // Then convert JSON slugs back to string (using English version)
        $categories = DB::table('categories')->get();
        foreach ($categories as $category) {
            if (str_starts_with($category->slug, '{')) {
                $slugData = json_decode($category->slug, true);
                if (is_array($slugData)) {
                    $stringSlug = $slugData['en'] ?? 'unknown';
                } else {
                    $stringSlug = 'unknown';
                }
                
                DB::table('categories')
                    ->where('id', $category->id)
                    ->update(['slug' => $stringSlug]);
            }
        }

        // Finally restore unique constraint (if it doesn't exist)
        try {
            Schema::table('categories', function (Blueprint $table) {
                $table->unique('slug');
            });
        } catch (\Exception $e) {
            // Constraint might already exist, continue
        }
    }
};