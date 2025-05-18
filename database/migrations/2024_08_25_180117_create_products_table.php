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
        Schema::create('products', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('brand');
            $table->string('slug')->unique();
            $table->longText('description_1')->nullable();
            $table->longText('description_2')->nullable();
            $table->string('origin_general');
            $table->string('origin_specific');
            $table->string('product_type');
            $table->string('quality');
            $table->string('food_type');
            $table->string('species_type');
            $table->float('price_from');
            $table->string('sell_unit');
            $table->string('sell_mode');
            $table->string('stock');
            $table->string('stock_unit');
            $table->boolean('out_of_stock');
            $table->string('image')->nullable();
            $table->boolean('featured')->default(false);
            $table->integer('featured_position')->nullable();
            $table->string('meta_title')->nullable();
            $table->string('meta_description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
