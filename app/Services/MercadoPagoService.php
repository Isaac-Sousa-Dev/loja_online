<?php
namespace App\Services;

use MercadoPago\MercadoPagoConfig;
use MercadoPago\Client\Payment\PaymentClient;
use MercadoPago\Payer;

class MercadoPagoService
{
    public function __construct()
    {
        // Configura o Access Token
        MercadoPagoConfig::setAccessToken(config('services.mercadopago.access_token'));
    }

    public function criarPagamentoPix(array $data)
    {
        $payment = new PaymentClient();
        $payment->transaction_amount = $data['amount'];
        $payment->description        = $data['description'];
        $payment->payment_method_id = 'pix';

        $payer = new Payer();
        $payer->email = $data['payer_email'];
        $payment->payer = $payer;

        $payment->save();

        return [
            'id'       => $payment->id,
            'qr_code'  => $payment->point_of_interaction->transaction_data->qr_code,
            'qr_code_base64' => $payment->point_of_interaction->transaction_data->qr_code_base64,
        ];
    }

    public function criarPagamentoCartao(array $data)
    {
        $payment = new Payment();
        $payment->transaction_amount = $data['amount'];
        $payment->token              = $data['token'];           // gerado pelo JS SDK
        $payment->description        = $data['description'];
        $payment->installments       = $data['installments'];    // número de parcelas
        $payment->payment_method_id  = $data['payment_method_id'];
        $payment->payer = [
            'email' => $data['payer_email'],
            'identification' => [
                'type'   => $data['doc_type'],    // ex: 'CPF'
                'number' => $data['doc_number'],
            ],
        ];

        $payment->save();

        return $payment->status;
    }
}
