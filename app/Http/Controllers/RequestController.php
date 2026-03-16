<?php

namespace App\Http\Controllers;

use App\Enums\RequestStatus;
use App\Models\Client;
use App\Models\ClientStore;
use App\Models\Product;
use App\Models\Request as ModelsRequest;
use App\Models\RequestPlan;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RequestController extends Controller
{
    
    public function index()
    {   
        $userAuth = Auth::user();
        if($userAuth->role == 'admin'){
            $requestsPlans = RequestPlan::all()->sortByDesc('created_at');
            return view('admin.requests.index', compact('requestsPlans'));
        } else {
            $partner = $userAuth->partner;
            $store = $partner->store;
            $requestsByStore = $store->requests->sortByDesc('created_at');
            // dd($requestsByStore);
            return view('partner.requests.index', compact('requestsByStore'));
        }
    }

    private function checkExistClient($phone, $request)
    {
        $phoneFormatted = str_replace(['(', ')', '-', ' '], '', $phone);
        $existClient = Client::where('phone', $phoneFormatted)->first();
        if($existClient == null) {
            $client = Client::create([
                'name' => $request->name,
                'phone' => $phoneFormatted
            ]);
            return $client;
        }
        return $existClient;
    }

    private function checkExistRequest($clientId, $productId)
    {
        $existRequest = ModelsRequest::where('client_id', $clientId)->where('product_id', $productId)->first();
        if($existRequest == null) {
            return false;
        }
        return true;
    }

    private function checkExistClientStore($storeId, $client)
    {
        $existClientStore = ClientStore::where('store_id', $storeId)->where('client_id', $client->id)->first();
        if($existClientStore == null) {
            ClientStore::create([
                'store_id' => $storeId,
                'client_id' => $client->id
            ]);
        }
    }

    public function store(Request $request)
    {
        try {
            $client = $this->checkExistClient($request->phone, $request);
            $this->checkExistClientStore($request->store_id, $client);

            $alreadyExists = $this->checkExistRequest($client->id, $request->product_id);
            if (!$alreadyExists) {
                ModelsRequest::create([
                    'client_id' => $client->id,
                    'store_id' => $request->store_id,
                    'product_id' => $request->product_id,
                    'shift' => $request->shift == "false" ? 0 : 1,
                    'finance' => $request->finance == "false" ? 0 : 1,
                    'message' => $request->message,
                    'status' => RequestStatus::IN_OPEN,
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Solicitação criada com sucesso.'
                ], 201);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Já existe uma solicitação para este produto e cliente.'
                ], 409); // 409 Conflict
            }

        } catch(Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao criar a solicitação.',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function init(Request $request)
    {
        $requestId = $request->requestId;
        $solicitation = ModelsRequest::find($requestId);
        $solicitation->status = RequestStatus::IN_PROGRESS->value;
        $solicitation->save();
    }


    public function sold(Request $request)
    {

        $productId = $request->productId;
        $currentProduct = Product::find($productId);
        $currentProduct->stock = $currentProduct->stock - 1;
        $currentProduct->save();

        $requestId = $request->requestId;
        $solicitation = ModelsRequest::find($requestId);
        $solicitation->status = RequestStatus::COMPLETED->value;
        $solicitation->save();

        $newProduct = Product::find($productId);
        // removendo todas as outras solicitações caso o estoque desse produto for zerado
        if($newProduct->stock == 0){
            $requestByProduct = ModelsRequest::where('product_id', $productId)->get();
            foreach($requestByProduct as $request){
                if($request->status == RequestStatus::IN_OPEN->value || $request->status == RequestStatus::IN_PROGRESS->value && $request->status != RequestStatus::COMPLETED->value){
                    // Remove a solicitação
                    $request->delete();
                }
            }
        }
        
    }


    public function unsold(Request $request)
    {
        $requestId = $request->requestId;
        $solicitation = ModelsRequest::find($requestId);
        $solicitation->delete(); 
    }


    public function show(string $id)
    {
        //
    }

  
    public function update(Request $request, string $id)
    {
        //
    }

   
    public function destroy(string $id)
    {
        //
    }
}
