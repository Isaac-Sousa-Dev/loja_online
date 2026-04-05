<?php

declare(strict_types=1);

namespace App\Actions\SysAdmin;

use App\Http\Requests\SysAdmin\StoreManualPartnerRequest;
use App\Models\Plan;
use App\Models\User;
use App\Services\partner\PartnerService;
use App\Services\store\StoreService;
use App\Services\subscription\SubscriptionService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

final class RegisterManualPartnerAction
{
    public function __construct(
        private readonly PartnerService $partnerService,
        private readonly StoreService $storeService,
        private readonly SubscriptionService $subscriptionService,
    ) {
    }

    public function execute(StoreManualPartnerRequest $request): User
    {
        $validated = $request->validated();
        $phone = $this->normalizePhone($validated['phone']);

        return DB::transaction(function () use ($request, $validated, $phone): User {
            $verificationCode = (string) random_int(100000, 999999);

            $user = User::query()->create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $phone,
                'password' => Hash::make($validated['password']),
                'role' => 'partner',
                'verification_code' => $verificationCode,
                'email_verified_at' => $validated['grant_access'] ? now() : null,
                'first_login' => true,
            ]);

            $manualReceiptUrl = null;
            if ($request->hasFile('manual_receipt')) {
                $manualReceiptUrl = $request->file('manual_receipt')->store('manual_receipts', 'public');
            }

            $payload = [
                'user_id' => $user->id,
                'store_name' => $validated['store_name'],
                'plan_id' => (int) $validated['plan_id'],
                'store_email' => $user->email,
                'store_phone' => $phone,
                'store_cpf_cnpj' => $validated['store_cpf_cnpj'] ?? null,
                'qtd_vehicles_in_stock' => $validated['qtd_vehicles_in_stock'] ?? null,
                'is_testing' => $validated['is_testing'],
                'payment_method' => $validated['payment_method'] ?? null,
                'start_date' => $validated['start_date'],
                'manual_receipt_url' => $manualReceiptUrl,
                'appellant' => false,
            ];

            $partner = $this->partnerService->insert($payload, $request);
            $payload['partner_id'] = $partner->id;

            $this->storeService->insert($payload, $request);

            $payload['subscription_status'] = $validated['grant_access'] ? 'active' : 'pending';
            $this->subscriptionService->insert($payload, $request, false);

            return $user;
        });
    }

    private function normalizePhone(string $phone): string
    {
        $digits = preg_replace('/\D/', '', $phone);

        return $digits !== '' ? $digits : $phone;
    }
}
