<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class CelcoinController extends Controller
{
    private $accessToken;

    public function __construct()
    {
        
    }

    public function authenticate()
    {
        if(Cache::has('celcoin_access_token')){
            $this->accessToken = Cache::get('celcoin_access_token');
            return;
        }

        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/x-www-form-urlencoded',
            ])->post(env('CELCOIN_API_URL').'/v5/token', [
                'client_id' => env('CELCOIN_CLIENT_ID'),
                'client_secret' => env('CELCOIN_CLIENT_SECRET'),
                'grant_type' => 'client_credentials'
            ]);

            if($response->failed()) {
                throw new \Exception('Celcoin API error: '.$response->body());
            }
    
            $this->accessToken = $response->json()['access_token'];
    
            Cache::put('celcoin_access_token', $this->accessToken, now()->addMinutes(50));
        } catch(\Exception $e) {
            throw $e;
        }

        
    }

    public function createPix(Request $request) 
    {
        $response = Http::withToken($this->accessToken)
            ->post(env('CELCOIN_API_URL').'/pix/v1/charge/qrcode', [
                'amount' => $request->amount,
                'merchant' => [
                    'name' => config('app.name'),
                    'city' => 'SAO PAULO',
                    'postal_code' => '00000000'
                ],
                'calendar' => [
                    'expiration' => 3600
                ]
            ]);
        
        return $response->json();
    }

    public function getPixStatus($transactionId)
    {
        $response = Http::withToken($this->accessToken)
            ->get(env('CELCOIN_API_URL')."/pix/v1/charge/{$transactionId}");

        return $response->json();
    }

    public function webhook(Request $request)
    {
        // Validação de assinatura
        $signature = $request->header('X-Signature');
        $payload = $request->getContent();

        if(!$this->validateSignature($signature, $payload)) {
            return response()->json(['error' => 'Invalid signature'], 403);
        }

        $event = $request->json()->all();

        // Processar eventos
        switch ($event['type']) {
            case 'pix.received':
                // Atualizar status de pagamento
                break;
            case 'pix.refunded':
                // Processar reembolso
                break;

        }

        return response()->json(['status' => 'success']);
    }

    private function validateSignature($signature, $payload) 
    {
        $secret = env('CELCOIN_WEBHOOK_SECRET');
        $computedSignature = hash_hmac('sha256', $payload, $secret);

        return hash_equals($signature, $computedSignature);
    }
}
