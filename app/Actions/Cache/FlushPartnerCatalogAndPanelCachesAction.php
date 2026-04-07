<?php

declare(strict_types=1);

namespace App\Actions\Cache;

use App\Actions\Catalog\FlushStoreCatalogCacheAction;
use App\Models\Partner;
use App\Support\Cache\PanelCacheKeys;
use Illuminate\Support\Facades\Cache;

/**
 * Após mudança em sortimento (produto, categoria, marca) ou vitrine da loja:
 * invalida catálogo público (version bump), métricas do dashboard e metadados de filtros de produtos.
 */
final class FlushPartnerCatalogAndPanelCachesAction
{
    public function __construct(
        private readonly FlushStoreCatalogCacheAction $flushStoreCatalogCache,
        private readonly ForgetStoreDashboardMetricsAction $forgetStoreDashboardMetrics,
    ) {}

    public function execute(Partner $partner): void
    {
        if ($partner->store !== null) {
            $storeId = (int) $partner->store->id;
            $this->flushStoreCatalogCache->execute($storeId);
            $this->forgetStoreDashboardMetrics->execute($storeId);
        }

        Cache::forget(PanelCacheKeys::productFilterMeta((int) $partner->id));
    }
}
