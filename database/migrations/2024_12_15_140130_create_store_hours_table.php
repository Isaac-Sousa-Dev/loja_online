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
        Schema::create('store_hours', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('store_id');
            $table->time('open_in_weekdays')->nullable();
            $table->time('close_in_weekdays')->nullable();
            $table->time('open_saturday')->nullable();
            $table->time('close_saturday')->nullable();
            $table->time('open_sunday')->nullable();
            $table->time('close_sunday')->nullable();

            $table->foreign('store_id')->references('id')->on('stores')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('store_hours');
    }
};
