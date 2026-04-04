<?php

declare(strict_types=1);

namespace App\Support\Cache;

use DateTimeInterface;

/**
 * Chaves estáveis do painel (dashboard, produtos, vendas) — separadas por loja/parceiro.
 */
final class PanelCacheKeys
{
    public static function dashboardMetrics(int $storeId, ?DateTimeInterface $at = null): string
    {
        $at ??= now();

        return 'panel:dashboard:store:'.$storeId.':metrics:'.$at->format('Ym');
    }

    public static function productFilterMeta(int $partnerId): string
    {
        return 'panel:products:filter_meta:p:'.$partnerId;
    }

    public static function salesDashboard(int $storeId, string $filterHash): string
    {
        return 'panel:sales:store:'.$storeId.':dash:'.$filterHash;
    }
}
