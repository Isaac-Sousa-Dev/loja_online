<?php

namespace App\Http\Controllers;

use App\Mail\SendVerificationCodeMail;
use App\Models\Partner;
use App\Models\User;
use App\Services\partner\PartnerService;
use App\Services\store\StoreService;
use App\Services\subscription\SubscriptionService;
use App\Services\user\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class PartnerController extends Controller
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
   
    public function index()
    {
        $users = User::where('role', 'partner')->with('partner')->get();
        return view('admin.partners.index', ['users' => $users]);
    }


    public function create()
    {
        return view('admin.partners.create');
    }
   
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $data = $request->all();
            $data['phone'] = str_replace(['(', ')', '-', ' '], '', $data['phone']);
            $user = $this->userService->userRegistration($data);

            if($request->hasFile('manual_receipt')): $manualReceiptUrl = $request->file('manual_receipt')->store('manual_receipts', 'public'); endif;
            
            $data['user_id'] = $user->id;
            $data['manual_receipt_url'] = $manualReceiptUrl ?? null;
            
            $data['partner_id'] = $this->partnerService->insert($data, $request)->id;
            $this->storeService->insert($data, $request);

            $data['start_date'] = date('Y-m-d');
            $data['appellant'] = false;
            $this->subscriptionService->insert($data, null, false);
            
            $data['user'] = $user;
            Mail::to($data['email'])->send(new SendVerificationCodeMail($data));

            DB::commit();
            
            session()->flash('success', 'Novo motivado na área!');
        } catch(\Exception $e) {
            DB::rollBack();
            
            session()->flash('error', 'Ocorreu um erro ao processar seu cadastro. Por favor, tente novamente.');
            throw $e;
        }

    }


    public function edit(string $id)
    {
        $user = User::find($id);
        return view('admin.partners.edit', ['user' => $user]);
    }


    public function update(Request $request, string $id)
    {
        $user = User::find($id);
        $data = $request->all();

        $user->update([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => $data['role']
        ]);

        $partner = Partner::where('user_id', $id)->first();

        $partner->update([
            'phone' => $data['phone'],
            'status' => $data['status'],
            'address' => $data['address'],
            'city' => $data['city'],
            'zip_code' => $data['zip_code'],
            'neighborhood' => $data['neighborhood'],
            'number' => $data['number']
        ]);

        return redirect()->route('partners.index')->with('success', 'Sócio atualizado com sucesso!');;

    }


    public function destroy(string $id)
    {
        $user = User::find($id);
        $partner = Partner::where('user_id', $id)->first();

        $partner->delete();
        $user->delete();

        return redirect()->route('partners.index')->with('success', 'Sócio deletado com sucesso!');
    }
}
