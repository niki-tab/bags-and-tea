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
        Schema::create('order_fees', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('order_id');
            $table->uuid('marketplace_fee_id')->nullable(); // null if manual fee
            $table->string('fee_code'); // 'buyer_protection', 'payment_processing', etc.
            $table->string('fee_name'); // Display name
            $table->enum('fee_type', ['fixed', 'percentage', 'manual']);
            $table->decimal('fee_rate', 5, 4)->nullable(); // For percentage fees
            $table->decimal('applied_to_amount', 8, 2); // Base amount fee was calculated on
            $table->decimal('fee_amount', 8, 2); // Actual fee charged
            $table->boolean('visible_to_customer')->default(true);
            $table->text('calculation_details')->nullable();
            $table->timestamps();
            
            // Indexes
            $table->index('order_id');
            $table->index('marketplace_fee_id');
            $table->index('fee_code');
            
            // Foreign keys
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->foreign('marketplace_fee_id')->references('id')->on('marketplace_fees')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_fees');
    }
};
