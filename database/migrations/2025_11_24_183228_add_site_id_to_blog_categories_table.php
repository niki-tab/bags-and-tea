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
        Schema::table('blog_categories', function (Blueprint $table) {
            $table->foreignUuid('site_id')
                ->nullable()
                ->after('id')
                ->constrained('sites')
                ->nullOnDelete();

            $table->index('site_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('blog_categories', function (Blueprint $table) {
            $table->dropForeign(['site_id']);
            $table->dropIndex(['site_id']);
            $table->dropColumn('site_id');
        });
    }
};
