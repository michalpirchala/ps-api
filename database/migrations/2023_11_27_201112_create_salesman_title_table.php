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
        Schema::create('salesman_title', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('salesman_id')->references('uuid')->on('salesmen');
            $table->foreignId('title_id')->references('id')->on('titles');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('salesman_title');
    }
};
