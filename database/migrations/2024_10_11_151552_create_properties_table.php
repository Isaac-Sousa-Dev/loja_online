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
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->string('reindeer')->nullable();
            $table->string('chassis')->nullable();
            $table->string('engine')->nullable();
            $table->string('year_of_manufacture')->nullable();
            $table->string('fuel')->nullable();
            $table->string('license_plate')->nullable();
            $table->string('miliage')->nullable();
            $table->string('exchange')->nullable();
            $table->string('bodywork')->nullable();
            $table->string('accept_exchange')->nullable();
            $table->string('review_done')->nullable();
            $table->string('color')->nullable();

            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('properties');
    }
};
