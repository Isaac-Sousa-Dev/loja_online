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
use Dedoc\Scramble\Scramble;
use Dedoc\Scramble\Support\Generator\OpenApi;
use Dedoc\Scramble\Support\Generator\SecurityScheme;
use Illuminate\Database\Events\MigrationsStarted;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{

    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        $this->resetTelescopeSqliteOnFresh();

        Product::observe(AuditObserver::class);
        ProductVariant::observe(AuditObserver::class);
        Category::observe(AuditObserver::class);
        Brand::observe(AuditObserver::class);
        Partner::observe(AuditObserver::class);
        Store::observe(AuditObserver::class);

        $this->configureScramble();
    }

    private function resetTelescopeSqliteOnFresh(): void
    {
        if (! app()->runningInConsole()) return;
        $argv = $_SERVER['argv'] ?? [];

        if (! in_array('migrate:fresh', $argv)) return;

        Event::listen(MigrationsStarted::class, function () {
            $dbPath = config('database.connections.telescope.database');

            if ($dbPath && file_exists($dbPath)) {
                file_put_contents($dbPath, '');
            }
        });
    }

    private function configureScramble(): void
    {
        if (! class_exists(Scramble::class)) {
            return;
        }

        Scramble::routes(function (Route $route) {
            return str_starts_with($route->uri(), 'api/');
        });

        Scramble::extendOpenApi(function (OpenApi $openApi) {
            $openApi->secure(
                SecurityScheme::http('bearer', 'JWT')
            );
        });
    }
}
