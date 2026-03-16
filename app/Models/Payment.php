<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'amount',
        'currency',
        'status', //pending, completed, failed, refunded, canceled
        'payment_method', //credit_card, pix, boleto, manual
        'payment_gateway', //stripe, paypal, gerencianet, mercado_pago
        'payment_date',
        'due-date',
        'manual_receipt_url',
        'approved_by', // Quem aprovou o pagamento manual
        'notes',
        'gateway_id',
        'gateway_response',
        'installments',
        'card_last_digits',
        'payer_name'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
