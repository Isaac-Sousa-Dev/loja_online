<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Partner;
use App\Models\Plan;
use App\Models\Product;
use App\Models\Store;
use App\Models\StoreCategories;
use App\Models\User;
use App\Services\category\CategoryService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryServiceUpdateTest extends TestCase
{
    use RefreshDatabase;

    public function test_renaming_category_migrates_partner_products_to_new_category_id(): void
    {
        $user = User::factory()->create();
        $partner = Partner::create(['user_id' => $user->id]);
        $plan = Plan::create([
            'name' => 'Free',
            'slug' => 'free',
            'price' => 0,
            'duration' => 30,
            'status' => 'active',
            'type' => 'monthly',
        ]);
        $store = Store::create([
            'partner_id' => $partner->id,
            'plan_id' => $plan->id,
            'store_name' => 'Loja Cat',
        ]);

        $categoryOld = Category::create(['name' => 'Calças', 'created_by' => $partner->id]);
        StoreCategories::create([
            'store_id' => $store->id,
            'category_id' => $categoryOld->id,
            'description' => 'Antes',
        ]);

        $product = Product::create([
            'name' => 'Jeans',
            'description' => 'Teste',
            'price' => 99.90,
            'stock' => 3,
            'partner_id' => $partner->id,
            'category_id' => $categoryOld->id,
            'is_active' => true,
        ]);

        app(CategoryService::class)->update([
            'category_id' => $categoryOld->id,
            'name' => 'Calças Femininas',
            'description' => 'Depois',
        ], $partner);

        $product->refresh();
        $newCategory = Category::where('name', 'Calças Femininas')->first();
        $this->assertNotNull($newCategory);
        $this->assertSame((int) $newCategory->id, (int) $product->category_id);
        $this->assertNotSame((int) $categoryOld->id, (int) $product->category_id);

        $storeRow = StoreCategories::where('store_id', $store->id)
            ->where('category_id', $newCategory->id)
            ->first();
        $this->assertNotNull($storeRow);
    }

    public function test_updating_only_description_does_not_change_product_category_id(): void
    {
        $user = User::factory()->create();
        $partner = Partner::create(['user_id' => $user->id]);
        $plan = Plan::create([
            'name' => 'Free',
            'slug' => 'free',
            'price' => 0,
            'duration' => 30,
            'status' => 'active',
            'type' => 'monthly',
        ]);
        $store = Store::create([
            'partner_id' => $partner->id,
            'plan_id' => $plan->id,
            'store_name' => 'Loja Cat 2',
        ]);

        $category = Category::create(['name' => 'Camisas', 'created_by' => $partner->id]);
        StoreCategories::create([
            'store_id' => $store->id,
            'category_id' => $category->id,
            'description' => 'Antiga descrição',
        ]);

        $product = Product::create([
            'name' => 'Polo',
            'description' => 'Teste',
            'price' => 49.90,
            'stock' => 2,
            'partner_id' => $partner->id,
            'category_id' => $category->id,
            'is_active' => true,
        ]);

        app(CategoryService::class)->update([
            'category_id' => $category->id,
            'name' => 'Camisas',
            'description' => 'Nova descrição',
        ], $partner);

        $product->refresh();
        $this->assertSame((int) $category->id, (int) $product->category_id);
    }
}
