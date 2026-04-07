<?php 

namespace App\Services\payment;
use App\Interfaces\AbstractServiceInterface;
use App\Models\Payment;
use Illuminate\Support\Facades\Auth;

class PaymentService implements AbstractServiceInterface{
    public function insert($data, $request = null)
    {
        Payment::create([
            'user_id' => $data['user_id'],
            'subscription_id' => $data['subscription_id'],
            'amount' => $data['amount'],
            'currency' => 'BRL',
            'status' => 'completed',
            'payment_method' => $data['payment_method'],
            'payment_date' => now(),
            'due_date' => date('Y-m-d', strtotime($data['due_date'])),
            'manual_receipt_url' => $data['manual_receipt_url'] ?? null,    
            'approved_by' => Auth::user()->name,
            'notes' => $data['notes'] ?? 'N/I'
        ]);
    }

    public function update($data, $model)
    {}

    public function delete($model){}
    public function find($id){}
    public function findAll(){}
}