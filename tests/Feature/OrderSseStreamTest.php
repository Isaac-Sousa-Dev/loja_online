<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Partner;
use App\Models\Plan;
use App\Models\Store;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderSseStreamTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        config(['orders.sse_enabled' => true]);
    }

    public function test_partner_receives_ok_sse_stream(): void
    {
        $user = User::factory()->create(['role' => 'partner']);
        $partner = Partner::query()->create(['user_id' => $user->id]);
        $plan = Plan::query()->create([
            'name' => 'Free', 'slug' => 'free', 'price' => 0,
            'duration' => 30, 'status' => 'active', 'type' => 'monthly',
        ]);
        Store::query()->create([
            'partner_id' => $partner->id,
            'plan_id' => $plan->id,
            'store_name' => 'Loja SSE',
            'wholesale_min_quantity' => 1,
        ]);

        $response = $this->actingAs($user)->get(route('orders.sse.stream'));

        $response->assertOk();
        $this->assertStringContainsString('text/event-stream', (string) $response->headers->get('Content-Type'));
    }

    public function test_admin_cannot_open_order_sse_stream(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)->get(route('orders.sse.stream'))->assertForbidden();
    }

    public function test_stream_returns_not_found_when_sse_disabled(): void
    {
        config(['orders.sse_enabled' => false]);

        $user = User::factory()->create(['role' => 'partner']);
        $partner = Partner::query()->create(['user_id' => $user->id]);
        $plan = Plan::query()->create([
            'name' => 'Free', 'slug' => 'free', 'price' => 0,
            'duration' => 30, 'status' => 'active', 'type' => 'monthly',
        ]);
        Store::query()->create([
            'partner_id' => $partner->id,
            'plan_id' => $plan->id,
            'store_name' => 'Loja SSE Off',
            'wholesale_min_quantity' => 1,
        ]);

        $this->actingAs($user)->get(route('orders.sse.stream'))->assertNotFound();
    }
}
