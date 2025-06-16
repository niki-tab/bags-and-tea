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
            // Add the new brand_id column
            $table->uuid('brand_id')->nullable()->after('name');
            
            // Add foreign key constraint
            $table->foreign('brand_id')->references('id')->on('brands')->onDelete('set null');
            
            // Remove the old brand string column
            $table->dropColumn('brand');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Add back the brand string column
            $table->string('brand')->after('name');
            
            // Drop foreign key and brand_id column
            $table->dropForeign(['brand_id']);
            $table->dropColumn('brand_id');
        });
    }
};