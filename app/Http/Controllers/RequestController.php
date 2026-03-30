<?php

namespace App\Http\Controllers;

use App\Enums\OrderStatus;
use App\Models\Client;
use App\Models\ClientStore;
use App\Models\Order;
use App\Models\Product;
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
            $allOrders = $store->orders()
                ->with(['client', 'product.images', 'product.brand'])
                ->latest()
                ->get();

            // Agrupa por order_ref: pedidos com carrinho ficam juntos,
            // pedidos antigos (sem order_ref) ficam individualmente
            $groupedOrders = $allOrders->groupBy(function ($r) {
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

            return view('partner.requests.index', compact('groupedOrders', 'allOrders'));
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
                $existing = Order::where('client_id', $client->id)
                    ->where('product_id', $productId)
                    ->whereIn('status', [OrderStatus::PENDING->value, OrderStatus::PAID->value])
                    ->first();

                if ($existing) {
                    $existing->quantity += $quantity;
                    $existing->save();
                    $created[] = $existing->id;
                } else {
                    $req = Order::create([
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
                        'message'            => $message,
                        'status'             => OrderStatus::PENDING->value,
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
        $solicitation = Order::find($requestId);
        $solicitation->status = OrderStatus::PAID->value;
        $solicitation->save();
    }


    public function sold(Request $request)
    {

        $productId = $request->productId;
        $currentProduct = Product::find($productId);
        $currentProduct->stock = $currentProduct->stock - 1;
        $currentProduct->save();

        $requestId = $request->requestId;
        $solicitation = Order::find($requestId);
        $solicitation->status = OrderStatus::SOLD->value;
        $solicitation->save();

        $newProduct = Product::find($productId);
        // removendo todas as outras solicitações caso o estoque desse produto for zerado
        if($newProduct->stock == 0){
            $requestByProduct = Order::where('product_id', $productId)->get();
            foreach($requestByProduct as $request){
                if($request->status == OrderStatus::PENDING->value || $request->status == OrderStatus::PAID->value && $request->status != OrderStatus::SOLD->value){
                    // Remove a solicitação
                    $request->delete();
                }
            }
        }
        
    }

    public function unsold(Request $request)
    {
        $requestId = $request->requestId;
        $solicitation = Order::find($requestId);
        $solicitation->delete(); 
    }

}
