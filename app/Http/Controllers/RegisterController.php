<?php

namespace App\Http\Controllers;

use App\Providers\RouteServiceProvider;
use App\Services\partner\PartnerService;
use App\Services\store\StoreService;
use App\Services\subscription\SubscriptionService;
use App\Services\user\UserService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RegisterController extends Controller
{
    protected $userService;
    protected $partnerService;
    protected $storeService;
    protected $subscriptionService;

    public function __construct(
        UserService $userService,
        PartnerService $partnerService,
        StoreService $storeService,
        SubscriptionService $subscriptionService
    )
    {
        $this->userService = $userService;
        $this->partnerService = $partnerService;
        $this->storeService = $storeService;
        $this->subscriptionService = $subscriptionService;
    }

    public function register(Request $request)
    {
        $data = $request->all();

        try {
            DB::beginTransaction();

            $user = $this->userService->userRegistration($data, $request);
            $data['user_id'] = $user->id;
            $data['is_testing'] = false;
            $partner = $this->partnerService->insert($data, $request);
            $data['partner_id'] = $partner->id;
            $this->storeService->insert($data, $request);

            $data['plan_id'] = 1;
            $this->subscriptionService->insert($data);
            
            DB::commit();
            Auth::login($user);

            // return redirect(RouteServiceProvider::HOME);
            return response()->json(['success' => true, 'user' => $user, 'partner' => $partner], 201);

        } catch(Exception $e) {
            DB::rollBack();
            return response()->json(['erro' => $e->getMessage()], 400);
        }
    }

    public function checkEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $exists = \App\Models\User::where('email', $request->email)->exists();

        return response()->json(['exists' => $exists]);
    }

}
