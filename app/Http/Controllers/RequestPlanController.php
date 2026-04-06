<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StorePlanAccessRequest;
use App\Mail\UserRegistrationMail;
use App\Models\Plan;
use App\Services\requests\RequestPlanService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class RequestPlanController extends Controller
{
    public function __construct(
        protected RequestPlanService $requestPlanService
    ) {
    }

    public function showRequestForm(?string $plan = null): View
    {
        $plans = config('vistoo_plans', []);
        $planKey = $plan ?? 'essencial';
        if (! is_array($plans) || ! array_key_exists($planKey, $plans)) {
            $planKey = 'essencial';
        }

        return view('request-plan.access', [
            'selectedPlanKey' => $planKey,
            'selectedPlan' => $plans[$planKey],
            'allPlans' => $plans,
        ]);
    }

    public function storeRequest(StorePlanAccessRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['payment_method'] = 'pendente';
        $data['qtd_products_in_stock'] = $data['qtd_products_in_stock'] ?? '';

        $response = $this->requestPlanService->insert($data);

        if ($response === 'error') {
            return back()
                ->withInput()
                ->withErrors([
                    'email' => 'Este e-mail ou WhatsApp já está cadastrado em nossa base. Tente fazer login ou use outros dados.',
                ]);
        }

        try {
            Mail::to($data['email'])->send(new UserRegistrationMail($data));
        } catch (\Throwable $e) {
            Log::warning('Falha ao enviar e-mail de solicitação de plano', [
                'email' => $data['email'],
                'exception' => $e->getMessage(),
            ]);
        }

        return redirect()
            ->route('request.plan.form', ['plan' => $data['plan_slug']])
            ->with('status', 'received');
    }

    public function listRequestPlans()
    {
        $allRequestPlans = $this->requestPlanService->findAll();
        if ($allRequestPlans instanceof \Illuminate\Database\Eloquent\Builder) {
            $paginatedData = $allRequestPlans->paginate(10);
        } elseif ($allRequestPlans instanceof \Illuminate\Support\Collection) {
            $paginatedData = $this->paginateCollection($allRequestPlans, 7);
        } else {
            throw new \RuntimeException('Tipo de dado não suportado para paginação');
        }

        foreach ($paginatedData as $data) {
            $slug = $data->plan_slug;
            $plan = $slug ? Plan::where('slug', $slug)->first() : null;
            $data->plan_price = $plan?->price ?? '—';
            $data->plan_name = $plan?->name ?? config('vistoo_plans.'.$slug.'.name', $slug ?? '—');
            $data->plan_duration = $plan?->duration ?? '';
            $data->plan_id = $plan?->id;
        }

        return view('admin.requests.index', ['allRequestPlans' => $paginatedData]);
    }

    /**
     * @param  \Illuminate\Support\Collection<int, mixed>  $items
     */
    protected function paginateCollection($items, int $perPage): \Illuminate\Pagination\LengthAwarePaginator
    {
        $page = \Illuminate\Pagination\Paginator::resolveCurrentPage() ?: 1;
        $offset = ($page - 1) * $perPage;

        return new \Illuminate\Pagination\LengthAwarePaginator(
            $items->slice($offset, $perPage),
            $items->count(),
            $perPage,
            $page,
            [
                'path' => \Illuminate\Pagination\Paginator::resolveCurrentPath(),
            ]
        );
    }
}
