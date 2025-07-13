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
        Schema::create('products', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('brand');
            $table->string('slug')->unique();
            $table->string('status')->default("approved");
            $table->longText('description_1')->nullable();
            $table->longText('description_2')->nullable();
            $table->string('origin_country')->nullable();
            $table->enum('product_type', ['simple', 'variable'])->default('simple');
            $table->string('quality_id');
            $table->float('price');
            $table->float('discounted_price')->nullable();
            $table->float('deal_price')->nullable();
            $table->enum('sell_mode', ['unit', 'gouped'])->default('unit');
            $table->integer('sell_mode_quantity')->default(1);
            $table->integer('stock')->default(1);
            $table->enum('stock_unit', ['unit', 'meter', 'roll'])->default('unit');
            $table->boolean('out_of_stock')->default(false);
            $table->boolean('is_sold_out')->default(false);
            $table->boolean('is_hidden')->default(false);
            $table->string('image')->nullable();
            $table->boolean('featured')->default(false);
            $table->integer('featured_position')->nullable();
            $table->string('meta_title')->nullable();
            $table->string('meta_description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
        Schema::dropIfExists('qualities');
    }
};
