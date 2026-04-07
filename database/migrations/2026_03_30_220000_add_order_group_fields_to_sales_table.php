<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->string('order_ref')->nullable()->after('store_id');
            $table->decimal('subtotal', 12, 2)->nullable()->after('total_amount');
            $table->decimal('shipping_amount', 12, 2)->default(0)->after('subtotal');
            $table->unsignedInteger('items_count')->default(0)->after('shipping_amount');
            $table->text('items_summary')->nullable()->after('items_count');
            $table->string('sale_status', 32)->nullable()->after('items_summary')->index();
        });

        Schema::table('sales', function (Blueprint $table) {
            $table->unique(['store_id', 'order_ref']);
        });
    }

    public function down(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->dropUnique(['store_id', 'order_ref']);
        });

        Schema::table('sales', function (Blueprint $table) {
            $table->dropColumn([
                'order_ref',
                'subtotal',
                'shipping_amount',
                'items_count',
                'items_summary',
                'sale_status',
            ]);
        });
    }
};
