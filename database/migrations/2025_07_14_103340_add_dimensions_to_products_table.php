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
        Schema::table('products', function (Blueprint $table) {
            $table->float('height')->nullable()->after('description_2');
            $table->float('width')->nullable()->after('height');
            $table->float('depth')->nullable()->after('width');
            $table->float('weight')->nullable()->after('depth');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['height', 'width', 'depth', 'weight']);
        });
    }
};
