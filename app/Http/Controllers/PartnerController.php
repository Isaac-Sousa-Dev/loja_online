<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\SysAdmin\GetPartnerDrawerDataAction;
use App\Actions\SysAdmin\RegisterManualPartnerAction;
use App\Actions\SysAdmin\UpdatePartnerFromAdminAction;
use App\Http\Requests\SysAdmin\PartnerStoreSuspensionRequest;
use App\Http\Requests\SysAdmin\StoreManualPartnerRequest;
use App\Http\Requests\SysAdmin\UpdatePartnerFromAdminRequest;
use App\Mail\SendVerificationCodeMail;
use App\Models\Partner;
use App\Models\Plan;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class PartnerController extends Controller
{
    public function index(Request $request): View
    {
        $query = User::query()
            ->where('role', 'partner')
            ->with(['partner.store', 'partner.subscription.plan', 'partner.salesTeamMembers.user'])
            ->orderByDesc('created_at');

        $search = $request->query('q');
        if (is_string($search) && $search !== '') {
            $term = '%' . trim($search) . '%';
            $query->where(function ($q) use ($term): void {
                $q->where('name', 'like', $term)
                    ->orWhere('email', 'like', $term)
                    ->orWhereHas('partner.store', static function ($s) use ($term): void {
                        $s->where('store_name', 'like', $term);
                    });
            });
        }

        $storeStatus = $request->query('store_status');
        if ($storeStatus === 'suspended_manual') {
            $query->whereHas('partner.store', static function ($s): void {
                $s->whereNotNull('suspended_at');
            });
        } elseif ($storeStatus === 'operational') {
            $query->whereHas('partner.store', static function ($s): void {
                $s->whereNull('suspended_at');
            });
        }

        $users = $query->paginate(15)->withQueryString();

        return view('admin.partners.index', [
            'users' => $users,
            'filterQ' => is_string($search) ? $search : '',
            'filterStoreStatus' => is_string($storeStatus) ? $storeStatus : 'all',
        ]);
    }

    public function drawerData(Partner $partner, GetPartnerDrawerDataAction $action): JsonResponse
    {
        return response()->json($action->execute($partner));
    }

    public function suspendStore(Partner $partner, PartnerStoreSuspensionRequest $request): RedirectResponse
    {
        $store = $partner->store;
        if ($store === null) {
            return redirect()
                ->route('partners.index')
                ->with('error', 'Esta conta de parceiro ainda não possui loja cadastrada.');
        }

        $store->update(['suspended_at' => now()]);

        return redirect()
            ->route('partners.index')
            ->with('success', 'Loja inativada manualmente. Parceiros e consultores não acessam o painel até a reativação.');
    }

    public function reactivateStore(Partner $partner, PartnerStoreSuspensionRequest $request): RedirectResponse
    {
        $store = $partner->store;
        if ($store === null) {
            return redirect()
                ->route('partners.index')
                ->with('error', 'Esta conta de parceiro ainda não possui loja cadastrada.');
        }

        $store->update(['suspended_at' => null]);

        return redirect()
            ->route('partners.index')
            ->with('success', 'Loja reativada. O acesso ao painel foi restabelecido.');
    }

    public function create(): View
    {
        $plans = Plan::query()
            ->where('status', 'active')
            ->orderBy('name')
            ->get();

        return view('admin.partners.create', ['plans' => $plans]);
    }

    public function store(StoreManualPartnerRequest $request, RegisterManualPartnerAction $action): RedirectResponse
    {
        try {
            $user = $action->execute($request);
            Mail::to($user->email)->send(new SendVerificationCodeMail(['user' => $user]));

            return redirect()
                ->route('partners.index')
                ->with('success', 'Loja e usuário criados com sucesso. O parceiro pode entrar com o e-mail e a senha definidos no cadastro.');
        } catch (\Throwable $e) {
            report($e);

            return back()
                ->withInput()
                ->with('error', 'Não foi possível concluir o cadastro. Verifique os dados e tente novamente.');
        }
    }

    public function edit(string $id): View
    {
        $user = User::query()
            ->where('role', 'partner')
            ->with([
                'partner.store.plan',
                'partner.subscription.plan',
                'partner.store.addressStore',
            ])
            ->findOrFail($id);

        if ($user->partner === null || $user->partner->store === null || $user->partner->subscription === null) {
            abort(404);
        }

        $currentPlanId = $user->partner->store->plan_id ?? $user->partner->subscription?->plan_id;
        $plans = Plan::query()
            ->where(static function ($query) use ($currentPlanId): void {
                $query->where('status', 'active');
                if ($currentPlanId !== null) {
                    $query->orWhere('id', $currentPlanId);
                }
            })
            ->orderBy('name')
            ->get();

        return view('admin.partners.edit', [
            'user' => $user,
            'plans' => $plans,
        ]);
    }

    public function update(UpdatePartnerFromAdminRequest $request, string $id, UpdatePartnerFromAdminAction $action): RedirectResponse
    {
        $user = User::query()
            ->where('role', 'partner')
            ->with(['partner.store', 'partner.subscription'])
            ->findOrFail($id);

        $action->execute($user, $request->validated());

        return redirect()
            ->route('partners.index')
            ->with('success', 'Loja e dados do parceiro atualizados com sucesso.');
    }

    public function destroy(string $id): RedirectResponse
    {
        $user = User::query()->findOrFail($id);
        $partner = Partner::query()->where('user_id', $id)->first();

        if ($partner !== null) {
            $partner->delete();
        }
        $user->delete();

        return redirect()->route('partners.index')->with('success', 'Sócio deletado com sucesso!');
    }
}
