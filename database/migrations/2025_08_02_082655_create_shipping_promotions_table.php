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
        Schema::create('shipping_promotions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name'); // 'Summer Free Shipping', 'Black Friday €5 Shipping'
            $table->string('code')->nullable(); // Optional promotion code
            $table->text('description')->nullable();
            
            // Promotion Rules
            $table->enum('promotion_type', ['free_shipping', 'fixed_rate', 'percentage_off', 'free_over_amount'])->default('free_shipping');
            $table->decimal('fixed_rate', 8, 2)->nullable(); // For fixed_rate type
            $table->decimal('percentage_off', 5, 2)->nullable(); // For percentage_off type (e.g., 50.00 = 50% off)
            $table->decimal('free_over_amount', 8, 2)->nullable(); // Free shipping if order > this amount
            
            // Geographic Scope
            $table->json('applicable_countries')->nullable(); // ['ES', 'FR'] or null = all
            $table->json('applicable_zones')->nullable(); // ['EU', 'Worldwide'] or null = all
            
            // Vendor Scope
            $table->uuid('vendor_id')->nullable(); // null = applies to all vendors
            
            // Time Constraints
            $table->timestamp('valid_from')->nullable();
            $table->timestamp('valid_until')->nullable();
            $table->boolean('is_active')->default(true);
            
            // Additional Constraints
            $table->decimal('minimum_order_amount', 8, 2)->nullable(); // Minimum order to apply
            $table->integer('usage_limit')->nullable(); // Total times promotion can be used
            $table->integer('times_used')->default(0); // Usage counter
            
            // Priority for overlapping promotions
            $table->integer('priority')->default(0); // Higher priority wins
            
            $table->timestamps();
            
            // Indexes
            $table->index(['is_active', 'valid_from', 'valid_until']);
            $table->index(['vendor_id', 'is_active']);
            $table->index('priority');
            $table->index('code');
            
            // Foreign key
            $table->foreign('vendor_id')->references('id')->on('vendors')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipping_promotions');
    }
};
