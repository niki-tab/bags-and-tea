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
        Schema::create('sites', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name'); // "Bags & Tea", "Shoes & Tea"
            $table->string('slug')->unique(); // "bagsandtea", "shoesandtea"
            $table->string('domain')->unique(); // "bagsandtea.com", "shoesandtea.com"
            $table->boolean('is_active')->default(true);
            $table->json('settings')->nullable(); // Site-specific settings (theme, logo, email, etc.)
            $table->timestamps();

            $table->index(['domain', 'is_active']);
            $table->index('slug');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sites');
    }
};
