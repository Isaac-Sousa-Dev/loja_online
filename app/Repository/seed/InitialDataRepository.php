<?php

declare(strict_types=1);

namespace App\Repository\seed;

use App\Models\AddressStore;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Client;
use App\Models\Image;
use App\Models\Partner;
use App\Models\Plan;
use App\Models\PlanModules;
use App\Models\Product;
use App\Models\RequestPlan;
use App\Models\Sale;
use App\Models\Store;
use App\Models\StoreCategories;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Support\Facades\DB;

/**
 * Persistência usada exclusivamente pelo seed inicial (dados de desenvolvimento).
 */
final class InitialDataRepository
{
    /**
     * @param  list<string>  $moduleKeys
     */
    public function createPlanWithModules(
        string $name,
        string $slug,
        string $description,
        float $price,
        int $durationDays,
        array $moduleKeys,
    ): Plan {
        $plan = Plan::create([
            'name' => $name,
            'slug' => $slug,
            'description' => $description,
            'price' => $price,
            'duration' => (string) $durationDays,
            'status' => 'active',
            'type' => 'monthly',
        ]);

        foreach ($moduleKeys as $module) {
            PlanModules::create([
                'plan_id' => $plan->id,
                'module' => $module,
            ]);
        }

        return $plan;
    }

    public function createUser(array $attributes): User
    {
        return User::create($attributes);
    }

    public function createRequestPlan(array $attributes): RequestPlan
    {
        return RequestPlan::create($attributes);
    }

    public function createPartner(array $attributes): Partner
    {
        return Partner::create($attributes);
    }

    public function createSubscription(array $attributes): Subscription
    {
        return Subscription::create($attributes);
    }

    public function createStore(array $attributes): Store
    {
        return Store::create($attributes);
    }

    /**
     * @param  array<string, mixed>  $row  Colunas da tabela store_hours (sem timestamps)
     */
    public function insertStoreHoursRow(array $row): void
    {
        DB::table('store_hours')->insert(array_merge($row, [
            'created_at' => now(),
            'updated_at' => now(),
        ]));
    }

    public function createAddressStore(array $attributes): AddressStore
    {
        return AddressStore::create($attributes);
    }

    public function createCategory(array $attributes): Category
    {
        return Category::create($attributes);
    }

    public function createStoreCategory(array $attributes): StoreCategories
    {
        return StoreCategories::create($attributes);
    }

    public function createBrand(array $attributes): Brand
    {
        return Brand::create($attributes);
    }

    public function createProduct(array $attributes): Product
    {
        return Product::create($attributes);
    }

    public function createProductImage(array $attributes): Image
    {
        return Image::create($attributes);
    }

    public function createClient(array $attributes): Client
    {
        return Client::create($attributes);
    }

    public function createSale(array $attributes): Sale
    {
        return Sale::create($attributes);
    }

    /**
     * @param  list<array<string, mixed>>  $rows
     */
    public function insertSalesTeamRows(array $rows): void
    {
        $now = now();
        foreach ($rows as &$row) {
            $row['created_at'] = $now;
            $row['updated_at'] = $now;
        }
        unset($row);
        DB::table('sales_teams')->insert($rows);
    }

}
