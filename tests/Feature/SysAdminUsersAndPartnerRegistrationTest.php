<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Mail\SendVerificationCodeMail;
use App\Models\Partner;
use App\Providers\RouteServiceProvider;
use App\Models\Plan;
use App\Models\Store;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class SysAdminUsersAndPartnerRegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_users_index_is_ok(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->get(route('admin.users.index'));

        $response->assertOk();
        $response->assertSee('Usuários da equipe', false);
    }

    public function test_partner_cannot_access_admin_users_index(): void
    {
        $partner = User::factory()->create(['role' => 'partner']);

        $response = $this->actingAs($partner)->get(route('admin.users.index'));

        $response->assertForbidden();
    }

    public function test_admin_can_register_partner_with_plan_start_date_and_grant_access(): void
    {
        Mail::fake();

        $admin = User::factory()->create(['role' => 'admin']);
        $plan = Plan::query()->create([
            'name' => 'Essencial',
            'slug' => 'essencial-sysadmin-test',
            'price' => 49.99,
            'duration' => 30,
            'status' => 'active',
            'type' => 'monthly',
        ]);

        $response = $this->actingAs($admin)->post(route('partners.store'), [
            'name' => 'João Lojista',
            'email' => 'joao.loja@example.com',
            'phone' => '(11) 98888-7777',
            'store_name' => 'Moda Solar',
            'plan_id' => $plan->id,
            'start_date' => '2026-01-15',
            'payment_method' => 'pix',
            'grant_access' => '1',
        ]);

        $response->assertRedirect(route('partners.index'));
        $response->assertSessionHas('success');

        Mail::assertSent(SendVerificationCodeMail::class);

        $user = User::query()->where('email', 'joao.loja@example.com')->first();
        $this->assertNotNull($user);
        $this->assertSame('partner', $user->role);
        $this->assertTrue($user->must_change_password);
        $this->assertTrue(Hash::check((string) config('partner.default_manual_store_password'), $user->password));
        $this->assertNotNull($user->verification_code);
        $this->assertNotNull($user->email_verified_at);

        $partner = Partner::query()->where('user_id', $user->id)->first();
        $this->assertNotNull($partner);
        $store = Store::query()->where('partner_id', $partner->id)->first();
        $this->assertNotNull($store);
        $this->assertSame('Moda Solar', $store->store_name);

        $subscription = Subscription::query()->where('partner_id', $partner->id)->first();
        $this->assertNotNull($subscription);
        $this->assertSame('active', $subscription->status);
        $this->assertSame('2026-01-15', (string) $subscription->start_date);
    }

    public function test_admin_can_register_partner_without_grant_access(): void
    {
        Mail::fake();

        $admin = User::factory()->create(['role' => 'admin']);
        $plan = Plan::query()->create([
            'name' => 'Básico',
            'slug' => 'basico-sysadmin-test',
            'price' => 29.99,
            'duration' => 30,
            'status' => 'active',
            'type' => 'monthly',
        ]);

        $response = $this->actingAs($admin)->post(route('partners.store'), [
            'name' => 'Maria Lojista',
            'email' => 'maria.loja@example.com',
            'phone' => '21999998888',
            'store_name' => 'Boutique Lua',
            'plan_id' => $plan->id,
            'start_date' => '2026-02-01',
            'grant_access' => '0',
        ]);

        $response->assertRedirect(route('partners.index'));

        $user = User::query()->where('email', 'maria.loja@example.com')->first();
        $this->assertNotNull($user);
        $this->assertNull($user->email_verified_at);

        $partner = Partner::query()->where('user_id', $user->id)->first();
        $this->assertNotNull($partner);
        $subscription = Subscription::query()->where('partner_id', $partner->id)->first();
        $this->assertNotNull($subscription);
        $this->assertSame('pending', $subscription->status);
    }

    public function test_partner_cannot_post_manual_partner_registration(): void
    {
        $plan = Plan::query()->create([
            'name' => 'X',
            'slug' => 'x-sysadmin-test',
            'price' => 10,
            'duration' => 30,
            'status' => 'active',
            'type' => 'monthly',
        ]);
        $partnerUser = User::factory()->create(['role' => 'partner']);

        $response = $this->actingAs($partnerUser)->post(route('partners.store'), [
            'name' => 'Fake',
            'email' => 'fake@example.com',
            'phone' => '11999999999',
            'store_name' => 'Fake Store',
            'plan_id' => $plan->id,
            'start_date' => '2026-03-01',
            'grant_access' => '1',
        ]);

        $response->assertForbidden();
    }

    public function test_admin_users_index_lists_only_sysadmin_users(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        User::factory()->create(['role' => 'admin', 'name' => 'Segundo Admin']);
        $partner = User::factory()->create(['role' => 'partner', 'name' => 'Só Parceiro']);

        $response = $this->actingAs($admin)->get(route('admin.users.index'));

        $response->assertOk();
        $response->assertSee('Segundo Admin', false);
        $response->assertDontSee('Só Parceiro', false);
    }

    public function test_admin_can_fetch_partner_drawer_json(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $plan = Plan::query()->create([
            'name' => 'Drawer Plan',
            'slug' => 'drawer-plan-test',
            'price' => 39.99,
            'duration' => 30,
            'status' => 'active',
            'type' => 'monthly',
        ]);
        $partnerUser = User::factory()->create(['role' => 'partner']);
        $partner = Partner::query()->create([
            'user_id' => $partnerUser->id,
            'partner_link' => 'drawer-loja',
            'is_testing' => false,
        ]);
        Subscription::query()->create([
            'partner_id' => $partner->id,
            'plan_id' => $plan->id,
            'status' => 'active',
            'start_date' => now()->toDateString(),
            'end_date' => null,
            'payment_method' => 'pix',
            'appellant' => false,
        ]);
        Store::query()->create([
            'partner_id' => $partner->id,
            'plan_id' => $plan->id,
            'store_name' => 'Loja Drawer Test',
        ]);

        $response = $this->actingAs($admin)->getJson(route('partners.drawer', $partner));

        $response->assertOk();
        $response->assertJsonPath('store.store_name', 'Loja Drawer Test');
        $response->assertJsonStructure([
            'partner',
            'store',
            'subscription',
            'monthly' => ['orders_count', 'revenue_ex_cancelled', 'revenue_completed_only', 'period_label'],
            'linked_users',
        ]);
    }

    public function test_admin_can_suspend_and_reactivate_store_manually(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $plan = Plan::query()->create([
            'name' => 'Suspend Plan',
            'slug' => 'suspend-plan-test',
            'price' => 19.99,
            'duration' => 30,
            'status' => 'active',
            'type' => 'monthly',
        ]);
        $partnerUser = User::factory()->create(['role' => 'partner']);
        $partner = Partner::query()->create([
            'user_id' => $partnerUser->id,
            'partner_link' => 'suspend-loja',
            'is_testing' => false,
        ]);
        Subscription::query()->create([
            'partner_id' => $partner->id,
            'plan_id' => $plan->id,
            'status' => 'active',
            'start_date' => now()->toDateString(),
            'end_date' => null,
            'payment_method' => 'pix',
            'appellant' => false,
        ]);
        $store = Store::query()->create([
            'partner_id' => $partner->id,
            'plan_id' => $plan->id,
            'store_name' => 'Loja Suspend',
        ]);

        $this->actingAs($admin)->post(route('partners.suspend', $partner))->assertRedirect(route('partners.index'));

        $store->refresh();
        $this->assertNotNull($store->suspended_at);

        $this->actingAs($admin)->post(route('partners.reactivate', $partner))->assertRedirect(route('partners.index'));

        $store->refresh();
        $this->assertNull($store->suspended_at);
    }

    public function test_partner_is_redirected_from_dashboard_when_store_suspended_manually(): void
    {
        $plan = Plan::query()->create([
            'name' => 'Block Plan',
            'slug' => 'block-plan-test',
            'price' => 9.99,
            'duration' => 30,
            'status' => 'active',
            'type' => 'monthly',
        ]);
        $partnerUser = User::factory()->create(['role' => 'partner']);
        $partner = Partner::query()->create([
            'user_id' => $partnerUser->id,
            'partner_link' => 'block-loja',
            'is_testing' => false,
        ]);
        Subscription::query()->create([
            'partner_id' => $partner->id,
            'plan_id' => $plan->id,
            'status' => 'active',
            'start_date' => now()->toDateString(),
            'end_date' => null,
            'payment_method' => 'pix',
            'appellant' => false,
        ]);
        Store::query()->create([
            'partner_id' => $partner->id,
            'plan_id' => $plan->id,
            'store_name' => 'Loja Bloqueada',
            'suspended_at' => now(),
        ]);

        $response = $this->actingAs($partnerUser)->get(route('dashboard'));

        $response->assertRedirect(route('partner.store.suspended'));
    }

    public function test_admin_partner_edit_page_renders(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $plan = Plan::query()->create([
            'name' => 'Edit UI Plan',
            'slug' => 'edit-ui-plan',
            'price' => 59.99,
            'duration' => 30,
            'status' => 'active',
            'type' => 'monthly',
        ]);
        $partnerUser = User::factory()->create(['role' => 'partner', 'name' => 'Titular Edição']);
        $partner = Partner::query()->create([
            'user_id' => $partnerUser->id,
            'partner_link' => 'loja-edicao-ui',
            'is_testing' => false,
        ]);
        Subscription::query()->create([
            'partner_id' => $partner->id,
            'plan_id' => $plan->id,
            'status' => 'active',
            'start_date' => now()->toDateString(),
            'end_date' => null,
            'payment_method' => 'pix',
            'appellant' => false,
        ]);
        Store::query()->create([
            'partner_id' => $partner->id,
            'plan_id' => $plan->id,
            'store_name' => 'Loja Edição UI',
        ]);

        $response = $this->actingAs($admin)->get(route('partners.edit', $partnerUser->id));

        $response->assertOk();
        $response->assertSee('Editar loja', false);
        $response->assertSee('Loja Edição UI', false);
        $response->assertSee('Titular Edição', false);
    }

    public function test_admin_can_update_partner_store_from_edit_form(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $plan = Plan::query()->create([
            'name' => 'Plano Base',
            'slug' => 'plano-base-upd',
            'price' => 29.99,
            'duration' => 30,
            'status' => 'active',
            'type' => 'monthly',
        ]);
        $partnerUser = User::factory()->create([
            'role' => 'partner',
            'email' => 'antes@example.com',
        ]);
        $partner = Partner::query()->create([
            'user_id' => $partnerUser->id,
            'partner_link' => 'loja-update',
            'is_testing' => false,
        ]);
        Subscription::query()->create([
            'partner_id' => $partner->id,
            'plan_id' => $plan->id,
            'status' => 'active',
            'start_date' => '2026-01-01',
            'end_date' => null,
            'payment_method' => 'pix',
            'appellant' => false,
        ]);
        $store = Store::query()->create([
            'partner_id' => $partner->id,
            'plan_id' => $plan->id,
            'store_name' => 'Nome Antigo',
        ]);

        $response = $this->actingAs($admin)->put(route('partners.update', $partnerUser->id), [
            'name' => 'Novo Titular',
            'email' => 'depois@example.com',
            'phone' => '(11) 98888-7777',
            'partner_link' => 'loja-update',
            'is_testing' => '0',
            'store_name' => 'Nome Novo',
            'store_email' => 'loja@example.com',
            'store_phone' => '',
            'store_cpf_cnpj' => '',
            'qtd_products_in_stock' => '',
            'plan_id' => (string) $plan->id,
            'subscription_status' => 'pending',
            'start_date' => '2026-02-01',
            'end_date' => '',
            'payment_method' => 'manual',
            'street' => 'Rua X',
            'city' => 'São Paulo',
            'state' => 'SP',
            'neighborhood' => 'Centro',
            'number' => '100',
            'zip_code' => '01310-100',
            'country' => 'Brasil',
            'panel_suspended' => '0',
        ]);

        $response->assertRedirect(route('partners.index'));
        $response->assertSessionHas('success');

        $partnerUser->refresh();
        $this->assertSame('Novo Titular', $partnerUser->name);
        $this->assertSame('depois@example.com', $partnerUser->email);

        $store->refresh();
        $this->assertSame('Nome Novo', $store->store_name);

        $subscription = Subscription::query()->where('partner_id', $partner->id)->first();
        $this->assertNotNull($subscription);
        $this->assertSame('pending', $subscription->status);
    }

    public function test_manual_partner_is_redirected_to_change_password_and_can_complete_it(): void
    {
        Mail::fake();

        $admin = User::factory()->create(['role' => 'admin']);
        $plan = Plan::query()->create([
            'name' => 'Fluxo Senha',
            'slug' => 'fluxo-senha-test',
            'price' => 19.99,
            'duration' => 30,
            'status' => 'active',
            'type' => 'monthly',
        ]);

        $this->actingAs($admin)->post(route('partners.store'), [
            'name' => 'Titular Senha',
            'email' => 'titular.senha@example.com',
            'phone' => '11888887777',
            'store_name' => 'Loja Senha',
            'plan_id' => $plan->id,
            'start_date' => '2026-04-05',
            'grant_access' => '1',
        ])->assertRedirect(route('partners.index'));

        $user = User::query()->where('email', 'titular.senha@example.com')->first();
        $this->assertNotNull($user);
        $this->assertTrue($user->must_change_password);

        $this->post(route('logout'));

        $provisional = (string) config('partner.default_manual_store_password');

        $this->post('/login', [
            'email' => $user->email,
            'password' => $provisional,
        ]);

        $this->assertAuthenticatedAs($user);

        $this->get(route('dashboard'))->assertRedirect(route('partner.first-password.edit'));

        $this->put(route('partner.first-password.update'), [
            'current_password' => $provisional,
            'password' => 'NovaSenhaForte9!',
            'password_confirmation' => 'NovaSenhaForte9!',
        ])->assertRedirect(RouteServiceProvider::HOME);

        $user->refresh();
        $this->assertFalse($user->must_change_password);
        $this->assertTrue(Hash::check('NovaSenhaForte9!', $user->password));
    }
}
