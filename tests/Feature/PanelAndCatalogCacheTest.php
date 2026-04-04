<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Actions\Cache\FlushPartnerCatalogAndPanelCachesAction;
use App\Actions\Catalog\FlushStoreCatalogCacheAction;
use App\Models\Partner;
use App\Models\Plan;
use App\Models\Store;
use App\Models\User;
use App\Support\Cache\PanelCacheKeys;
use App\Support\Catalog\CatalogStoreCacheVersion;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class PanelAndCatalogCacheTest extends TestCase
{
    use RefreshDatabase;

    public function test_catalog_version_bump_is_isolated_per_store(): void
    {
        $version = app(CatalogStoreCacheVersion::class);
        $flush = app(FlushStoreCatalogCacheAction::class);

        $flush->execute(10);
        $flush->execute(10);
        $flush->execute(20);

        $this->assertSame(2, $version->current(10));
        $this->assertSame(1, $version->current(20));
        $this->assertSame(0, $version->current(99));
    }

    public function test_flush_partner_clears_product_filter_meta_and_bumps_catalog(): void
    {
        $user = User::factory()->create();
        $partner = Partner::query()->create([
            'user_id' => $user->id,
            'partner_link' => 'loja-a',
        ]);
        $plan = Plan::query()->create([
            'name' => 'Free',
            'slug' => 'free',
            'price' => 0,
            'duration' => 30,
            'status' => 'active',
            'type' => 'monthly',
        ]);
        $store = Store::query()->create([
            'partner_id' => $partner->id,
            'plan_id' => $plan->id,
            'store_name' => 'Loja A',
            'wholesale_min_quantity' => 1,
        ]);
        $partner->setRelation('store', $store);

        $metaKey = PanelCacheKeys::productFilterMeta((int) $partner->id);
        Cache::put($metaKey, ['stub' => true], 600);
        $this->assertTrue(Cache::has($metaKey));

        $version = app(CatalogStoreCacheVersion::class);
        $before = $version->current((int) $store->id);

        app(FlushPartnerCatalogAndPanelCachesAction::class)->execute($partner);

        $this->assertFalse(Cache::has($metaKey));
        $this->assertSame($before + 1, $version->current((int) $store->id));
    }

    public function test_dashboard_metrics_cache_key_is_per_store_and_month(): void
    {
        $k1 = PanelCacheKeys::dashboardMetrics(5);
        $k2 = PanelCacheKeys::dashboardMetrics(6);

        $this->assertNotSame($k1, $k2);
        $this->assertStringContainsString(':store:5:metrics:', $k1);
    }
}
