<?php

declare(strict_types=1);

namespace App\Actions\Dashboard;

use App\Models\Plan;
use App\Models\RequestPlan;
use App\Models\Store;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

final class GetSysAdminDashboardDataAction
{
    private const STORAGE_QUOTA_BYTES = 1_073_741_824;

    private const LATEST_SOLICITATIONS_LIMIT = 5;

    private const LATEST_STORES_LIMIT = 5;

    /**
     * @return array<string, mixed>
     */
    public function execute(): array
    {
        $storesCount = Store::query()->count();
        $solicitationsCount = RequestPlan::query()->count();
        $monthlyRevenue = $this->monthlyRevenueFromActiveStores();

        $storageUsedBytes = $this->estimatePublicStorageBytes();
        $storagePercent = self::STORAGE_QUOTA_BYTES > 0
            ? (int) min(100, round(($storageUsedBytes / self::STORAGE_QUOTA_BYTES) * 100))
            : 0;

        $latestRequestPlans = $this->latestRequestPlansDecorated();
        $latestPartnerUsers = User::query()
            ->where('role', 'partner')
            ->with(['partner.store', 'partner.subscription.plan'])
            ->orderByDesc('created_at')
            ->limit(self::LATEST_STORES_LIMIT)
            ->get();

        return [
            'storesCount' => $storesCount,
            'storagePercent' => $storagePercent,
            'storageUsedHuman' => $this->formatBytes($storageUsedBytes),
            'solicitationsCount' => $solicitationsCount,
            'monthlyRevenue' => $monthlyRevenue,
            'latestRequestPlans' => $latestRequestPlans,
            'latestPartnerUsers' => $latestPartnerUsers,
        ];
    }

    /**
     * @return Collection<int, RequestPlan>
     */
    private function latestRequestPlansDecorated(): Collection
    {
        $requests = RequestPlan::query()
            ->orderByDesc('created_at')
            ->limit(self::LATEST_SOLICITATIONS_LIMIT)
            ->get();

        if ($requests->isEmpty()) {
            return $requests;
        }

        $slugs = $requests->pluck('plan_slug')->filter()->unique()->values();
        $plansBySlug = Plan::query()
            ->whereIn('slug', $slugs)
            ->get()
            ->keyBy('slug');

        foreach ($requests as $requestPlan) {
            $slug = $requestPlan->plan_slug;
            $plan = $slug !== null ? $plansBySlug->get($slug) : null;
            $requestPlan->plan_price = $plan?->price ?? '—';
            $requestPlan->plan_name = $plan?->name ?? config('vistoo_plans.'.$slug.'.name', $slug ?? '—');
        }

        return $requests;
    }

    /**
     * Soma o preço mensal dos planos vinculados às lojas cuja assinatura está ativa.
     */
    private function monthlyRevenueFromActiveStores(): string
    {
        $sum = Store::query()
            ->whereHas('partner.subscription', static function ($query): void {
                $query->where('status', 'active');
            })
            ->whereNotNull('stores.plan_id')
            ->join('plans', 'plans.id', '=', 'stores.plan_id')
            ->sum(DB::raw('plans.price'));

        return (string) $sum;
    }

    private function estimatePublicStorageBytes(): int
    {
        $path = storage_path('app/public');
        if (! is_dir($path)) {
            return 0;
        }

        $total = 0;
        try {
            foreach (File::allFiles($path) as $file) {
                $total += $file->getSize();
            }
        } catch (\Throwable) {
            return 0;
        }

        return $total;
    }

    private function formatBytes(int $bytes): string
    {
        if ($bytes < 1024) {
            return $bytes.' B';
        }

        $units = ['KB', 'MB', 'GB', 'TB'];
        $value = $bytes / 1024;
        $unitIndex = 0;
        while ($value >= 1024 && $unitIndex < count($units) - 1) {
            $value /= 1024;
            ++$unitIndex;
        }

        return number_format($value, $unitIndex === 0 ? 0 : 1, ',', '.').' '.$units[$unitIndex];
    }
}
