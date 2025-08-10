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
        Schema::create('order_discounts', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('order_id');
            $table->uuid('discount_code_id')->nullable(); // null if manual discount
            $table->string('discount_code')->nullable(); // Store the actual code used
            $table->string('discount_name'); // Name/description of discount applied
            $table->enum('discount_type', ['percentage', 'fixed_amount', 'free_shipping', 'manual']);
            $table->decimal('discount_value', 8, 2); // Original discount value
            $table->decimal('discount_amount', 8, 2); // Actual amount discounted
            $table->decimal('applied_to_amount', 8, 2); // Base amount discount was calculated on
            $table->text('calculation_details')->nullable(); // How discount was calculated
            $table->timestamps();
            
            // Indexes
            $table->index('order_id');
            $table->index('discount_code_id');
            $table->index('discount_code');
            
            // Foreign keys
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->foreign('discount_code_id')->references('id')->on('discount_codes')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_discounts');
    }
};
