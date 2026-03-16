<?php

namespace App\Http\Controllers;

use App\Mail\UserRegistrationMail;
use App\Models\Plan;
use App\Services\requests\RequestPlanService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class RequestPlanController extends Controller
{
    protected $requestPlanService;  
    public function __construct(RequestPlanService $requestPlanService)
    {
        $this->requestPlanService = $requestPlanService;
    }

    public function newRequestPlan(Request $request)
    {
        $data = $request->all();
        $response = $this->requestPlanService->insert($data);
    
        if($response == 'error') {
            return response()->json(['error' => 'E-mail ou telefone já cadastrado'], 400);
        } 
        
        // TODO: Implementar envio em produção
        Mail::to($data['email'])->send(new UserRegistrationMail($data));
        return response()->json(['message' => 'Solicitação de plano enviada com sucesso'], 200);

    }

    public function listRequestPlans()
    {
        $allRequestPlans = $this->requestPlanService->findAll();        
        // Se for um Query Builder
        if ($allRequestPlans instanceof \Illuminate\Database\Eloquent\Builder) {
            $paginatedData = $allRequestPlans->paginate(10);
        }
        // Se for uma Collection
        elseif ($allRequestPlans instanceof \Illuminate\Support\Collection) {
            $paginatedData = $this->paginateCollection($allRequestPlans, 7);
        }
        // Caso inesperado
        else {
            throw new \RuntimeException('Tipo de dado não suportado para paginação');
        }

        foreach($paginatedData as $data) {
            $plan = Plan::where('slug', $data['plan_slug'])->first();
            $data->plan_price = $plan->price;
            $data->plan_name = $plan->name;
            $data->plan_duration = $plan->duration;
            $data->plan_id = $plan->id;
        }
        
        return view('admin.requests.index', ['allRequestPlans' => $paginatedData]);
    }

    /**
     * Pagina uma Collection manualmente
     */
    protected function paginateCollection($items, $perPage)
    {
        $page = \Illuminate\Pagination\Paginator::resolveCurrentPage() ?: 1;
        $offset = ($page - 1) * $perPage;
        
        return new \Illuminate\Pagination\LengthAwarePaginator(
            $items->slice($offset, $perPage),   
            $items->count(),
            $perPage,
            $page,
            [
                'path' => \Illuminate\Pagination\Paginator::resolveCurrentPath()
            ]
        );
    }
}
