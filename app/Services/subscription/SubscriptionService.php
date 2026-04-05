<?php

declare(strict_types=1);

namespace App\Services\subscription;

use App\Interfaces\AbstractServiceInterface;
use App\Models\Subscription;
use App\Services\payment\PaymentService;
use Illuminate\Support\Carbon;

class SubscriptionService implements AbstractServiceInterface
{
    protected $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    public function insert(array $data, $request = null, $isPayment = true)
    {
        $startDate = isset($data['start_date'])
            ? Carbon::parse((string) $data['start_date'])->toDateString()
            : now()->toDateString();

        $status = $data['subscription_status'] ?? 'active';

        $data['subscription_id'] = Subscription::create([
            'partner_id' => $data['partner_id'],
            'plan_id' => $data['plan_id'],
            'status' => $status,
            'start_date' => $startDate,
            'end_date' => null,
            'payment_method' => $data['payment_method'] ?? null,
            'appellant' => $data['appellant'] ?? null,
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