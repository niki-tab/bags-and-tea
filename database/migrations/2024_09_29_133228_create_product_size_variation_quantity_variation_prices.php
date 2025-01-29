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
        Schema::create('product_size_variation_quantity_variation_prices', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('product_id');
            $table->uuid('product_size_variation_id')->nullable();
            $table->uuid('product_quantity_variation_id')->nullable();
            $table->float('shop_price')->nullable();
            $table->float('shop_discounted_price')->nullable();
            $table->float('sale_price');
            $table->float('discounted_price')->nullable();
            $table->string('currency')->default("€");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_size_variation_quantity_variation_prices');
    }
};
