<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Enums\FulfillmentType;
use App\Enums\OrderStatus;
use App\Models\Category;
use App\Models\Client;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Partner;
use App\Models\Plan;
use App\Models\Product;
use App\Models\StoreWholesaleLevel;
use App\Models\Store;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PartnerOrdersIndexTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    private Store $store;

    private Product $product;
    private StoreWholesaleLevel $wholesaleLevel;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $partner = Partner::create(['user_id' => $this->user->id]);
        $plan = Plan::create([
            'name' => 'Free',
            'slug' => 'free',
            'price' => 0,
            'duration' => 30,
            'status' => 'active',
            'type' => 'monthly',
        ]);
        $this->store = Store::create([
            'partner_id' => $partner->id,
            'plan_id' => $plan->id,
            'store_name' => 'Loja Pedidos',
            'wholesale_min_quantity' => 3,
        ]);
        $this->wholesaleLevel = StoreWholesaleLevel::create([
            'store_id' => $this->store->id,
            'position' => 1,
            'label' => 'Atacado 1',
            'min_quantity' => 3,
        ]);
        $category = Category::create([
            'name' => 'Cat',
            'created_by' => $partner->id,
        ]);
        $this->product = Product::create([
            'name' => 'Blazer',
            'description' => 'Teste',
            'price' => 100.00,
            'price_wholesale' => 80.00,
            'stock' => 10,
            'partner_id' => $partner->id,
            'category_id' => $category->id,
            'is_active' => true,
        ]);
    }

    public function test_orders_index_defaults_to_today_pending_orders(): void
    {
        $todayPending = $this->createOrder('ORD-TODAY-PENDING', OrderStatus::PENDING, now());
        $this->createOrder('ORD-TODAY-CONFIRMED', OrderStatus::CONFIRMED, now());
        $this->createOrder('ORD-YESTERDAY-PENDING', OrderStatus::PENDING, now()->subDay());

        $response = $this->actingAs($this->user)->get(route('orders.index'));

        $response->assertOk();
        $response->assertSee($todayPending->code);
        $response->assertDontSee('ORD-TODAY-CONFIRMED');
        $response->assertDontSee('ORD-YESTERDAY-PENDING');
        $response->assertSee('Pedidos');
        $response->assertSee('Hoje');
    }

    public function test_orders_index_can_switch_to_last_7_days_with_quick_filters(): void
    {
        $this->createOrder('ORD-2-DAYS', OrderStatus::CONFIRMED, now()->subDays(2));
        $this->createOrder('ORD-6-DAYS', OrderStatus::CANCELLED, now()->subDays(6));
        $this->createOrder('ORD-10-DAYS', OrderStatus::PENDING, now()->subDays(10));

        $response = $this->actingAs($this->user)->get(route('orders.index', [
            'period' => '7d',
            'quick_status' => 'all',
        ]));

        $response->assertOk();
        $response->assertSee('ORD-2-DAYS');
        $response->assertSee('ORD-6-DAYS');
        $response->assertDontSee('ORD-10-DAYS');
    }

    public function test_orders_index_exposes_wholesale_pricing_mode_in_drawer_payload(): void
    {
        $this->createOrder('ORD-WHOLESALE', OrderStatus::PENDING, now(), 3, 80.00);

        $response = $this->actingAs($this->user)->get(route('orders.index', [
            'quick_status' => OrderStatus::PENDING->value,
        ]));

        $response->assertOk();
        $response->assertSee('"pricingModeLabel":"Atacado"', false);
        $response->assertSee('"pricingModeLabel":"Atacado"', false);
    }

    private function createOrder(
        string $code,
        OrderStatus $status,
        \Carbon\CarbonInterface $createdAt,
        int $quantity = 1,
        float $unitPrice = 100.00,
    ): Order {
        $client = Client::create([
            'name' => 'Cliente '.$code,
            'phone' => fake()->numerify('119########'),
            'email' => strtolower($code).'@test.test',
        ]);

        $order = Order::create([
            'code' => $code,
            'store_id' => $this->store->id,
            'client_id' => $client->id,
            'status' => $status,
            'fulfillment_type' => FulfillmentType::PICKUP,
            'subtotal' => $unitPrice * $quantity,
            'shipping_amount' => 0,
            'discount_amount' => 0,
            'total' => $unitPrice * $quantity,
            'payment_method' => 'pix',
            'payment_installments' => 1,
            'payment_status' => 'pending',
        ]);

        $order->forceFill([
            'created_at' => $createdAt,
            'updated_at' => $createdAt,
        ])->saveQuietly();

        OrderItem::create([
            'order_id' => $order->id,
            'store_id' => $this->store->id,
            'client_id' => $client->id,
            'product_id' => $this->product->id,
            'store_wholesale_level_id' => $unitPrice < 100 ? $this->wholesaleLevel->id : null,
            'wholesale_applied_mode' => $unitPrice < 100 ? 'product' : null,
            'quantity' => $quantity,
            'unit_price' => $unitPrice,
            'line_subtotal' => $unitPrice * $quantity,
        ]);

        return $order;
    }
}
