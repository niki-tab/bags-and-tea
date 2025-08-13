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
        Schema::create('blog_article_category', function (Blueprint $table) {
            $table->uuid('blog_article_id');
            $table->uuid('blog_category_id');
            $table->timestamps();

            $table->foreign('blog_article_id')->references('id')->on('blog_articles')->onDelete('cascade');
            $table->foreign('blog_category_id')->references('id')->on('blog_categories')->onDelete('cascade');
            
            $table->primary(['blog_article_id', 'blog_category_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blog_article_category');
    }
};
