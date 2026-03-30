<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Referência de pedido — agrupa múltiplos itens do mesmo carrinho
            $table->string('order_ref')->nullable()->after('status')->index();
            // Quantidade do produto neste item
            $table->unsignedInteger('quantity')->default(1)->after('order_ref');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['order_ref', 'quantity']);
        });
    }
};
