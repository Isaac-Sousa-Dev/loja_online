<?php

namespace App\Http\Controllers;

use MercadoPago\Client\PreApprovalPlan\PreApprovalPlanClient;
use MercadoPago\MercadoPagoConfig;
use MercadoPago\Exceptions\MPApiException;

class SubscriptionTestController extends Controller
{
    public function createPlan()
    {
        try {
            // Configura o Access Token
            MercadoPagoConfig::setAccessToken(config('services.mercadopago.access_token'));

            // Cliente para PreApprovalPlan
            $client = new PreApprovalPlanClient();

            // Parâmetros do plano (ajuste conforme sua necessidade)
            $params = [
                "reason" => "Assinatura de Teste - Plano Básico",
                "auto_recurring" => [
                    "frequency" => 1,
                    "frequency_type" => "months",
                    "repetitions" => 12, // Número de cobranças
                    "billing_day" => 10, // Dia da cobrança
                    "billing_day_proportional" => false,
                    "free_trial" => [
                        "frequency" => 1,
                        "frequency_type" => "months"
                    ],
                    "transaction_amount" => 9.90,
                    "currency_id" => "BRL"
                ],
                "payment_methods_allowed" => [
                    "payment_types" => [["id" => "credit_card"]],
                    "payment_methods" => [["id" => "bolbradesco"]]
                ],
                "back_url" => "https://automotiv.com.br"
            ];

            // Cria o plano
            $plan = $client->create($params);

            // Retorna a resposta (ou redireciona para a URL de pagamento)
            return response()->json($plan);

        } catch (MPApiException $e) {
            // Log do erro detalhado
            // \Log::error('Erro ao criar plano: ' . $e->getApiResponse()->getContent());
            return response()->json([
                'error' => $e->getMessage(),
                'details' => $e->getApiResponse()->getContent()
            ], 500);
        }
    }
}