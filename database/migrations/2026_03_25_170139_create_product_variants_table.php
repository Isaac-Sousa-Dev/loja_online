<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->string('color')->nullable();       // ex: "Vermelho", "Azul"
            $table->string('color_hex')->nullable();   // ex: "#FF0000"
            $table->string('size')->nullable();        // ex: "P", "M", "G", "42"
            $table->unsignedInteger('stock')->default(0);
            $table->decimal('price_override', 10, 2)->nullable(); // preço diferente do produto base
            $table->boolean('active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_variants');
    }
};
