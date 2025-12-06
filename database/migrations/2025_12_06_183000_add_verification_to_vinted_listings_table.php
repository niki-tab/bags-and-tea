<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('vinted_listings', function (Blueprint $table) {
            $table->boolean('is_verified_product')->nullable()->after('is_interesting');
            $table->string('verification_reason')->nullable()->after('is_verified_product');
        });
    }

    public function down(): void
    {
        Schema::table('vinted_listings', function (Blueprint $table) {
            $table->dropColumn(['is_verified_product', 'verification_reason']);
        });
    }
};
