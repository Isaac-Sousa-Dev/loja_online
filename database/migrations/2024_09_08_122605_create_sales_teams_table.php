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
        Schema::create('sales_teams', function (Blueprint $table) {
            $table->id();
            $table->string('phone');
            $table->string('status')->default('active');
            $table->text('initial_message')->nullable();
            $table->string('address');
            $table->string('city');
            $table->string('zip_code');
            $table->string('neighborhood');
            $table->string('number');

            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('partner_id');

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade'); 
            $table->foreign('partner_id')->references('id')->on('partners')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales_teams');
    }
};
