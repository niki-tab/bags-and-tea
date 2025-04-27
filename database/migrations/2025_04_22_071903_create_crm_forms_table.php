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
        Schema::create('crm_forms', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string("form_identifier");
            $table->string("form_name");
            $table->string("form_description")->nullable();
            $table->json("form_fields");
            $table->boolean("is_active")->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('crm_forms');
    }
};
