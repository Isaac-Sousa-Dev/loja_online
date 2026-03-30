<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Variante escolhida
            $table->foreignId('product_variant_id')->nullable()->after('product_id')->constrained('product_variants')->nullOnDelete();
            $table->string('selected_color')->nullable()->after('product_variant_id');
            $table->string('selected_size')->nullable()->after('selected_color');

            // Forma de pagamento escolhida
            $table->string('payment_method')->nullable()->after('selected_size'); // pix | credit_card | cash

            // Entrega
            $table->enum('delivery_type', ['pickup', 'delivery'])->default('pickup')->after('payment_method');
            $table->string('delivery_address')->nullable()->after('delivery_type');
            $table->string('delivery_city')->nullable()->after('delivery_address');
            $table->string('delivery_state')->nullable()->after('delivery_city');
            $table->string('delivery_zip')->nullable()->after('delivery_state');
            $table->string('delivery_complement')->nullable()->after('delivery_zip');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['product_variant_id']);
            $table->dropColumn([
                'product_variant_id', 'selected_color', 'selected_size',
                'payment_method', 'delivery_type',
                'delivery_address', 'delivery_city', 'delivery_state',
                'delivery_zip', 'delivery_complement',
            ]);
        });
    }
};
