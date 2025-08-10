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
        Schema::create('vendor_commissions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('suborder_id');
            $table->uuid('vendor_id');
            
            // Commission calculation
            $table->decimal('order_amount', 10, 2); // Suborder subtotal
            $table->decimal('commission_rate', 5, 2); // Percentage at time of order
            $table->decimal('commission_amount', 10, 2); // Calculated commission
            $table->decimal('vendor_payout', 10, 2); // Amount owed to vendor
            
            // Payout tracking
            $table->enum('payout_status', ['pending', 'processing', 'paid', 'failed'])->default('pending');
            $table->timestamp('payout_date')->nullable();
            $table->string('payout_reference')->nullable(); // Bank transfer ref, etc.
            $table->text('payout_notes')->nullable();
            
            $table->timestamps();
            
            // Indexes
            $table->index('suborder_id');
            $table->index('vendor_id');
            $table->index('payout_status');
            
            // Foreign keys
            $table->foreign('suborder_id')->references('id')->on('suborders')->onDelete('cascade');
            $table->foreign('vendor_id')->references('id')->on('vendors')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vendor_commissions');
    }
};
