<?php

declare(strict_types=1);

namespace App\Actions\SysAdmin;

use App\Enums\OrderStatus;
use App\Models\Partner;
use Illuminate\Support\Carbon;

final class GetPartnerDrawerDataAction
{
    /**
     * @return array<string, mixed>
     */
    public function execute(Partner $partner): array
    {
        $partner->loadMissing([
            'user',
            'store.plan',
            'store.addressStore',
            'subscription.plan',
            'salesTeamMembers.user',
        ]);

        $store = $partner->store;
        $owner = $partner->user;
        $subscription = $partner->subscription;

        $start = Carbon::now()->locale(app()->getLocale())->startOfMonth();
        $end = Carbon::now()->locale(app()->getLocale())->endOfMonth();

        $monthlyOrdersTotal = 0.0;
        $monthlyOrdersCount = 0;
        $monthlyCompletedTotal = 0.0;

        if ($store !== null) {
            $baseQuery = $store->orders()
                ->whereBetween('created_at', [$start, $end]);

            $monthlyOrdersCount = (int) (clone $baseQuery)->count();
            $monthlyOrdersTotal = (float) (clone $baseQuery)
                ->where('status', '!=', OrderStatus::CANCELLED->value)
                ->sum('total');

            $monthlyCompletedTotal = (float) (clone $baseQuery)
                ->where('status', OrderStatus::COMPLETED->value)
                ->sum('total');
        }

        $linkedUsers = [];

        if ($owner !== null) {
            $linkedUsers[] = [
                'id' => $owner->id,
                'name' => $owner->name,
                'email' => $owner->email,
                'phone' => $owner->phone,
                'role' => 'partner',
                'role_label' => 'Parceiro (titular)',
            ];
        }

        foreach ($partner->salesTeamMembers as $member) {
            $u = $member->user;
            if ($u === null) {
                continue;
            }
            $linkedUsers[] = [
                'id' => $u->id,
                'name' => $u->name,
                'email' => $u->email,
                'phone' => $u->phone,
                'role' => $u->role,
                'role_label' => $u->role === 'seller' ? 'Consultor(a) / vendedor' : ($u->role ?? 'Usuário'),
            ];
        }

        return [
            'partner' => [
                'id' => $partner->id,
                'partner_link' => $partner->partner_link,
                'is_testing' => (bool) $partner->is_testing,
            ],
            'store' => $store === null ? null : [
                'id' => $store->id,
                'store_name' => $store->store_name,
                'store_email' => $store->store_email,
                'store_phone' => $store->store_phone,
                'store_cpf_cnpj' => $store->store_cpf_cnpj,
                'configured_store' => (bool) $store->configured_store,
                'suspended_at' => $store->suspended_at?->toIso8601String(),
                'plan' => $store->plan ? [
                    'name' => $store->plan->name,
                    'price' => (float) $store->plan->price,
                ] : null,
                'address' => $store->addressStore ? [
                    'street' => $store->addressStore->street ?? null,
                    'city' => $store->addressStore->city ?? null,
                    'neighborhood' => $store->addressStore->neighborhood ?? null,
                    'zip_code' => $store->addressStore->zip_code ?? null,
                    'number' => $store->addressStore->number ?? null,
                    'state' => $store->addressStore->state ?? null,
                ] : null,
            ],
            'subscription' => $subscription === null ? null : [
                'status' => $subscription->status,
                'start_date' => $subscription->start_date !== null ? (string) $subscription->start_date : null,
                'end_date' => $subscription->end_date !== null ? (string) $subscription->end_date : null,
                'payment_method' => $subscription->payment_method,
                'plan' => $subscription->plan ? [
                    'name' => $subscription->plan->name,
                    'price' => (float) $subscription->plan->price,
                ] : null,
            ],
            'monthly' => [
                'period_label' => $start->isoFormat('MMMM [de] YYYY'),
                'orders_count' => $monthlyOrdersCount,
                'revenue_ex_cancelled' => $monthlyOrdersTotal,
                'revenue_completed_only' => $monthlyCompletedTotal,
            ],
            'linked_users' => $linkedUsers,
        ];
    }
}
