<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bag_search_queries', function (Blueprint $table) {
            $table->decimal('min_price', 10, 2)->default(50.00)->after('ideal_price');
        });
    }

    public function down(): void
    {
        Schema::table('bag_search_queries', function (Blueprint $table) {
            $table->dropColumn('min_price');
        });
    }
};
