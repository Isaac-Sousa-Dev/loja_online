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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->decimal('amount', 10, 2);
            $table->string('currency')->default('BRL');
            $table->enum('status', ['pending', 'completed', 'failed', 'refunded', 'canceled'])->default('pending');
            $table->string('payment_method'); // credit_card, pix, boleto, manual
            $table->string('payment_gateway')->nullable(); // stripe, paypal, gerencianet, mercado_pago
            $table->timestamp('payment_date')->nullable();
            $table->date('due_date')->nullable();
            $table->string('manual_receipt_url')->nullable();
            $table->string('approved_by'); // Quem aprovou o pagamento manual
            $table->text('notes')->nullable();
            $table->string('gateway_id')->nullable(); // ID do pagamento no gateway
            $table->text('gateway_response')->nullable(); // Resposta do gateway
            $table->integer('installments')->nullable(); // Número de parcelas
            $table->string('card_last_digits')->nullable(); // Últimos dígitos do cartão
            $table->string('payer_name')->nullable(); // Nome do pagador
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
