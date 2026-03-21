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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description');
            $table->decimal('price', 10, 2);
            $table->decimal('price_promotional', 10, 2)->nullable();
            $table->decimal('cost', 10, 2)->nullable();
            $table->decimal('profit', 10, 2)->nullable();
            $table->unsignedBigInteger('partner_id')->nullable();
            $table->unsignedBigInteger('brand_id')->nullable();
            $table->unsignedBigInteger('category_id')->nullable();
            $table->enum('gender', ['feminine', 'masculine'])->nullable();
            $table->integer('stock')->nullable();
            $table->string('image_main')->nullable();
            $table->string('color')->nullable();
            $table->string('old_price')->nullable();   
            $table->string('tags')->nullable();     

            $table->foreign('partner_id')->references('id')->on('partners')->onDelete('cascade');
            $table->foreign('brand_id')->references('id')->on('brands')->onDelete('cascade');
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
