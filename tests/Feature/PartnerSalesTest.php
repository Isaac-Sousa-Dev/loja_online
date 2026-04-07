<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Enums\OrderStatus;
use App\Enums\FulfillmentType;
use App\Models\Category;
use App\Models\Client;
use App\Models\Order;
use App\Models\OrderItem;
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
        /** @var User $user */
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
            'code' => 'ORD-SALE-TEST',
            'store_id' => $store->id,
            'client_id' => $client->id,
            'status' => OrderStatus::CONFIRMED,
            'fulfillment_type' => FulfillmentType::PICKUP,
            'subtotal' => 240.00,
            'shipping_amount' => 0,
            'discount_amount' => 0,
            'total' => 240.00,
            'payment_method' => 'pix',
            'payment_installments' => 1,
            'payment_status' => 'paid',
            'message' => null,
            'shift' => false,
            'finance' => false,
        ]);
        OrderItem::create([
            'order_id' => $order->id,
            'store_id' => $store->id,
            'client_id' => $client->id,
            'product_id' => $product->id,
            'quantity' => 2,
            'unit_price' => 120.00,
            'line_subtotal' => 240.00,
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
        /** @var User $user */
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
            'code' => 'ORD-FILTER-1',
            'store_id' => $store->id,
            'client_id' => $client->id,
            'status' => OrderStatus::CONFIRMED,
            'fulfillment_type' => FulfillmentType::PICKUP,
            'subtotal' => 30.00,
            'shipping_amount' => 0,
            'discount_amount' => 0,
            'total' => 30.00,
            'payment_method' => 'cash',
            'payment_installments' => 1,
            'payment_status' => 'paid',
            'message' => null,
            'shift' => false,
            'finance' => false,
        ]);
        OrderItem::create([
            'order_id' => $order->id,
            'store_id' => $store->id,
            'client_id' => $client->id,
            'product_id' => $product->id,
            'quantity' => 1,
            'unit_price' => 30.00,
            'line_subtotal' => 30.00,
        ]);
        app(SyncSaleFromOrdersService::class)->syncForOrder($order);

        $this->actingAs($user)
            ->get(route('index.sales', ['q' => 'Especial']))
            ->assertOk()
            ->assertSee('João Especial');
    }

    public function test_completed_order_syncs_sale_as_completed(): void
    {
        /** @var User $user */
        $user = User::factory()->create();
        $partner = Partner::create(['user_id' => $user->id]);
        $plan = Plan::create([
            'name' => 'Free', 'slug' => 'free', 'price' => 0,
            'duration' => 30, 'status' => 'active', 'type' => 'monthly',
        ]);
        $store = Store::create([
            'partner_id' => $partner->id,
            'plan_id' => $plan->id,
            'store_name' => 'Loja Finalizada',
        ]);
        $category = Category::create(['name' => 'Cat', 'created_by' => $partner->id]);
        $product = Product::create([
            'name' => 'Vestido',
            'description' => 'Teste',
            'price' => 199.90,
            'stock' => 4,
            'partner_id' => $partner->id,
            'category_id' => $category->id,
            'is_active' => true,
        ]);
        $client = Client::create([
            'name' => 'Carla Final',
            'phone' => '11977776666',
            'email' => 'carla@test.test',
        ]);

        $order = Order::create([
            'code' => 'ORD-COMPLETE-1',
            'store_id' => $store->id,
            'client_id' => $client->id,
            'status' => OrderStatus::COMPLETED,
            'fulfillment_type' => FulfillmentType::DELIVERY,
            'subtotal' => 199.90,
            'shipping_amount' => 0,
            'discount_amount' => 0,
            'total' => 199.90,
            'payment_method' => 'pix',
            'payment_installments' => 1,
            'payment_status' => 'paid',
            'completed_at' => now(),
        ]);
        OrderItem::create([
            'order_id' => $order->id,
            'store_id' => $store->id,
            'client_id' => $client->id,
            'product_id' => $product->id,
            'quantity' => 1,
            'unit_price' => 199.90,
            'line_subtotal' => 199.90,
        ]);

        app(SyncSaleFromOrdersService::class)->syncForOrder($order);

        $this->assertDatabaseHas('sales', [
            'store_id' => $store->id,
            'order_ref' => 'ORD-COMPLETE-1',
            'sale_status' => 'completed',
            'status' => 'completed',
        ]);
    }

    public function test_dashboard_totals_reflect_filtered_sales(): void
    {
        /** @var User $user */
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
