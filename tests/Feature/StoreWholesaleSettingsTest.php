<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Partner;
use App\Models\Plan;
use App\Models\Store;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class StoreWholesaleSettingsTest extends TestCase
{
    use RefreshDatabase;

    public function test_partner_can_save_multiple_wholesale_levels_and_count_mode(): void
    {
        /** @var User $user */
        $user = User::factory()->create(['role' => 'partner']);
        $partner = Partner::query()->create([
            'user_id' => $user->id,
            'partner_link' => 'loja-atacado',
        ]);
        $plan = Plan::query()->create([
            'name' => 'Free',
            'slug' => 'free',
            'price' => 0,
            'duration' => 30,
            'status' => 'active',
            'type' => 'monthly',
        ]);
        $store = Store::query()->create([
            'partner_id' => $partner->id,
            'plan_id' => $plan->id,
            'store_name' => 'Loja Atacado',
            'store_email' => 'loja@test.com',
            'store_phone' => '11999999999',
            'store_cpf_cnpj' => '12345678000100',
        ]);

        $response = $this->actingAs($user)->put(route('store.update', $store->id), [
            'store_name' => 'Loja Atacado',
            'store_email' => 'loja@test.com',
            'store_phone' => '11999999999',
            'store_cpf_cnpj' => '12345678000100',
            'wholesale_count_mode' => 'cart',
            'wholesale_levels' => [
                ['min_quantity' => 10],
                ['min_quantity' => 30],
            ],
        ]);

        $response->assertRedirect(route('store.edit'));
        $store->refresh();

        $this->assertSame('cart', $store->wholesale_count_mode?->value);
        $this->assertDatabaseHas('store_wholesale_levels', [
            'store_id' => $store->id,
            'position' => 1,
            'label' => 'Atacado 1',
            'min_quantity' => 10,
        ]);
        $this->assertDatabaseHas('store_wholesale_levels', [
            'store_id' => $store->id,
            'position' => 2,
            'label' => 'Atacado 2',
            'min_quantity' => 30,
        ]);
    }
}
