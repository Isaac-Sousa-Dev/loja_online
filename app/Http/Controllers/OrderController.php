<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\Cache\ForgetStoreDashboardMetricsAction;
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
use App\Models\Store;
use App\Services\Wholesale\WholesalePriceResolver;
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
        private readonly ForgetStoreDashboardMetricsAction $forgetStoreDashboardMetrics,
        private readonly WholesalePriceResolver $wholesalePriceResolver,
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

        $filtersState = $this->resolveOrdersFiltersState($request);

        $query = $this->filteredOrdersQuery($request, $store->id, $filtersState)
            ->orderByDesc('created_at');
        $orders = $query->paginate(15)->withQueryString();
    
        $drawerData = $orders->getCollection()->mapWithKeys(fn (Order $o) => [
            $o->id => $this->drawerPayload($o),
        ])->all();

        if ($request->ajax()) {
            return response()->view('partner.orders._table', [
                'orders' => $orders,
                'store' => $store,
                'drawerData' => $drawerData,
                'filtersState' => $filtersState,
            ]);
        }
        
        return view('partner.orders.index', [
            'orders' => $orders,
            'store' => $store,
            'drawerData' => $drawerData,
            'filtersState' => $filtersState,
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

        $filtersState = $this->resolveOrdersFiltersState($request);

        $count = $this->filteredOrdersQuery($request, $store->id, $filtersState)->count();
        if ($count > 500) {
            return response()->json([
                'message' => 'Mais de 500 registros: exportação em fila ainda não configurada. Refine os filtros.',
            ], 422);
        }

        $orders = $this->filteredOrdersQuery($request, $store->id, $filtersState)
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

                    $product = Product::query()
                        ->with('wholesalePrices')
                        ->whereKey($productId)
                        ->firstOrFail();

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
                        $line->recalcLineSubtotal();
                        $line->save();
                    } else {
                        OrderItem::query()->create([
                            'order_id' => $order->id,
                            'store_id' => $order->store_id,
                            'client_id' => $order->client_id,
                            'product_id' => $productId,
                            'product_variant_id' => $variantId ?: null,
                            'store_wholesale_level_id' => null,
                            'wholesale_applied_mode' => null,
                            'selected_color' => $item['color'] ?? null,
                            'selected_size' => $item['size'] ?? null,
                            'quantity' => $quantity,
                            'unit_price' => number_format((float) $product->price, 2, '.', ''),
                            'line_subtotal' => bcmul(number_format((float) $product->price, 2, '.', ''), (string) $quantity, 2),
                        ]);
                    }
                }

                $this->repriceOrderItems($order);
                $order->recalculateTotals();
                $order->forceFill(['notified_at' => null])->save();

                return $order->fresh(['items']);
            });

            $this->forgetStoreDashboardMetrics->execute($storeId);

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

    private function filteredOrdersQuery(Request $request, int $storeId, ?array $filtersState = null)
    {
        $filtersState ??= $this->resolveOrdersFiltersState($request);

        $query = Order::query()
            ->where('store_id', $storeId)
            ->with([
                'client',
                'items.product.brand',
                'items.variant',
                'items.product.images',
                'statusHistories.changedBy',
            ]);

        if ($filtersState['date_from'] !== null) {
            $query->whereDate('created_at', '>=', $filtersState['date_from']);
        }
        if ($filtersState['date_to'] !== null) {
            $query->whereDate('created_at', '<=', $filtersState['date_to']);
        }

        $clientSearch = trim((string) $request->input('client', ''));
        if ($clientSearch !== '') {
            $query->whereHas('client', function ($q) use ($clientSearch): void {
                $q->where('name', 'like', '%'.$clientSearch.'%')
                    ->orWhere('phone', 'like', '%'.$clientSearch.'%');
            });
        }

        $statuses = $filtersState['statuses'];
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
        $order->loadMissing(['client', 'store.wholesaleLevels', 'items.product.brand', 'items.product.wholesalePrices', 'items.variant', 'items.wholesaleLevel', 'items.product.images', 'statusHistories.changedBy']);

        $lines = [];
        $pricingModes = [];
        foreach ($order->items as $item) {
            $pricingMode = $this->resolveOrderItemPricingMode($item, $order->store);
            $pricingModes[] = $pricingMode;
            $lines[] = [
                'id' => $item->id,
                'name' => $item->product?->name ?? '—',
                'variation' => $item->variationSummary(),
                'qty' => (int) $item->quantity,
                'unit_price' => (float) $item->unit_price,
                'subtotal' => (float) $item->line_subtotal,
                'image' => $this->resolveOrderItemImage($item),
                'pricing_mode' => $pricingMode,
                'pricingModeLabel' => $this->pricingModeLabel($pricingMode),
                'wholesaleLevelLabel' => $item->wholesaleLevel?->label,
                'wholesaleLevelPosition' => $item->wholesaleLevel?->position,
                'wholesaleAppliedMode' => $item->wholesale_applied_mode,
            ];
        }

        $orderPricingMode = $this->resolveAggregatePricingMode($pricingModes);

        $timeline = $order->statusHistories
            ->sortBy('created_at')
            ->values()
            ->map(function ($h) {
            $toLabel = OrderStatus::tryFrom((string) $h->to_status)?->label() ?? $h->to_status;

            return [
                'from' => $h->from_status,
                'to' => $h->to_status,
                'to_label' => $toLabel,
                'at' => $h->created_at?->format('d/m/Y H:i'),
                'by' => $h->changedBy?->name ?? 'Sistema',
                'note' => $h->note,
            ];
        })->all();

        return [
            'id' => $order->id,
            'code' => $order->code,
            'status' => $order->status->value,
            'statusLabel' => $order->status->label(),
            'fulfillment' => $order->fulfillment_type->value,
            'fulfillmentLabel' => $order->fulfillment_type->label(),
            'pricingMode' => $orderPricingMode,
            'pricingModeLabel' => $this->pricingModeLabel($orderPricingMode),
            'createdAt' => $order->created_at?->format('d/m/Y \à\s H:i'),
            'payment_method' => $order->payment_method ?? '—',
            'payment_installments' => $order->payment_installments ?? 1,
            'payment_status' => $order->payment_status ?? '—',
            'paymentStatusLabel' => $this->formatPaymentStatus($order->payment_status),
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
            'can_complete_stock' => $order->status === OrderStatus::DELIVERED,
        ];
    }

    private function resolveOrderItemImage(OrderItem $item): string
    {
        $product = $item->product;
        if ($product === null || $product->images->isEmpty()) {
            return '';
        }

        $selectedColor = $this->normalizeColorKey($item->variant?->color ?? $item->selected_color);
        $images = $product->images;

        $matchedImage = null;
        if ($selectedColor !== null) {
            $matchedImage = $images->first(function ($image) use ($selectedColor): bool {
                return $this->normalizeColorKey($image->variant_color) === $selectedColor
                    && (bool) $image->is_cover;
            });

            $matchedImage ??= $images->first(function ($image) use ($selectedColor): bool {
                return $this->normalizeColorKey($image->variant_color) === $selectedColor;
            });
        }

        $matchedImage ??= $images->firstWhere('is_cover', true);
        $matchedImage ??= $images->sortBy('index')->first();

        if ($matchedImage === null || $matchedImage->url === null || $matchedImage->url === '') {
            return '';
        }

        return asset('storage/'.$this->normalizeStoredImagePath((string) $matchedImage->url));
    }

    private function normalizeColorKey(?string $color): ?string
    {
        $normalized = trim((string) $color);

        return $normalized === '' ? null : mb_strtolower($normalized);
    }

    private function normalizeStoredImagePath(string $path): string
    {
        $normalized = ltrim($path, '/');

        if (str_starts_with($normalized, 'public/')) {
            $normalized = substr($normalized, strlen('public/'));
        }

        return $normalized;
    }

    private function formatPaymentStatus(?string $status): string
    {
        return match (mb_strtolower(trim((string) $status))) {
            'paid', 'confirmed', 'completed' => 'Pagamento confirmado',
            'pending' => 'Aguardando confirmação',
            'failed' => 'Falhou',
            'canceled', 'cancelled' => 'Cancelado',
            'refunded' => 'Estornado',
            default => '—',
        };
    }

    /**
     * @return array{
     *     period: string,
     *     date_from: ?string,
     *     date_to: ?string,
     *     statuses: array<int, string>,
     *     hasActiveDrawerFilters: bool,
     *     selectedQuickStatus: string
     * }
     */
    private function resolveOrdersFiltersState(Request $request): array
    {
        $statusInput = $request->input('status', []);
        if (! is_array($statusInput)) {
            $statusInput = $statusInput !== null && $statusInput !== '' ? [(string) $statusInput] : [];
        }

        $explicitStatuses = collect($statusInput)
            ->map(static fn ($status): string => (string) $status)
            ->filter(static fn (string $status): bool => OrderStatus::tryFrom($status) !== null)
            ->values()
            ->all();

        $hasExplicitDateRange = $request->filled('date_from') || $request->filled('date_to');
        $hasClientFilter = trim((string) $request->input('client', '')) !== '';
        $hasFulfillmentFilter = in_array($request->input('fulfillment_type', 'all'), ['delivery', 'pickup'], true);
        $quickStatus = $this->normalizeQuickStatus((string) $request->input('quick_status', ''));
        $hasPeriodParam = $request->query->has('period');
        $hasQuickStatusParam = $request->query->has('quick_status');
        $hasUserControlledScope = $hasExplicitDateRange
            || $hasClientFilter
            || $hasFulfillmentFilter
            || $explicitStatuses !== []
            || $hasPeriodParam
            || $hasQuickStatusParam;

        $period = $hasExplicitDateRange
            ? 'custom'
            : $this->normalizeOrdersPeriod((string) $request->input('period', 'today'));

        [$dateFrom, $dateTo] = $hasExplicitDateRange
            ? [
                $request->filled('date_from') ? (string) $request->input('date_from') : null,
                $request->filled('date_to') ? (string) $request->input('date_to') : null,
            ]
            : $this->resolvePeriodDateRange($period);

        $statuses = $explicitStatuses;
        if ($statuses === []) {
            if ($quickStatus !== null && $quickStatus !== 'all') {
                $statuses = [$quickStatus];
            } elseif (! $hasUserControlledScope) {
                $statuses = [OrderStatus::PENDING->value];
            }
        }

        return [
            'period' => $period,
            'date_from' => $dateFrom,
            'date_to' => $dateTo,
            'statuses' => $statuses,
            'hasActiveDrawerFilters' => $hasExplicitDateRange || $hasClientFilter || $hasFulfillmentFilter || $explicitStatuses !== [],
            'selectedQuickStatus' => count($statuses) === 1 ? $statuses[0] : 'all',
        ];
    }

    private function normalizeOrdersPeriod(string $period): string
    {
        return match ($period) {
            '7d', '30d', 'custom' => $period,
            default => 'today',
        };
    }

    /**
     * @return array{0: string, 1: string}
     */
    private function resolvePeriodDateRange(string $period): array
    {
        $today = now()->toDateString();

        return match ($period) {
            '30d' => [now()->subDays(29)->toDateString(), $today],
            '7d' => [now()->subDays(6)->toDateString(), $today],
            default => [$today, $today],
        };
    }

    private function normalizeQuickStatus(string $status): ?string
    {
        $normalized = trim($status);

        if ($normalized === '') {
            return null;
        }

        if ($normalized === 'all') {
            return 'all';
        }

        return OrderStatus::tryFrom($normalized)?->value;
    }

    /**
     * @return array{
     *     unit_price:string,
     *     pricing_mode:string,
     *     store_wholesale_level_id:?int,
     *     wholesale_level_label:?string,
     *     wholesale_level_position:?int,
     *     wholesale_applied_mode:?string
     * }
     */
    private function resolvePricingForOrderItem(Product $product, ?Store $store, int $productQuantity, int $cartQuantity): array
    {
        return $this->wholesalePriceResolver->resolveForQuantities(
            $product,
            $store,
            $productQuantity,
            $cartQuantity,
        );
    }

    private function resolveOrderItemPricingMode(OrderItem $item, ?Store $store): string
    {
        if ($item->store_wholesale_level_id !== null) {
            return 'wholesale';
        }

        $wholesalePrice = (float) ($item->product?->price_wholesale ?? 0);
        $minimumQuantity = (int) ($store?->wholesale_min_quantity ?? 0);
        $matchesWholesalePrice = $wholesalePrice > 0 && abs((float) $item->unit_price - $wholesalePrice) < 0.01;
        if ($minimumQuantity >= 2 && (int) $item->quantity >= $minimumQuantity && $matchesWholesalePrice) {
            return 'wholesale';
        }

        return 'retail';
    }

    private function repriceOrderItems(Order $order): void
    {
        $order->loadMissing(['store.wholesaleLevels', 'items.product.wholesalePrices']);
        $cartQuantity = (int) $order->items->sum('quantity');
        $productQuantities = $order->items
            ->groupBy('product_id')
            ->map(static fn ($items): int => (int) $items->sum('quantity'));

        foreach ($order->items as $item) {
            $product = $item->product;
            if ($product === null) {
                continue;
            }

            $pricing = $this->resolvePricingForOrderItem(
                $product,
                $order->store,
                (int) ($productQuantities[$item->product_id] ?? 0),
                $cartQuantity
            );

            $item->unit_price = $pricing['unit_price'];
            $item->store_wholesale_level_id = $pricing['store_wholesale_level_id'];
            $item->wholesale_applied_mode = $pricing['wholesale_applied_mode'];
            $item->recalcLineSubtotal();
            $item->saveQuietly();
        }
    }

    /**
     * @param  array<int, string>  $pricingModes
     */
    private function resolveAggregatePricingMode(array $pricingModes): string
    {
        $uniqueModes = array_values(array_unique($pricingModes));

        if ($uniqueModes === []) {
            return 'retail';
        }

        if (count($uniqueModes) === 1) {
            return $uniqueModes[0];
        }

        return 'mixed';
    }

    private function pricingModeLabel(string $pricingMode): string
    {
        return match ($pricingMode) {
            'wholesale' => 'Atacado',
            'mixed' => 'Misto',
            default => 'Varejo',
        };
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
