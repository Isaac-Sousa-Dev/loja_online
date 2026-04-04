<?php

declare(strict_types=1);

namespace App\Actions\Dashboard;

use App\Enums\OrderStatus;
use App\Models\Store;
use App\Support\Cache\PanelCacheKeys;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Cache;

final class GetPartnerDashboardOrderMetricsAction
{
    private const DASHBOARD_ORDER_METRICS_TTL_SECONDS = 120;

    /**
     * Métricas de pedidos do mês civil atual (fuso da aplicação).
     *
     * @return array{completed_sales_count: int, new_orders_count: int, monthly_sales_total: string}
     */
    public function execute(Store $store): array
    {
        $storeId = (int) $store->id;
        $cacheKey = PanelCacheKeys::dashboardMetrics($storeId);

        return Cache::remember(
            $cacheKey,
            self::DASHBOARD_ORDER_METRICS_TTL_SECONDS,
            function () use ($store): array {
                $monthStart = Carbon::now()->startOfMonth();
                $monthEnd = Carbon::now()->endOfMonth();

                $completedBase = $store->orders()
                    ->where('status', OrderStatus::COMPLETED)
                    ->where(function (Builder $query) use ($monthStart, $monthEnd): void {
                        $query->whereBetween('completed_at', [$monthStart, $monthEnd])
                            ->orWhere(function (Builder $legacy) use ($monthStart, $monthEnd): void {
                                $legacy->whereNull('completed_at')
                                    ->whereBetween('updated_at', [$monthStart, $monthEnd]);
                            });
                    });

                $completedSalesCount = (int) $completedBase->clone()->count();
                $monthlyTotal = (string) $completedBase->clone()->sum('total');

                $newOrdersCount = (int) $store->orders()
                    ->where('status', OrderStatus::PENDING)
                    ->whereBetween('created_at', [$monthStart, $monthEnd])
                    ->count();

                return [
                    'completed_sales_count' => $completedSalesCount,
                    'new_orders_count' => $newOrdersCount,
                    'monthly_sales_total' => $monthlyTotal,
                ];
            },
        );
    }
}
