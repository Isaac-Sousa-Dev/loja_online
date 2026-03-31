<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Enums\OrderStatus;
use App\Models\Category;
use App\Models\Client;
use App\Models\Order;
use App\Models\Partner;
use App\Models\Plan;
use App\Models\Product;
use App\Models\Sale;
use App\Models\Store;
use App\Models\User;
use App\Services\Sale\SyncSaleFromOrdersService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PartnerSalesTest extends TestCase
{
    use RefreshDatabase;

    public function test_confirmed_order_syncs_and_appears_on_sales_index(): void
    {
        $user = User::factory()->create();
        $partner = Partner::create(['user_id' => $user->id]);
        $plan = Plan::create([
            'name' => 'Free', 'slug' => 'free', 'price' => 0,
            'duration' => 30, 'status' => 'active', 'type' => 'monthly',
        ]);
        $store = Store::create([
            'partner_id' => $partner->id,
            'plan_id' => $plan->id,
            'store_name' => 'Loja Vendas',
        ]);
        $category = Category::create(['name' => 'Cat', 'created_by' => $partner->id]);
        $product = Product::create([
            'name' => 'Jaqueta',
            'description' => 'Teste',
            'price' => 120.00,
            'stock' => 5,
            'partner_id' => $partner->id,
            'category_id' => $category->id,
            'is_active' => true,
        ]);
        $client = Client::create([
            'name' => 'Maria Silva',
            'phone' => '11988887777',
            'email' => 'maria@test.test',
        ]);

        $order = Order::create([
            'store_id' => $store->id,
            'client_id' => $client->id,
            'product_id' => $product->id,
            'status' => OrderStatus::PAID->value,
            'message' => null,
            'shift' => false,
            'finance' => false,
            'order_ref' => 'ORD-SALE-TEST',
            'quantity' => 2,
            'payment_method' => 'pix',
            'delivery_type' => 'pickup',
        ]);

        app(SyncSaleFromOrdersService::class)->syncForOrder($order);

        $this->assertDatabaseHas('sales', [
            'store_id' => $store->id,
            'order_ref' => 'ORD-SALE-TEST',
            'sale_status' => 'confirmed',
        ]);

        $response = $this->actingAs($user)->get(route('index.sales'));
        $response->assertOk();
        $response->assertSee('ORD-SALE-TEST');
        $response->assertSee('Maria Silva');
        $response->assertSee('R$ 240,00', false);
    }

    public function test_sales_search_filter_by_client_name(): void
    {
        $user = User::factory()->create();
        $partner = Partner::create(['user_id' => $user->id]);
        $plan = Plan::create([
            'name' => 'Free', 'slug' => 'free', 'price' => 0,
            'duration' => 30, 'status' => 'active', 'type' => 'monthly',
        ]);
        $store = Store::create([
            'partner_id' => $partner->id,
            'plan_id' => $plan->id,
            'store_name' => 'Loja B',
        ]);
        $category = Category::create(['name' => 'Cat', 'created_by' => $partner->id]);
        $product = Product::create([
            'name' => 'Boné',
            'description' => 'Teste',
            'price' => 30.00,
            'stock' => 3,
            'partner_id' => $partner->id,
            'category_id' => $category->id,
            'is_active' => true,
        ]);
        $client = Client::create([
            'name' => 'João Especial',
            'phone' => '11911112222',
            'email' => null,
        ]);
        $order = Order::create([
            'store_id' => $store->id,
            'client_id' => $client->id,
            'product_id' => $product->id,
            'status' => OrderStatus::PAID->value,
            'message' => null,
            'shift' => false,
            'finance' => false,
            'order_ref' => 'ORD-FILTER-1',
            'quantity' => 1,
            'payment_method' => 'cash',
            'delivery_type' => 'pickup',
        ]);
        app(SyncSaleFromOrdersService::class)->syncForOrder($order);

        $this->actingAs($user)
            ->get(route('index.sales', ['q' => 'Especial']))
            ->assertOk()
            ->assertSee('João Especial');
    }

    public function test_dashboard_totals_reflect_filtered_sales(): void
    {
        $user = User::factory()->create();
        $partner = Partner::create(['user_id' => $user->id]);
        $plan = Plan::create([
            'name' => 'Free', 'slug' => 'free', 'price' => 0,
            'duration' => 30, 'status' => 'active', 'type' => 'monthly',
        ]);
        $store = Store::create([
            'partner_id' => $partner->id,
            'plan_id' => $plan->id,
            'store_name' => 'Loja C',
        ]);
        $category = Category::create(['name' => 'Cat', 'created_by' => $partner->id]);
        $product = Product::create([
            'name' => 'Meia',
            'description' => 'Teste',
            'price' => 10.00,
            'stock' => 10,
            'partner_id' => $partner->id,
            'category_id' => $category->id,
            'is_active' => true,
        ]);
        $client = Client::create(['name' => 'A', 'phone' => '1', 'email' => null]);

        Sale::create([
            'store_id' => $store->id,
            'client_id' => $client->id,
            'seller_id' => null,
            'product_id' => $product->id,
            'total_amount' => 100.00,
            'subtotal' => 100.00,
            'shipping_amount' => 0,
            'items_count' => 1,
            'items_summary' => '1× Meia',
            'sale_status' => 'completed',
            'type' => 1,
            'status' => 'completed',
            'payment_method' => 'pix',
            'discount' => 0,
            'fees' => 0,
        ]);

        Sale::create([
            'store_id' => $store->id,
            'client_id' => $client->id,
            'seller_id' => null,
            'product_id' => $product->id,
            'total_amount' => 50.00,
            'subtotal' => 50.00,
            'shipping_amount' => 0,
            'items_count' => 1,
            'items_summary' => '1× Meia',
            'sale_status' => 'completed',
            'type' => 1,
            'status' => 'completed',
            'payment_method' => 'cash',
            'discount' => 0,
            'fees' => 0,
        ]);

        $html = $this->actingAs($user)
            ->get(route('index.sales', ['payment_method' => 'pix']))
            ->assertOk()
            ->getContent();

        $this->assertStringContainsString('R$ 100,00', $html);
        $this->assertStringNotContainsString('R$ 50,00', $html);
    }
}
