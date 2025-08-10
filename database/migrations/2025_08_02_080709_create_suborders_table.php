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
        Schema::create('suborders', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('order_id');
            $table->uuid('vendor_id');
            $table->string('suborder_number')->unique();
            $table->enum('status', ['pending', 'confirmed', 'processing', 'shipped', 'delivered', 'cancelled'])->default('pending');
            
            // Vendor-specific pricing
            $table->decimal('subtotal', 10, 2);
            $table->decimal('vendor_commission_rate', 5, 2)->default(0); // Percentage
            $table->decimal('commission_amount', 10, 2)->default(0);
            $table->decimal('vendor_payout', 10, 2); // subtotal - commission_amount
            
            // Fulfillment tracking
            $table->string('tracking_number')->nullable();
            $table->string('shipping_carrier')->nullable();
            $table->timestamp('shipped_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            
            // Vendor notes
            $table->text('vendor_notes')->nullable();
            $table->timestamps();
            
            // Indexes
            $table->index('order_id');
            $table->index('vendor_id');
            $table->index('status');
            $table->index('suborder_number');
            
            // Foreign keys
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->foreign('vendor_id')->references('id')->on('vendors')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('suborders');
    }
};
