<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Partner;
use App\Models\Plan;
use App\Models\Store;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminDashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_dashboard_renders_with_metrics_sections(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->get(route('dashboard'));

        $response->assertOk();
        $response->assertSee('Lojas', false);
        $response->assertSee('Armazenamento', false);
        $response->assertSee('Solicitações', false);
        $response->assertSee('Renda mensal', false);
        $response->assertSee('Últimas solicitações', false);
        $response->assertSee('Últimas lojas cadastradas', false);
        $response->assertDontSee('Gerar Pix', false);
    }

    public function test_monthly_revenue_sums_plans_for_stores_with_active_subscription(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $planActive = Plan::query()->create([
            'name' => 'Plano Ativo',
            'slug' => 'plano-ativo-dash',
            'price' => 49.99,
            'duration' => 30,
            'status' => 'active',
            'type' => 'monthly',
        ]);
        $planPending = Plan::query()->create([
            'name' => 'Plano Pendente',
            'slug' => 'plano-pendente-dash',
            'price' => 200.00,
            'duration' => 30,
            'status' => 'active',
            'type' => 'monthly',
        ]);

        $userActive = User::factory()->create(['role' => 'partner']);
        $partnerActive = Partner::query()->create([
            'user_id' => $userActive->id,
            'partner_link' => 'loja-ativa-dash',
            'is_testing' => false,
        ]);
        Subscription::query()->create([
            'partner_id' => $partnerActive->id,
            'plan_id' => $planActive->id,
            'status' => 'active',
            'start_date' => now()->toDateString(),
            'end_date' => null,
            'payment_method' => 'pix',
            'appellant' => false,
        ]);
        Store::query()->create([
            'partner_id' => $partnerActive->id,
            'plan_id' => $planActive->id,
            'store_name' => 'Loja Assinatura Ativa',
        ]);

        $userPending = User::factory()->create(['role' => 'partner']);
        $partnerPending = Partner::query()->create([
            'user_id' => $userPending->id,
            'partner_link' => 'loja-pendente-dash',
            'is_testing' => false,
        ]);
        Subscription::query()->create([
            'partner_id' => $partnerPending->id,
            'plan_id' => $planPending->id,
            'status' => 'pending',
            'start_date' => now()->toDateString(),
            'end_date' => null,
            'payment_method' => null,
            'appellant' => false,
        ]);
        Store::query()->create([
            'partner_id' => $partnerPending->id,
            'plan_id' => $planPending->id,
            'store_name' => 'Loja Assinatura Pendente',
        ]);

        $response = $this->actingAs($admin)->get(route('dashboard'));

        $response->assertOk();
        $response->assertSee('R$ 49,99', false);
        $response->assertSee('Planos das lojas com assinatura ativa', false);
    }
}
