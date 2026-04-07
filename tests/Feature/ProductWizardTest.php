<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Partner;
use App\Models\Plan;
use App\Models\Product;
use App\Models\Store;
use App\Models\StoreCategories;
use App\Models\User;
use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductWizardTest extends TestCase
{
    use RefreshDatabase;

    public function test_wizard_store_returns_json_with_product_id_and_syncs_variants(): void
    {
        $this->withoutMiddleware(ValidateCsrfToken::class);

        $user = User::factory()->create();
        $partner = Partner::create(['user_id' => $user->id]);
        $plan = Plan::create([
            'name' => 'Test Plan',
            'slug' => 'test-plan',
            'price' => 0,
            'duration' => 30,
            'status' => 'active',
            'type' => 'monthly',
        ]);
        $store = Store::create([
            'partner_id' => $partner->id,
            'plan_id' => $plan->id,
            'store_name' => 'Loja Teste',
        ]);
        $category = Category::create(['name' => 'Test Cat', 'created_by' => $partner->id]);
        StoreCategories::create([
            'store_id' => $store->id,
            'category_id' => $category->id,
        ]);
        $brand = Brand::create([
            'codigo' => 'BR01',
            'name' => 'Brand',
            'partner_id' => $partner->id,
        ]);

        $payload = [
            'category_id' => (string) $category->id,
            'brand_id' => (string) $brand->id,
            'name' => 'Camisa Teste',
            'description' => 'Descrição',
            'price' => '10,00',
            'price_wholesale' => '',
            'price_promotional' => '',
            'cost' => '',
            'profit' => '',
            'gender' => 'M',
            'weight' => '',
            'width' => '',
            'height' => '',
            'length' => '',
            'installments' => '1',
            'discount_pix' => '',
            'variants_payload' => json_encode([
                ['color' => 'Preto', 'size' => 'M', 'stock' => 5, 'sku' => 'TST-P-M', 'price' => ''],
            ], JSON_THROW_ON_ERROR),
        ];

        $response = $this->actingAs($user)
            ->withHeader('Accept', 'application/json')
            ->post(route('products.wizard.store'), $payload);

        $response->assertCreated();
        $response->assertJsonStructure(['product_id', 'message']);

        $product = Product::first();
        $this->assertNotNull($product);
        $this->assertSame(5, (int) $product->stock);
        $this->assertCount(1, $product->variants()->get());
    }
}
