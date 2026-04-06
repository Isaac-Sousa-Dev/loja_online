<?php

declare(strict_types=1);

namespace App\Actions\SysAdmin;

use App\Models\AddressStore;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

final class UpdatePartnerFromAdminAction
{
    /**
     * @param  array<string, mixed>  $validated
     */
    public function execute(User $user, array $validated): void
    {
        DB::transaction(function () use ($user, $validated): void {
            $partner = $user->partner;
            if ($partner === null) {
                return;
            }

            $userData = [
                'name' => $validated['name'],
                'email' => $validated['email'],
            ];

            $phone = isset($validated['phone']) ? trim((string) $validated['phone']) : '';
            $userData['phone'] = $phone !== '' ? $this->normalizePhone($phone) : null;

            if (! empty($validated['password'])) {
                $userData['password'] = Hash::make((string) $validated['password']);
                $userData['must_change_password'] = false;
            }

            $user->update($userData);

            $link = isset($validated['partner_link']) ? trim((string) $validated['partner_link']) : '';
            $partner->update([
                'partner_link' => $link !== '' ? $link : null,
                'is_testing' => (bool) ($validated['is_testing'] ?? false),
            ]);

            $store = $partner->store;
            if ($store === null) {
                return;
            }

            $planId = (int) $validated['plan_id'];
            $panelSuspended = (bool) ($validated['panel_suspended'] ?? false);
            $suspendedAt = null;
            if ($panelSuspended) {
                $suspendedAt = $store->suspended_at ?? now();
            }

            $store->update([
                'store_name' => $validated['store_name'],
                'store_email' => $validated['store_email'] ?? null,
                'store_phone' => $validated['store_phone'] ?? null,
                'store_cpf_cnpj' => $validated['store_cpf_cnpj'] ?? null,
                'qtd_products_in_stock' => $validated['qtd_products_in_stock'] ?? null,
                'plan_id' => $planId,
                'suspended_at' => $suspendedAt,
            ]);

            $subscription = $partner->subscription;
            if ($subscription !== null) {
                $subscription->update([
                    'plan_id' => $planId,
                    'status' => $validated['subscription_status'],
                    'start_date' => $validated['start_date'],
                    'end_date' => $validated['end_date'] ?? null,
                    'payment_method' => $validated['payment_method'] ?? null,
                ]);
            }

            $country = isset($validated['country']) && trim((string) $validated['country']) !== ''
                ? trim((string) $validated['country'])
                : 'Brasil';

            AddressStore::query()->updateOrCreate(
                ['store_id' => $store->id],
                [
                    'street' => $validated['street'] ?? null,
                    'city' => $validated['city'] ?? null,
                    'state' => $validated['state'] ?? null,
                    'neighborhood' => $validated['neighborhood'] ?? null,
                    'number' => $validated['number'] ?? null,
                    'zip_code' => $validated['zip_code'] ?? null,
                    'country' => $country,
                ]
            );
        });
    }

    private function normalizePhone(string $phone): string
    {
        $digits = preg_replace('/\D/', '', $phone);

        return $digits !== '' ? $digits : $phone;
    }
}
