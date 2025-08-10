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
        Schema::create('discount_codes', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('code')->unique(); // SUMMER2024, WELCOME10, NEWCUSTOMER
            $table->string('name'); // Display name for admin
            $table->text('description')->nullable();
            
            // Discount Type and Value
            $table->enum('type', ['percentage', 'fixed_amount', 'free_shipping', 'buy_x_get_y'])->default('percentage');
            $table->decimal('value', 8, 2); // 10 for 10%, or 25.00 for €25 off
            $table->decimal('minimum_order_amount', 8, 2)->nullable(); // Minimum order to apply
            $table->decimal('maximum_discount_amount', 8, 2)->nullable(); // Cap for percentage discounts
            
            // Usage Limits
            $table->integer('usage_limit')->nullable(); // Total times code can be used
            $table->integer('usage_limit_per_customer')->nullable(); // Per customer limit
            $table->integer('times_used')->default(0); // Usage counter
            
            // Time Constraints
            $table->timestamp('valid_from')->nullable();
            $table->timestamp('valid_until')->nullable();
            $table->boolean('is_active')->default(true);
            
            // Product/Category Scope
            $table->json('applicable_products')->nullable(); // Product UUIDs (null = all products)
            $table->json('applicable_categories')->nullable(); // Category UUIDs (null = all categories)
            $table->json('excluded_products')->nullable(); // Product UUIDs to exclude
            
            // Customer Scope
            $table->boolean('first_time_customers_only')->default(false);
            $table->json('applicable_customer_emails')->nullable(); // Specific customers only
            
            $table->timestamps();
            
            // Indexes
            $table->index('code');
            $table->index(['is_active', 'valid_from', 'valid_until']);
            $table->index('first_time_customers_only');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('discount_codes');
    }
};
