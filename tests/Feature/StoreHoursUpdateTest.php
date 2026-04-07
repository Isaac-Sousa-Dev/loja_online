<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Partner;
use App\Models\Plan;
use App\Models\Store;
use App\Models\StoreHour;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class StoreHoursUpdateTest extends TestCase
{
    use RefreshDatabase;

    public function test_partner_can_update_store_hours_from_flat_form_fields(): void
    {
        $user = User::factory()->create(['role' => 'partner']);
        $partner = Partner::query()->create([
            'user_id' => $user->id,
            'partner_link' => 'loja-horarios',
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
            'store_name' => 'Loja Horários',
            'wholesale_min_quantity' => 1,
        ]);

        foreach (range(0, 6) as $dayOfWeek) {
            StoreHour::query()->create([
                'store_id' => $store->id,
                'day_of_week' => $dayOfWeek,
                'open_time' => '08:00:00',
                'close_time' => '18:00:00',
                'is_open' => $dayOfWeek === 0 ? 0 : 1,
            ]);
        }

        $response = $this->actingAs($user)->post(route('store_hours.update', $store->id), [
            'weekday_open' => '09:00',
            'weekday_close' => '17:30',
            'saturday_open' => '10:00',
            'saturday_close' => '15:00',
            'sunday_open' => '11:00',
            'sunday_close' => '13:00',
        ]);

        $response->assertRedirect(route('store.edit'));

        $monday = StoreHour::query()->where('store_id', $store->id)->where('day_of_week', 1)->firstOrFail();
        $this->assertSame('09:00', substr((string) $monday->open_time, 0, 5));
        $this->assertSame('17:30', substr((string) $monday->close_time, 0, 5));
        $this->assertSame(1, (int) $monday->is_open);

        $sunday = StoreHour::query()->where('store_id', $store->id)->where('day_of_week', 0)->firstOrFail();
        $this->assertSame('11:00', substr((string) $sunday->open_time, 0, 5));
        $this->assertSame('13:00', substr((string) $sunday->close_time, 0, 5));
        $this->assertSame(1, (int) $sunday->is_open);
    }
}
