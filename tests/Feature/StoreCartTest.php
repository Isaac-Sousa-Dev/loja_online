<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Partner;
use App\Models\Plan;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Store;
use App\Models\User;
use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StoreCartTest extends TestCase
{
    use RefreshDatabase;

    private Store $store;
    private Product $product;
    private ProductVariant $variantRed;
    private ProductVariant $variantBlue;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware(ValidateCsrfToken::class);

        $user     = User::factory()->create();
        $partner  = Partner::create(['user_id' => $user->id]);
        $plan     = Plan::create([
            'name' => 'Free', 'slug' => 'free', 'price' => 0,
            'duration' => 30, 'status' => 'active', 'type' => 'monthly',
        ]);
        $this->store = Store::create([
            'partner_id' => $partner->id,
            'plan_id'    => $plan->id,
            'store_name' => 'Loja Teste',
            'wholesale_min_quantity' => 3,
        ]);

        $category = Category::create(['name' => 'Cat', 'created_by' => $partner->id]);

        $this->product = Product::create([
            'name'        => 'Camisa',
            'description' => 'Camisa de teste',
            'price'       => 50.00,
            'price_wholesale' => 40.00,
            'stock'       => 10,
            'partner_id'  => $partner->id,
            'category_id' => $category->id,
            'is_active'   => true,
        ]);

        $this->variantRed = ProductVariant::create([
            'product_id' => $this->product->id,
            'color'      => 'Vermelho',
            'color_hex'  => '#FF0000',
            'size'       => 'M',
            'stock'      => 5,
            'active'     => true,
        ]);

        $this->variantBlue = ProductVariant::create([
            'product_id' => $this->product->id,
            'color'      => 'Azul',
            'color_hex'  => '#0000FF',
            'size'       => 'M',
            'stock'      => 3,
            'active'     => true,
        ]);
    }

    public function test_different_variants_create_separate_orders(): void
    {
        $payload = [
            'name'     => 'João',
            'phone'    => '11999998888',
            'store_id' => $this->store->id,
            'payment_method' => 'pix',
            'items' => [
                [
                    'product_id' => $this->product->id,
                    'quantity'   => 2,
                    'variant_id' => $this->variantRed->id,
                    'color'      => 'Vermelho',
                    'size'       => 'M',
                ],
                [
                    'product_id' => $this->product->id,
                    'quantity'   => 1,
                    'variant_id' => $this->variantBlue->id,
                    'color'      => 'Azul',
                    'size'       => 'M',
                ],
            ],
        ];

        $response = $this->postJson(route('orders.storeCart'), $payload);

        $response->assertStatus(201);
        $response->assertJsonStructure(['success', 'order_ref', 'order_id', 'created']);

        $this->assertEquals(1, Order::query()->where('store_id', $this->store->id)->count());
        $header = Order::query()->where('store_id', $this->store->id)->first();
        $this->assertNotNull($header);

        $items = OrderItem::query()->where('order_id', $header->id)->where('product_id', $this->product->id)->get();
        $this->assertCount(2, $items);

        $redLine = $items->firstWhere('product_variant_id', $this->variantRed->id);
        $blueLine = $items->firstWhere('product_variant_id', $this->variantBlue->id);

        $this->assertNotNull($redLine);
        $this->assertNotNull($blueLine);
        $this->assertEquals(2, $redLine->quantity);
        $this->assertEquals(1, $blueLine->quantity);
        $this->assertEquals($header->code, $response->json('order_ref'));
    }

    public function test_same_variant_increments_existing_order(): void
    {
        $payload = [
            'name'     => 'Maria',
            'phone'    => '11988887777',
            'store_id' => $this->store->id,
            'items' => [
                [
                    'product_id' => $this->product->id,
                    'quantity'   => 1,
                    'variant_id' => $this->variantRed->id,
                    'color'      => 'Vermelho',
                    'size'       => 'M',
                ],
            ],
        ];

        $this->postJson(route('orders.storeCart'), $payload)->assertStatus(201);
        $this->postJson(route('orders.storeCart'), $payload)->assertStatus(201);

        $this->assertEquals(1, Order::query()->where('store_id', $this->store->id)->count());
        $header = Order::query()->where('store_id', $this->store->id)->first();
        $line = OrderItem::query()
            ->where('order_id', $header->id)
            ->where('product_id', $this->product->id)
            ->where('product_variant_id', $this->variantRed->id)
            ->first();

        $this->assertNotNull($line);
        $this->assertEquals(2, $line->quantity);
    }

    public function test_empty_cart_returns_422(): void
    {
        $payload = [
            'name'     => 'Teste',
            'phone'    => '11999990000',
            'store_id' => $this->store->id,
            'items'    => [],
        ];

        $response = $this->postJson(route('orders.storeCart'), $payload);
        $response->assertStatus(422);
    }

    public function test_store_cart_applies_wholesale_price_when_minimum_quantity_is_reached(): void
    {
        $payload = [
            'name' => 'Atacado',
            'phone' => '11977776666',
            'store_id' => $this->store->id,
            'items' => [
                [
                    'product_id' => $this->product->id,
                    'quantity' => 3,
                    'variant_id' => $this->variantRed->id,
                    'color' => 'Vermelho',
                    'size' => 'M',
                ],
            ],
        ];

        $this->postJson(route('orders.storeCart'), $payload)->assertCreated();

        $line = OrderItem::query()->where('product_variant_id', $this->variantRed->id)->first();

        $this->assertNotNull($line);
        $this->assertSame('40.00', number_format((float) $line->unit_price, 2, '.', ''));
        $this->assertSame('120.00', number_format((float) $line->line_subtotal, 2, '.', ''));
    }

    public function test_store_cart_reprices_existing_line_when_quantity_crosses_wholesale_threshold(): void
    {
        $payload = [
            'name' => 'Escalonado',
            'phone' => '11966665555',
            'store_id' => $this->store->id,
            'items' => [
                [
                    'product_id' => $this->product->id,
                    'quantity' => 2,
                    'variant_id' => $this->variantBlue->id,
                    'color' => 'Azul',
                    'size' => 'M',
                ],
            ],
        ];

        $this->postJson(route('orders.storeCart'), $payload)->assertCreated();

        $payload['items'][0]['quantity'] = 1;
        $this->postJson(route('orders.storeCart'), $payload)->assertCreated();

        $line = OrderItem::query()->where('product_variant_id', $this->variantBlue->id)->first();

        $this->assertNotNull($line);
        $this->assertSame(3, $line->quantity);
        $this->assertSame('40.00', number_format((float) $line->unit_price, 2, '.', ''));
        $this->assertSame('120.00', number_format((float) $line->line_subtotal, 2, '.', ''));
    }
}
