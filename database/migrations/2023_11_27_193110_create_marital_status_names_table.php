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
        Schema::create('marital_status_names', function (Blueprint $table) {
            $table->id();
            $table->foreignId('marital_status_id')->references('id')->on('marital_statuses');
            $table->foreignId('gender_id')->nullable()->references('id')->on('genders');
            $table->string("name");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('marital_status_names');
    }
};
