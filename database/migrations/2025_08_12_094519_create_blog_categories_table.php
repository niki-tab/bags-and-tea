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
        Schema::create('blog_categories', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->json('name'); // Translatable field
            $table->string('slug')->unique();
            $table->uuid('parent_id')->nullable();
            $table->integer('display_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->string('color')->nullable(); // Optional color coding for blog categories
            $table->text('description_1')->nullable(); // First description field
            $table->text('description_2')->nullable(); // Second description field
            $table->timestamps();

            $table->foreign('parent_id')->references('id')->on('blog_categories')->onDelete('cascade');
            $table->index(['parent_id', 'is_active']);
            $table->index(['display_order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blog_categories');
    }
};
