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
        Schema::create('bag_search_queries', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name'); // e.g. "Chanel Classic Flap Medium"
            $table->string('brand')->nullable(); // e.g. "Chanel", "Louis Vuitton"
            $table->decimal('ideal_price', 10, 2); // Target price we're looking for
            $table->decimal('max_price', 10, 2)->nullable(); // Maximum price to consider
            $table->string('vinted_search_url'); // Full Vinted search URL with filters
            $table->enum('platform', ['vinted'])->default('vinted'); // For future platform support
            $table->boolean('is_active')->default(true);
            $table->timestamp('last_scanned_at')->nullable();
            $table->timestamps();

            $table->index('is_active');
            $table->index('platform');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bag_search_queries');
    }
};
