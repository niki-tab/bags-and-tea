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
        Schema::create('marketplace_fees', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('fee_name'); // 'Buyer Protection', 'Payment Processing', 'Transaction Fee'
            $table->string('fee_code')->unique(); // 'buyer_protection', 'payment_processing'
            $table->text('description')->nullable();
            
            // Fee Calculation
            $table->enum('fee_type', ['fixed', 'percentage', 'tiered'])->default('fixed');
            $table->decimal('fixed_amount', 8, 2)->nullable(); // For fixed fees
            $table->decimal('percentage_rate', 5, 4)->nullable(); // For percentage fees (e.g., 2.9000 = 2.9%)
            $table->json('tiered_rates')->nullable(); // For complex tiered fee structures
            
            // Application Rules
            $table->decimal('minimum_order_amount', 8, 2)->nullable(); // Apply only if order > amount
            $table->decimal('maximum_fee_amount', 8, 2)->nullable(); // Cap the fee amount
            $table->json('applicable_countries')->nullable(); // Apply to specific countries only
            $table->json('applicable_payment_methods')->nullable(); // Apply to specific payment methods
            
            // Status
            $table->boolean('is_active')->default(true);
            $table->timestamp('effective_from')->nullable();
            $table->timestamp('effective_until')->nullable();
            
            // Admin Info
            $table->boolean('visible_to_customer')->default(true); // Show fee in checkout breakdown
            $table->string('customer_label')->nullable(); // How to display to customer
            $table->integer('display_order')->default(0);
            
            $table->timestamps();
            
            // Indexes
            $table->index('fee_code');
            $table->index(['is_active', 'effective_from', 'effective_until']);
            $table->index('display_order');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('marketplace_fees');
    }
};
