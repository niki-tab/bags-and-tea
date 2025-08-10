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
        Schema::create('orders', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id')->nullable();
            $table->string('order_number')->unique();
            $table->enum('status', ['pending', 'processing', 'shipped', 'delivered', 'cancelled', 'refunded'])->default('pending');
            $table->enum('payment_status', ['pending', 'paid', 'failed', 'refunded'])->default('pending');
            $table->string('payment_method')->nullable(); // 'stripe_card', 'stripe_klarna', 'stripe_paypal'
            $table->string('payment_intent_id')->nullable(); // Stripe Payment Intent ID
            $table->string('currency', 3)->default('EUR');
            
            // Customer information
            $table->string('customer_email');
            $table->string('customer_name');
            $table->string('customer_phone')->nullable();
            
            // Billing address
            $table->json('billing_address'); // {line1, line2, city, postal_code, country, state}
            
            // Shipping address  
            $table->json('shipping_address'); // {line1, line2, city, postal_code, country, state}
            
            // Pricing Breakdown (total across all vendors)
            $table->decimal('subtotal', 10, 2); // Sum of all suborder subtotals
            $table->decimal('total_discounts', 10, 2)->default(0); // Sum from order_discounts table
            $table->decimal('total_fees', 10, 2)->default(0); // Sum from order_fees table  
            $table->decimal('shipping_amount', 10, 2)->default(0); // Total shipping cost
            $table->uuid('shipping_promotion_id')->nullable(); // Applied shipping promotion
            $table->decimal('tax_amount', 10, 2)->default(0); // VAT/taxes
            $table->decimal('total_amount', 10, 2); // Final charged amount
            
            // Additional info
            $table->text('notes')->nullable();
            $table->timestamps();
            
            // Indexes
            $table->index('user_id');
            $table->index('status');
            $table->index('payment_status');
            $table->index('order_number');
            $table->index('customer_email');
            $table->index('shipping_promotion_id');
            
            // Foreign keys
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            // Note: shipping_promotion_id foreign key will be added after shipping_promotions table is created
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
