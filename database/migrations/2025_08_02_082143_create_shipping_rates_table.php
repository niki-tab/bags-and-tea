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
        Schema::create('shipping_rates', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('vendor_id')->nullable(); // null = applies to all vendors (marketplace-wide)
            $table->string('country_code', 2); // ISO country code (ES, FR, DE, etc.)
            $table->string('zone_name')->nullable(); // 'EU', 'Europe', 'Worldwide', etc.
            $table->string('rate_name'); // 'Standard Shipping', 'Express', etc.
            $table->enum('rate_type', ['fixed', 'weight_based', 'free', 'calculated'])->default('fixed');
            $table->decimal('base_rate', 8, 2)->default(0); // Base shipping cost
            $table->decimal('per_kg_rate', 8, 2)->default(0); // Additional cost per kg (for weight_based)
            $table->decimal('free_shipping_threshold', 8, 2)->nullable(); // Free shipping if order > amount
            $table->integer('delivery_days_min')->default(3);
            $table->integer('delivery_days_max')->default(7);
            $table->boolean('is_active')->default(true);
            $table->integer('priority')->default(0); // Higher priority = checked first
            $table->timestamps();
            
            // Indexes
            $table->index(['vendor_id', 'country_code', 'is_active']);
            $table->index(['is_active', 'priority']);
            $table->index('zone_name');
            
            // Foreign key
            $table->foreign('vendor_id')->references('id')->on('vendors')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipping_rates');
    }
};
