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
        Schema::create('shop_filters', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->json('name'); // Translatable field - Display name of the filter
            $table->string('type'); // category, attribute, brand, quality, price
            $table->string('reference_table')->nullable(); // For DB-driven filters (categories, attributes, brands, qualities)
            $table->string('product_column')->nullable(); // For direct product column filters
            $table->json('config')->nullable(); // Additional configuration (e.g., price ranges)
            $table->integer('display_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['is_active', 'display_order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shop_filters');
    }
};