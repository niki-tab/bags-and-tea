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
        Schema::create('vinted_listings', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('bag_search_query_id');
            $table->string('vinted_item_id')->unique(); // Vinted's internal ID to avoid duplicates
            $table->string('title');
            $table->decimal('price', 10, 2);
            $table->string('currency', 3)->default('EUR');
            $table->string('url'); // Direct link to the listing
            $table->string('main_image_url')->nullable();
            $table->string('seller_name')->nullable();
            $table->decimal('seller_rating', 3, 2)->nullable();
            $table->string('condition')->nullable(); // e.g. "Very good", "Good", "Satisfactory"
            $table->text('description')->nullable();
            $table->string('size')->nullable();
            $table->string('brand_detected')->nullable(); // Brand detected by AI
            $table->json('raw_data')->nullable(); // Full scraped data for reference
            $table->boolean('is_interesting')->default(false); // Meets price criteria
            $table->boolean('details_scraped')->default(false); // Phase 2 completed
            $table->timestamp('published_at')->nullable(); // When item was published on Vinted (from phase 2)
            $table->boolean('notification_sent')->default(false);
            $table->timestamp('scraped_at')->useCurrent();
            $table->timestamps();

            $table->foreign('bag_search_query_id')
                ->references('id')
                ->on('bag_search_queries')
                ->onDelete('cascade');

            $table->index('bag_search_query_id');
            $table->index('is_interesting');
            $table->index('details_scraped');
            $table->index('notification_sent');
            $table->index('price');
            $table->index('scraped_at');
            $table->index('published_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vinted_listings');
    }
};
