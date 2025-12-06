<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bag_search_queries', function (Blueprint $table) {
            $table->unsignedInteger('max_pages')->default(3)->after('vinted_search_url');
            $table->string('page_param')->default('page')->after('max_pages'); // e.g., "page" for Vinted
        });
    }

    public function down(): void
    {
        Schema::table('bag_search_queries', function (Blueprint $table) {
            $table->dropColumn(['max_pages', 'page_param']);
        });
    }
};
