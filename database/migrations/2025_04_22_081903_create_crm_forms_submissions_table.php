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
        Schema::create('crm_form_submissions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string("crm_form_id");
            $table->json("crm_form_answers");
            $table->timestamps();

            $table->foreign('crm_form_id')->references('id')->on('crm_forms');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('crm_form_submissions');
    }
};
