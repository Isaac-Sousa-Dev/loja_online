<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Actions\Orders\UpdateOrderStatusAction;
use App\Enums\FulfillmentType;
use App\Enums\OrderStatus;
use App\Models\Category;
use App\Models\Client;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Partner;
use App\Models\Plan;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Store;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use InvalidArgumentException;
use Tests\TestCase;

class OrderInventoryHoldTest extends TestCase
{
    use RefreshDatabase;

    private UpdateOrderStatusAction $updateOrderStatus;

    private Store $store;

    private Product $product;

    private ProductVariant $variant;

    protected function setUp(): void
    {
        parent::setUp();

        $this->updateOrderStatus = app(UpdateOrderStatusAction::class);

        $user = User::factory()->create(['role' => 'partner']);
        $partner = Partner::create(['user_id' => $user->id]);
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
            'store_name' => 'Loja Estoque',
            'wholesale_min_quantity' => 3,
        ]);
        $category = Category::create([
            'name' => 'Cat',
            'created_by' => $partner->id,
        ]);
        $this->product = Product::create([
            'name' => 'Camisa',
            'description' => 'Teste',
            'price' => 100.00,
            'price_wholesale' => 80.00,
            'stock' => 50,
            'partner_id' => $partner->id,
            'category_id' => $category->id,
            'is_active' => true,
        ]);
        $this->variant = ProductVariant::create([
            'product_id' => $this->product->id,
            'color' => 'Azul',
            'size' => 'M',
            'stock' => 10,
            'active' => true,
        ]);
    }

    public function test_confirming_payment_decrements_variant_stock_and_sets_hold_timestamp(): void
    {
        $order = $this->makePendingOrderWithVariantLine(quantity: 3);
        $before = (int) $this->variant->fresh()->stock;

        $this->updateOrderStatus->confirm($order, null);

        $order->refresh();
        self::assertSame(OrderStatus::CONFIRMED, $order->status);
        self::assertNotNull($order->inventory_hold_applied_at);
        self::assertSame($before - 3, (int) $this->variant->fresh()->stock);
        self::assertSame(
            (int) ProductVariant::query()->where('product_id', $this->product->id)->sum('stock'),
            (int) ($this->product->fresh()->stock ?? 0),
            'products.stock deve refletir a soma das variantes após baixa por pedido.'
        );
    }

    public function test_cancelling_after_payment_confirmation_restores_variant_stock(): void
    {
        $order = $this->makePendingOrderWithVariantLine(quantity: 4);
        $before = (int) $this->variant->fresh()->stock;

        $this->updateOrderStatus->confirm($order, null);
        self::assertSame($before - 4, (int) $this->variant->fresh()->stock);

        $this->updateOrderStatus->cancel($order, null);

        $order->refresh();
        self::assertSame(OrderStatus::CANCELLED, $order->status);
        self::assertNull($order->inventory_hold_applied_at);
        self::assertSame($before, (int) $this->variant->fresh()->stock);
        self::assertSame(
            (int) ProductVariant::query()->where('product_id', $this->product->id)->sum('stock'),
            (int) ($this->product->fresh()->stock ?? 0)
        );
    }

    public function test_parent_product_stock_is_sum_of_all_variants_after_variant_line_movement(): void
    {
        ProductVariant::create([
            'product_id' => $this->product->id,
            'color' => 'Vermelho',
            'size' => 'P',
            'stock' => 20,
            'active' => true,
        ]);

        $order = $this->makePendingOrderWithVariantLine(quantity: 2);
        $this->updateOrderStatus->confirm($order, null);

        $expectedSum = (int) ProductVariant::query()->where('product_id', $this->product->id)->sum('stock');
        self::assertSame(8 + 20, $expectedSum);
        self::assertSame($expectedSum, (int) ($this->product->fresh()->stock ?? 0));
    }

    public function test_confirm_fails_when_variant_stock_is_insufficient(): void
    {
        $this->variant->update(['stock' => 1]);
        $order = $this->makePendingOrderWithVariantLine(quantity: 3);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Estoque insuficiente');

        $this->updateOrderStatus->confirm($order, null);
    }

    public function test_completing_sale_does_not_decrement_again_when_hold_was_applied(): void
    {
        $order = $this->makePendingOrderWithVariantLine(quantity: 2);
        $this->updateOrderStatus->confirm($order, null);
        $afterHold = (int) $this->variant->fresh()->stock;

        $order->refresh();
        $order->status = OrderStatus::SEPARATING;
        $order->save();
        $order->status = OrderStatus::DELIVERED;
        $order->save();

        $this->updateOrderStatus->completeWithStock($order, null);

        self::assertSame(OrderStatus::COMPLETED, $order->fresh()->status);
        self::assertSame($afterHold, (int) $this->variant->fresh()->stock);
    }

    public function test_completing_without_prior_hold_still_decrements_stock_once(): void
    {
        $order = $this->makeDeliveredOrderWithVariantLineSkippingHold(quantity: 2);
        $before = (int) $this->variant->fresh()->stock;

        self::assertNull($order->inventory_hold_applied_at);

        $this->updateOrderStatus->completeWithStock($order, null);

        self::assertSame($before - 2, (int) $this->variant->fresh()->stock);
    }

    public function test_confirming_payment_decrements_product_stock_when_line_has_no_variant(): void
    {
        $order = $this->makePendingOrderWithProductOnlyLine(quantity: 5);
        $before = (int) ($this->product->fresh()->stock ?? 0);

        $this->updateOrderStatus->confirm($order, null);

        self::assertNotNull($order->fresh()->inventory_hold_applied_at);
        self::assertSame($before - 5, (int) ($this->product->fresh()->stock ?? 0));
    }

    private function makePendingOrderWithVariantLine(int $quantity): Order
    {
        $client = Client::create([
            'name' => 'Cliente Teste',
            'phone' => '11999999999',
            'email' => 'cliente@test.test',
        ]);

        $order = Order::create([
            'code' => Order::generateUniqueCode(),
            'store_id' => $this->store->id,
            'client_id' => $client->id,
            'status' => OrderStatus::PENDING,
            'fulfillment_type' => FulfillmentType::PICKUP,
            'subtotal' => '200.00',
            'shipping_amount' => '0.00',
            'discount_amount' => '0.00',
            'total' => '200.00',
            'payment_method' => 'pix',
            'payment_installments' => 1,
            'payment_status' => 'pending',
        ]);

        OrderItem::create([
            'order_id' => $order->id,
            'store_id' => $this->store->id,
            'client_id' => $client->id,
            'product_id' => $this->product->id,
            'product_variant_id' => $this->variant->id,
            'selected_color' => 'Azul',
            'selected_size' => 'M',
            'quantity' => $quantity,
            'unit_price' => '100.00',
            'line_subtotal' => bcmul('100.00', (string) $quantity, 2),
        ]);

        return $order->fresh(['items']);
    }

    private function makePendingOrderWithProductOnlyLine(int $quantity): Order
    {
        $client = Client::create([
            'name' => 'Cliente Só Produto',
            'phone' => '11888888888',
            'email' => 'soproduto@test.test',
        ]);

        $order = Order::create([
            'code' => Order::generateUniqueCode(),
            'store_id' => $this->store->id,
            'client_id' => $client->id,
            'status' => OrderStatus::PENDING,
            'fulfillment_type' => FulfillmentType::PICKUP,
            'subtotal' => '500.00',
            'shipping_amount' => '0.00',
            'discount_amount' => '0.00',
            'total' => '500.00',
            'payment_method' => 'pix',
            'payment_installments' => 1,
            'payment_status' => 'pending',
        ]);

        OrderItem::create([
            'order_id' => $order->id,
            'store_id' => $this->store->id,
            'client_id' => $client->id,
            'product_id' => $this->product->id,
            'product_variant_id' => null,
            'selected_color' => null,
            'selected_size' => null,
            'quantity' => $quantity,
            'unit_price' => '100.00',
            'line_subtotal' => bcmul('100.00', (string) $quantity, 2),
        ]);

        return $order->fresh(['items']);
    }

    private function makeDeliveredOrderWithVariantLineSkippingHold(int $quantity): Order
    {
        $order = $this->makePendingOrderWithVariantLine($quantity);
        $order->status = OrderStatus::DELIVERED;
        $order->payment_status = 'paid';
        $order->inventory_hold_applied_at = null;
        $order->saveQuietly();

        return $order->fresh(['items']);
    }
}
