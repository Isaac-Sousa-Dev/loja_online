<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('stores', function (Blueprint $table): void {
            $table->string('wholesale_count_mode')
                ->default('product')
                ->after('wholesale_min_quantity');
        });

        Schema::create('store_wholesale_levels', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('store_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('position');
            $table->string('label');
            $table->unsignedInteger('min_quantity');
            $table->timestamps();

            $table->unique(['store_id', 'position']);
            $table->unique(['store_id', 'min_quantity']);
        });

        Schema::table('order_items', function (Blueprint $table): void {
            $table->foreignId('store_wholesale_level_id')
                ->nullable()
                ->after('product_variant_id')
                ->constrained('store_wholesale_levels')
                ->nullOnDelete();
            $table->string('wholesale_applied_mode')
                ->nullable()
                ->after('store_wholesale_level_id');
        });
    }

    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table): void {
            $table->dropConstrainedForeignId('store_wholesale_level_id');
            $table->dropColumn('wholesale_applied_mode');
        });

        Schema::dropIfExists('store_wholesale_levels');

        Schema::table('stores', function (Blueprint $table): void {
            $table->dropColumn('wholesale_count_mode');
        });
    }
};
