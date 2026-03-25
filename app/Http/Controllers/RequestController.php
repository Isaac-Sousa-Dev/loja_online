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

            // Busca todos os requests com relacionamentos
            $allRequests = $store->requests()
                ->with(['client', 'product.images', 'product.brand'])
                ->latest()
                ->get();

            // Agrupa por order_ref: pedidos com carrinho ficam juntos,
            // pedidos antigos (sem order_ref) ficam individualmente
            $groupedOrders = $allRequests->groupBy(function ($r) {
                return $r->order_ref ?: 'single_' . $r->id;
            })->map(function ($items) {
                return [
                    'order_ref'  => $items->first()->order_ref,
                    'client'     => $items->first()->client,
                    'store'      => $items->first()->store,
                    'status'     => $items->first()->status,
                    'created_at' => $items->first()->created_at,
                    'items'      => $items,
                    'total'      => $items->sum(fn($r) => $r->product->price * $r->quantity),
                    'qty_items'  => $items->sum('quantity'),
                ];
            })->sortByDesc('created_at')->values();

            return view('partner.requests.index', compact('groupedOrders', 'allRequests'));
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
                    'client_id'  => $client->id,
                    'store_id'   => $request->store_id,
                    'product_id' => $request->product_id,
                    'shift'      => $request->shift == "false" ? 0 : 1,
                    'finance'    => $request->finance == "false" ? 0 : 1,
                    'message'    => $request->message,
                    'status'     => RequestStatus::IN_OPEN,
                    'quantity'   => max(1, (int) $request->quantity),
                ]);

                return response()->json(['success' => true, 'message' => 'Solicitação criada com sucesso.'], 201);
            } else {
                return response()->json(['success' => false, 'message' => 'Já existe uma solicitação para este produto e cliente.'], 409);
            }

        } catch(Exception $e) {
            return response()->json(['success' => false, 'message' => 'Erro ao criar a solicitação.', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Recebe o carrinho completo e cria uma request por item,
     * todos vinculados ao mesmo order_ref.
     */
    public function storeCart(Request $request)
    {
        try {
            $items    = $request->input('items', []);
            $storeId  = $request->input('store_id');
            $name     = $request->input('name');
            $phone    = $request->input('phone');
            $message  = $request->input('message', '');

            if (empty($items)) {
                return response()->json(['success' => false, 'message' => 'Carrinho vazio.'], 422);
            }

            $fakeReq = new \Illuminate\Http\Request();
            $fakeReq->merge(['name' => $name, 'phone' => $phone]);

            $client = $this->checkExistClient($phone, $fakeReq);
            $this->checkExistClientStore($storeId, $client);

            // Gera referência única para este pedido (agrupa todos os itens)
            $orderRef = 'ORD-' . strtoupper(substr(md5(uniqid()), 0, 8));

            $created = [];
            foreach ($items as $item) {
                $productId = $item['product_id'];
                $quantity  = max(1, (int) ($item['quantity'] ?? 1));

                // Se já existe request aberta para este produto+cliente, apenas atualiza quantidade
                $existing = ModelsRequest::where('client_id', $client->id)
                    ->where('product_id', $productId)
                    ->whereIn('status', [RequestStatus::IN_OPEN->value, RequestStatus::IN_PROGRESS->value])
                    ->first();

                if ($existing) {
                    $existing->quantity += $quantity;
                    $existing->save();
                    $created[] = $existing->id;
                } else {
                    $req = ModelsRequest::create([
                        'client_id'          => $client->id,
                        'store_id'           => $storeId,
                        'product_id'         => $productId,
                        'product_variant_id' => $item['variant_id'] ?? null,
                        'selected_color'     => $item['color'] ?? null,
                        'selected_size'      => $item['size'] ?? null,
                        'payment_method'     => $request->input('payment_method'),
                        'delivery_type'      => $request->input('delivery_type', 'pickup'),
                        'delivery_address'   => $request->input('delivery_address'),
                        'delivery_city'      => $request->input('delivery_city'),
                        'delivery_state'     => $request->input('delivery_state'),
                        'delivery_zip'       => $request->input('delivery_zip'),
                        'delivery_complement'=> $request->input('delivery_complement'),
                        'shift'              => 0,
                        'finance'            => $request->input('payment_method') === 'credit_card' ? 1 : 0,
                        'message'            => $message,
                        'status'             => RequestStatus::IN_OPEN,
                        'order_ref'          => $orderRef,
                        'quantity'           => $quantity,
                    ]);
                    $created[] = $req->id;
                }
            }

            return response()->json(['success' => true, 'order_ref' => $orderRef, 'created' => $created], 201);

        } catch(Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
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
