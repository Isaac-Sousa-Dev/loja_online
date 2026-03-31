<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\Orders\UpdateOrderStatusAction;
use App\Enums\FulfillmentType;
use App\Enums\OrderStatus;
use App\Http\Requests\BulkOrdersRequest;
use App\Http\Requests\StoreCartRequest;
use App\Http\Resources\OrderResource;
use App\Models\Client;
use App\Models\ClientStore;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\RequestPlan;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use InvalidArgumentException;
use Symfony\Component\HttpFoundation\StreamedResponse;

class OrderController extends Controller
{
    public function __construct(
        private readonly UpdateOrderStatusAction $updateOrderStatus,
    ) {}

    public function index(Request $request): View|Response
    {
        $userAuth = Auth::user();
        if ($userAuth === null) {
            abort(403);
        }

        if ($userAuth->role === 'admin') {
            $requestsPlans = RequestPlan::query()->orderByDesc('created_at')->get();

            return view('admin.requests.index', compact('requestsPlans'));
        }

        $this->authorize('viewAny', Order::class);

        $store = $userAuth->partner->store;
        if ($store === null) {
            abort(403);
        }

        if ($request->boolean('ack')) {
            Order::query()
                ->where('store_id', $store->id)
                ->where('status', OrderStatus::PENDING)
                ->whereNull('notified_at')
                ->update(['notified_at' => now()]);
        }

        $query = $this->filteredOrdersQuery($request, $store->id);
        $orders = $query->paginate(15)->withQueryString();

        $drawerData = $orders->getCollection()->mapWithKeys(fn (Order $o) => [
            $o->id => $this->drawerPayload($o),
        ])->all();

        if ($request->ajax()) {
            return response()->view('partner.orders._table', [
                'orders' => $orders,
                'store' => $store,
                'drawerData' => $drawerData,
            ]);
        }

        return view('partner.orders.index', [
            'orders' => $orders,
            'store' => $store,
            'drawerData' => $drawerData,
        ]);
    }

    public function pendingJson(Request $request): JsonResponse
    {
        $user = $request->user();
        if ($user === null || $user->role === 'admin' || $user->partner === null) {
            return response()->json(['data' => []], 403);
        }

        $store = $user->partner->store;
        if ($store === null) {
            return response()->json(['data' => []], 403);
        }

        $orders = Order::query()
            ->where('store_id', $store->id)
            ->with(['client', 'items.product'])
            ->where('status', OrderStatus::PENDING)
            ->latest()
            ->limit(50)
            ->get();

        return OrderResource::collection($orders)->response();
    }

    public function bulkAction(BulkOrdersRequest $request): JsonResponse
    {
        $user = $request->user();
        $store = $user?->partner?->store;
        if ($store === null) {
            return response()->json(['message' => 'Loja não encontrada.'], 403);
        }

        $ids = $request->input('ids', []);
        $orders = Order::query()
            ->where('store_id', $store->id)
            ->whereIn('id', $ids)
            ->get();

        foreach ($orders as $order) {
            $this->authorize('update', $order);
        }

        $action = $request->string('action')->toString();
        try {
            if ($action === 'confirm') {
                $n = $this->updateOrderStatus->bulkConfirm($orders, $user);
            } else {
                $n = $this->updateOrderStatus->bulkCancel($orders, $user);
            }
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return response()->json(['ok' => true, 'affected' => $n]);
    }

    public function export(Request $request): StreamedResponse|JsonResponse
    {
        $user = $request->user();
        if ($user === null || $user->role === 'admin' || $user->partner === null) {
            return response()->json(['message' => 'Não autorizado.'], 403);
        }

        $store = $user->partner->store;
        if ($store === null) {
            return response()->json(['message' => 'Loja não encontrada.'], 403);
        }

        $count = $this->filteredOrdersQuery($request, $store->id)->count();
        if ($count > 500) {
            return response()->json([
                'message' => 'Mais de 500 registros: exportação em fila ainda não configurada. Refine os filtros.',
            ], 422);
        }

        $orders = $this->filteredOrdersQuery($request, $store->id)
            ->with(['client', 'items.product'])
            ->orderByDesc('created_at')
            ->get();

        $filename = 'pedidos-'.now()->format('Y-m-d-His').'.csv';

        return response()->streamDownload(function () use ($orders): void {
            $out = fopen('php://output', 'w');
            if ($out === false) {
                return;
            }
            fputcsv($out, [
                'Código', 'Data', 'Cliente', 'Documento', 'Itens', 'Qtd total', 'Subtotal', 'Frete', 'Desconto', 'Total',
                'Pagamento', 'Status', 'Tipo', 'Concluído em',
            ], ';');
            foreach ($orders as $order) {
                $qty = $order->items->sum('quantity');
                fputcsv($out, [
                    $order->code,
                    $order->created_at?->format('d/m/Y H:i'),
                    $order->client?->name,
                    '',
                    $order->itemsVariationSummary(),
                    $qty,
                    number_format((float) $order->subtotal, 2, ',', '.'),
                    number_format((float) $order->shipping_amount, 2, ',', '.'),
                    number_format((float) $order->discount_amount, 2, ',', '.'),
                    number_format((float) $order->total, 2, ',', '.'),
                    $order->payment_method ?? '',
                    $order->status->label(),
                    $order->fulfillment_type->label(),
                    $order->completed_at?->format('d/m/Y H:i') ?? '',
                ], ';');
            }
            fclose($out);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    public function confirm(Request $request, Order $order): JsonResponse
    {
        $this->authorize('update', $order);
        try {
            $this->updateOrderStatus->confirm($order, $request->user());
        } catch (InvalidArgumentException $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return response()->json(['ok' => true]);
    }

    public function advance(Request $request, Order $order): JsonResponse
    {
        $this->authorize('update', $order);
        try {
            $this->updateOrderStatus->advance($order, $request->user());
        } catch (InvalidArgumentException $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return response()->json(['ok' => true]);
    }

    public function cancel(Request $request, Order $order): JsonResponse
    {
        $this->authorize('update', $order);
        try {
            $this->updateOrderStatus->cancel($order, $request->user(), $request->input('note'));
        } catch (InvalidArgumentException $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return response()->json(['ok' => true]);
    }

    public function complete(Request $request, Order $order): JsonResponse
    {
        $this->authorize('update', $order);
        try {
            $this->updateOrderStatus->completeWithStock($order, $request->user());
        } catch (InvalidArgumentException $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return response()->json(['ok' => true]);
    }

    public function storeCart(StoreCartRequest $request): JsonResponse
    {
        try {
            $validated = $request->validated();
            $items = $validated['items'];
            $storeId = $validated['store_id'];
            $name = $validated['name'];
            $phone = $validated['phone'];
            $message = $validated['message'] ?? '';

            $fakeReq = new Request();
            $fakeReq->merge(['name' => $name, 'phone' => $phone]);

            $client = $this->checkExistClient($phone, $fakeReq);
            $this->checkExistClientStore($storeId, $client);

            $deliveryType = $request->input('delivery_type', 'pickup');
            $fulfillment = $deliveryType === 'delivery' ? FulfillmentType::DELIVERY : FulfillmentType::PICKUP;

            $order = DB::transaction(function () use ($request, $storeId, $client, $items, $message, $fulfillment): Order {
                $order = Order::query()
                    ->where('store_id', $storeId)
                    ->where('client_id', $client->id)
                    ->where('status', OrderStatus::PENDING)
                    ->lockForUpdate()
                    ->orderByDesc('id')
                    ->first();

                if ($order === null) {
                    $order = Order::query()->create([
                        'code' => Order::generateUniqueCode(),
                        'store_id' => $storeId,
                        'client_id' => $client->id,
                        'status' => OrderStatus::PENDING,
                        'fulfillment_type' => $fulfillment,
                        'subtotal' => 0,
                        'shipping_amount' => 0,
                        'discount_amount' => 0,
                        'total' => 0,
                        'payment_method' => $request->input('payment_method'),
                        'payment_installments' => max(1, (int) $request->input('payment_installments', 1)),
                        'payment_status' => 'pending',
                        'message' => $message,
                        'shift' => false,
                        'finance' => false,
                    ]);
                }

                $order->fulfillment_type = $fulfillment;
                $order->payment_method = $request->input('payment_method', $order->payment_method);
                $order->message = $message !== '' ? $message : $order->message;
                $order->save();

                foreach ($items as $item) {
                    $productId = (int) $item['product_id'];
                    $variantId = isset($item['variant_id']) ? (int) $item['variant_id'] : null;
                    $quantity = max(1, (int) ($item['quantity'] ?? 1));

                    $product = Product::query()->whereKey($productId)->firstOrFail();
                    $unitPrice = (string) $product->price;

                    $lineQuery = $order->items()->where('product_id', $productId);
                    if ($variantId !== null) {
                        $lineQuery->where('product_variant_id', $variantId);
                    } else {
                        $lineQuery->whereNull('product_variant_id')
                            ->where('selected_color', $item['color'] ?? null)
                            ->where('selected_size', $item['size'] ?? null);
                    }

                    $line = $lineQuery->first();
                    if ($line !== null) {
                        $line->quantity = (int) $line->quantity + $quantity;
                        $line->unit_price = $unitPrice;
                        $line->recalcLineSubtotal();
                        $line->save();
                    } else {
                        OrderItem::query()->create([
                            'order_id' => $order->id,
                            'store_id' => $order->store_id,
                            'client_id' => $order->client_id,
                            'product_id' => $productId,
                            'product_variant_id' => $variantId ?: null,
                            'selected_color' => $item['color'] ?? null,
                            'selected_size' => $item['size'] ?? null,
                            'quantity' => $quantity,
                            'unit_price' => $unitPrice,
                            'line_subtotal' => bcmul($unitPrice, (string) $quantity, 2),
                        ]);
                    }
                }

                $order->recalculateTotals();

                return $order->fresh(['items']);
            });

            return response()->json([
                'success' => true,
                'order_ref' => $order->code,
                'order_id' => $order->id,
                'created' => $order->items->pluck('id')->all(),
            ], 201);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function init(Request $request): JsonResponse
    {
        $orderId = (int) ($request->input('orderId') ?? $request->input('requestId'));
        $order = Order::query()->findOrFail($orderId);
        $this->authorize('update', $order);
        try {
            $this->updateOrderStatus->confirm($order, $request->user());
        } catch (InvalidArgumentException $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return response()->json(['ok' => true]);
    }

    public function sold(Request $request): JsonResponse
    {
        $orderId = (int) ($request->input('orderId') ?? $request->input('requestId'));
        $order = Order::query()->findOrFail($orderId);
        $this->authorize('update', $order);
        try {
            $this->updateOrderStatus->completeWithStock($order, $request->user());
        } catch (InvalidArgumentException $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return response()->json(['ok' => true]);
    }

    public function unsold(Request $request): JsonResponse
    {
        $orderId = (int) ($request->input('orderId') ?? $request->input('requestId'));
        $order = Order::query()->findOrFail($orderId);
        $this->authorize('update', $order);
        try {
            $this->updateOrderStatus->cancel($order, $request->user());
        } catch (InvalidArgumentException $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return response()->json(['ok' => true]);
    }

    private function filteredOrdersQuery(Request $request, int $storeId)
    {
        $query = Order::query()
            ->where('store_id', $storeId)
            ->with(['client', 'items.product.brand', 'items.variant', 'items.product.images']);

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->input('date_from'));
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->input('date_to'));
        }

        $clientSearch = trim((string) $request->input('client', ''));
        if ($clientSearch !== '') {
            $query->whereHas('client', function ($q) use ($clientSearch): void {
                $q->where('name', 'like', '%'.$clientSearch.'%')
                    ->orWhere('phone', 'like', '%'.$clientSearch.'%');
            });
        }

        $statuses = $request->input('status', []);
        if (! is_array($statuses)) {
            $statuses = $statuses !== null && $statuses !== '' ? [(string) $statuses] : [];
        }
        if ($statuses !== []) {
            $query->whereIn('status', $statuses);
        }

        $ftype = $request->input('fulfillment_type', 'all');
        if (in_array($ftype, ['delivery', 'pickup'], true)) {
            $query->where('fulfillment_type', $ftype);
        }

        return $query;
    }

    /**
     * @return array<string, mixed>
     */
    private function drawerPayload(Order $order): array
    {
        $order->loadMissing(['client', 'store', 'items.product.brand', 'items.variant', 'items.product.images', 'statusHistories.changedBy']);

        $lines = [];
        foreach ($order->items as $item) {
            $img = '';
            if ($item->product && $item->product->images->isNotEmpty()) {
                $img = asset('storage/'.$item->product->images->first()->url);
            }
            $lines[] = [
                'id' => $item->id,
                'name' => $item->product?->name ?? '—',
                'variation' => $item->variationSummary(),
                'qty' => (int) $item->quantity,
                'unit_price' => (float) $item->unit_price,
                'subtotal' => (float) $item->line_subtotal,
                'image' => $img,
            ];
        }

        $timeline = $order->statusHistories->map(function ($h) {
            $toLabel = OrderStatus::tryFrom((string) $h->to_status)?->label() ?? $h->to_status;

            return [
                'from' => $h->from_status,
                'to' => $h->to_status,
                'to_label' => $toLabel,
                'at' => $h->created_at?->format('d/m/Y H:i'),
                'by' => $h->changedBy?->name ?? 'Sistema',
                'note' => $h->note,
            ];
        })->values()->all();

        return [
            'id' => $order->id,
            'code' => $order->code,
            'status' => $order->status->value,
            'statusLabel' => $order->status->label(),
            'fulfillment' => $order->fulfillment_type->value,
            'fulfillmentLabel' => $order->fulfillment_type->label(),
            'createdAt' => $order->created_at?->format('d/m/Y \à\s H:i'),
            'payment_method' => $order->payment_method ?? '—',
            'payment_installments' => $order->payment_installments ?? 1,
            'payment_status' => $order->payment_status ?? '—',
            'message' => $order->message ?? '',
            'client' => [
                'name' => $order->client?->name ?? '—',
                'phone' => $order->client?->phone ?? '—',
                'email' => $order->client?->email ?? '—',
            ],
            'delivery' => [
                'type' => $order->fulfillment_type->value,
                'lines' => $order->fulfillment_type === FulfillmentType::DELIVERY
                    ? 'Endereço informado no próximo passo com o cliente (campos em evolução no checkout).'
                    : 'Retirada na loja: '.$order->store?->store_name,
            ],
            'lines' => $lines,
            'subtotal' => (float) $order->subtotal,
            'shipping' => (float) $order->shipping_amount,
            'discount' => (float) $order->discount_amount,
            'total' => (float) $order->total,
            'timeline' => $timeline,
            'can_confirm' => $order->status->canConfirmFromPending(),
            'can_advance' => $order->status->nextOperational($order->store) !== null,
            'can_cancel' => $order->status->canCancel(),
            'can_complete_stock' => in_array($order->status, [OrderStatus::CONFIRMED, OrderStatus::SEPARATING, OrderStatus::DELIVERED], true),
        ];
    }

    private function checkExistClient(string $phone, Request $req): Client
    {
        $phoneFormatted = str_replace(['(', ')', '-', ' '], '', $phone);
        $existClient = Client::where('phone', $phoneFormatted)->first();
        if ($existClient === null) {
            return Client::create([
                'name' => $req->name,
                'phone' => $phoneFormatted,
            ]);
        }

        return $existClient;
    }

    private function checkExistClientStore(int $storeId, Client $client): void
    {
        $existClientStore = ClientStore::where('store_id', $storeId)->where('client_id', $client->id)->first();
        if ($existClientStore === null) {
            ClientStore::create([
                'store_id' => $storeId,
                'client_id' => $client->id,
            ]);
        }
    }
}
