<?php

declare(strict_types=1);

namespace App\Providers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Partner;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Store;
use App\Observers\AuditObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{

    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Product::observe(AuditObserver::class);
        ProductVariant::observe(AuditObserver::class);
        Category::observe(AuditObserver::class);
        Brand::observe(AuditObserver::class);
        Partner::observe(AuditObserver::class);
        Store::observe(AuditObserver::class);
    }
}
