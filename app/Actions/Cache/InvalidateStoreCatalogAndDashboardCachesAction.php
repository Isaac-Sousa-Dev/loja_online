<?php

declare(strict_types=1);

namespace App\Actions\Cache;

use App\Actions\Catalog\FlushStoreCatalogCacheAction;

/**
 * Quando estoque ou totais do painel mudam por pedido (ex.: conclusão com baixa de estoque).
 */
final class InvalidateStoreCatalogAndDashboardCachesAction
{
    public function __construct(
        private readonly FlushStoreCatalogCacheAction $flushStoreCatalogCache,
        private readonly ForgetStoreDashboardMetricsAction $forgetStoreDashboardMetrics,
    ) {}

    public function execute(int $storeId): void
    {
        if ($storeId <= 0) {
            return;
        }

        $this->flushStoreCatalogCache->execute($storeId);
        $this->forgetStoreDashboardMetrics->execute($storeId);
    }
}
