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
        Schema::create('authentication_certificates', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('certificate_number')->unique(); // e.g., CERT-2025-000001
            $table->string('order_number'); // e.g., BT-2025-000023
            $table->uuid('order_item_id'); // Foreign key to order_items table
            $table->uuid('product_id'); // Foreign key to products table
            $table->string('customer_name');
            $table->string('customer_email');
            $table->string('product_qr_url'); // URL to product detail page
            $table->json('product_snapshot'); // Snapshot of product data at time of certification
            $table->timestamp('sent_at'); // When the certificate was sent
            $table->string('sent_by_user_id')->nullable(); // Admin who sent it
            $table->timestamps();

            // Indexes
            $table->index('order_number');
            $table->index('order_item_id');
            $table->index('product_id');
            $table->index('customer_email');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('authentication_certificates');
    }
};
