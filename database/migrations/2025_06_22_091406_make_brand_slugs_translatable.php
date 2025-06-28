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
        // Add a temporary column to store the JSON data
        DB::statement('ALTER TABLE brands ADD COLUMN slug_temp JSON');
        
        // Migrate existing data to JSON format in the temp column
        DB::statement("UPDATE brands SET slug_temp = JSON_OBJECT('en', slug, 'es', slug)");
        
        // Drop the old slug column and rename the temp column
        DB::statement('ALTER TABLE brands DROP COLUMN slug');
        DB::statement('ALTER TABLE brands CHANGE slug_temp slug JSON');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Add a temporary column to store the string data
        DB::statement('ALTER TABLE brands ADD COLUMN slug_temp VARCHAR(255)');
        
        // Extract English slug as the primary slug
        DB::statement("UPDATE brands SET slug_temp = JSON_UNQUOTE(JSON_EXTRACT(slug, '$.en'))");
        
        // Drop the JSON slug column and rename the temp column
        DB::statement('ALTER TABLE brands DROP COLUMN slug');
        DB::statement('ALTER TABLE brands CHANGE slug_temp slug VARCHAR(255)');
    }
};
