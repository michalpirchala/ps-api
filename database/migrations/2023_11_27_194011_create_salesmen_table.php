<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('salesmen', function (Blueprint $table) {
            $table->uuid()->primary()->default(DB::Raw("uuid_generate_v4()"));
            $table->string('first_name');
            $table->string('last_name');
            $table->integer("prosight_id")->unique();
            $table->string('email');
            $table->string('phone');
            $table->foreignId('gender_id')->references('id')->on('genders');
            $table->foreignId('marital_status_id')->nullable()->references('id')->on('marital_statuses');
            $table->timestamps(3);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('salesmen');
    }
};
