<?php

namespace App\Http\Controllers;

use App\Mail\SendVerificationCodeMail;
use App\Services\user\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Models\RequestPlan;
use App\Services\partner\PartnerService;
use App\Services\store\StoreService;
use App\Services\subscription\SubscriptionService;
use Illuminate\Support\Facades\DB; // Adicione esta linha

class UserController extends Controller
{
    protected $userService;
    protected $subscriptionService;
    protected $partnerService;
    protected $storeService;

    public function __construct
    (
        UserService $userService,
        SubscriptionService $subscriptionService,
        PartnerService $partnerService,
        StoreService $storeService
    )
    {
        $this->userService = $userService;
        $this->subscriptionService = $subscriptionService;
        $this->partnerService = $partnerService;
        $this->storeService = $storeService;
    }

    public function newSubscribeUser(Request $request)
    {
        DB::beginTransaction();
        try {
            $data = $request->all();
            $user = $this->userService->userRegistration($data);

            if($request->hasFile('manual_receipt')): $manualReceiptUrl = $request->file('manual_receipt')->store('manual_receipts', 'public'); endif;
            
            $data['user_id'] = $user->id;
            $data['manual_receipt_url'] = $manualReceiptUrl ?? null;
            
            $data['partner_id'] = $this->partnerService->insert($data, $request)->id;
            $this->storeService->insert($data, $request);
            $this->subscriptionService->insert($data);
            $this->removeRequestPlan($data['requestPlanId']);

            DB::commit();
            
            $data['user'] = $user;
            Mail::to($data['email'])->send(new SendVerificationCodeMail($data));
            
            session()->flash('success', 'Novo motivado na área!');
        } catch(\Exception $e) {
            DB::rollBack();
            
            session()->flash('error', 'Ocorreu um erro ao processar seu cadastro. Por favor, tente novamente.');
            throw $e;
        }
    }

    private function removeRequestPlan($requestPlanId)
    {
        $requestPlan = RequestPlan::find($requestPlanId);
        if ($requestPlan) {
            $requestPlan->delete();
        }
        
    }
}
