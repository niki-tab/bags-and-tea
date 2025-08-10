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
        Schema::create('order_items', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('suborder_id');
            $table->uuid('product_id');
            
            // Product details at time of order (for historical accuracy)
            $table->json('product_name'); // Translatable field snapshot
            $table->string('product_sku')->nullable();
            $table->decimal('unit_price', 10, 2);
            $table->integer('quantity');
            $table->decimal('total_price', 10, 2); // unit_price * quantity
            
            // Product snapshot for order history
            $table->json('product_snapshot')->nullable(); // Full product data at time of order
            
            $table->timestamps();
            
            // Indexes
            $table->index('suborder_id');
            $table->index('product_id');
            
            // Foreign keys
            $table->foreign('suborder_id')->references('id')->on('suborders')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
