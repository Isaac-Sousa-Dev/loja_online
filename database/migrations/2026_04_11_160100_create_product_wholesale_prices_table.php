<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_wholesale_prices', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->foreignId('store_wholesale_level_id')->constrained('store_wholesale_levels')->cascadeOnDelete();
            $table->decimal('price', 10, 2)->nullable();
            $table->timestamps();

            $table->unique(['product_id', 'store_wholesale_level_id'], 'product_wholesale_prices_unique_level');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_wholesale_prices');
    }
};
