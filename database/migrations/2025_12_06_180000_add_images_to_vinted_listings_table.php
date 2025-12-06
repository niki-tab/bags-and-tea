<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('vinted_listings', function (Blueprint $table) {
            $table->json('images')->nullable()->after('main_image_url');
            $table->string('uploaded_text')->nullable()->after('published_at'); // "Subido hace 5 horas"
        });
    }

    public function down(): void
    {
        Schema::table('vinted_listings', function (Blueprint $table) {
            $table->dropColumn(['images', 'uploaded_text']);
        });
    }
};
