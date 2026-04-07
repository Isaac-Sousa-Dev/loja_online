<?php

declare(strict_types=1);

namespace App\Actions\Catalog;

use App\Support\Catalog\CatalogStoreCacheVersion;

final class FlushStoreCatalogCacheAction
{
    public function __construct(
        private readonly CatalogStoreCacheVersion $catalogStoreCacheVersion,
    ) {}

    public function execute(int $storeId): void
    {
        if ($storeId <= 0) {
            return;
        }
        $this->catalogStoreCacheVersion->bump($storeId);
    }
}
