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
        Schema::create('fipe_by_model_and_years', function (Blueprint $table) {
            $table->id();
            $table->string('vehicle_type')->nullable();
            $table->string('price_fipe')->nullable();
            $table->string('brand_name')->nullable();
            $table->unsignedBigInteger('brand_id');
            $table->string('model_name')->nullable();
            $table->string('model_year')->nullable();
            $table->string('fuel')->nullable();
            $table->string('codigo_fipe')->nullable();
            $table->string('reference_month')->nullable();
            $table->string('fuel_acronym')->nullable();

            $table->foreign('brand_id')->references('id')->on('brands')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fipe_by_model_and_years');
    }
};
