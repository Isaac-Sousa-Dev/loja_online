<?php

declare(strict_types=1);

namespace App\Actions\Cache;

use App\Support\Cache\PanelCacheKeys;
use Illuminate\Support\Facades\Cache;

final class ForgetStoreDashboardMetricsAction
{
    public function execute(int $storeId): void
    {
        if ($storeId <= 0) {
            return;
        }

        Cache::forget(PanelCacheKeys::dashboardMetrics($storeId));
    }
}
