<?php

declare(strict_types=1);

namespace App\Support\Catalog;

use Illuminate\Support\Facades\Cache;

/**
 * Version counter per store: bumping invalidates all catalog keys that embed the current version.
 */
final class CatalogStoreCacheVersion
{
    private const PREFIX = 'catalog:store:';

    public function current(int $storeId): int
    {
        return (int) Cache::get($this->key($storeId), 0);
    }

    public function bump(int $storeId): int
    {
        $key = $this->key($storeId);
        if (! Cache::has($key)) {
            Cache::put($key, 1, now()->addYears(5));

            return 1;
        }

        return (int) Cache::increment($key);
    }

    private function key(int $storeId): string
    {
        return self::PREFIX.$storeId.':version';
    }
}
