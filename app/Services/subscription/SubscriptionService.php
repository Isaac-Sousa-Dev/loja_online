<?php 

namespace App\Services\subscription;

use App\Interfaces\AbstractServiceInterface;
use App\Models\Subscription;
use App\Services\payment\PaymentService;

class SubscriptionService implements AbstractServiceInterface{

    protected $paymentService;
    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    public function insert(array $data, $request = null, $isPayment = true)
    {
        // $startDateFormatted = date('Y-m-d', strtotime($data['start_date']));
        $data['subscription_id'] = Subscription::create([
            'partner_id' => $data['partner_id'],
            'plan_id' => $data['plan_id'],
            'status' => 'active',
            'start_date' => now(),
            'end_date' => null,
            'payment_method' => $data['payment_method'] ?? null,
            'appellant' => $data['appellant'] ?? null
        ])->id;

        // CRIA UM PAGAMENTO PARA O USUÁRIO
        // if($isPayment){
        //     $this->paymentService->insert($data);
        // }
    }

    public function update($data, $model)
    {}

    public function delete($model){}
    public function find($id){}
    public function findAll(){}
}