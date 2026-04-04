<?php

declare(strict_types=1);

namespace App\Actions\Orders;

use App\Actions\Cache\ForgetStoreDashboardMetricsAction;
use App\Actions\Cache\InvalidateStoreCatalogAndDashboardCachesAction;
use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\User;
use App\Services\Sale\SyncSaleFromOrdersService;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

final class UpdateOrderStatusAction
{
    public function __construct(
        private readonly RecordOrderStatusHistoryAction $recordHistory,
        private readonly DecrementStockForOrderLineItemsAction $decrementStockForOrderLineItems,
        private readonly ApplyInventoryHoldWhenOrderPaymentConfirmedAction $applyInventoryHoldWhenOrderPaymentConfirmed,
        private readonly ReleaseInventoryHoldWhenOrderCancelledAction $releaseInventoryHoldWhenOrderCancelled,
        private readonly SyncSaleFromOrdersService $syncSaleFromOrders,
        private readonly ForgetStoreDashboardMetricsAction $forgetStoreDashboardMetrics,
        private readonly InvalidateStoreCatalogAndDashboardCachesAction $invalidateStoreCatalogAndDashboardCaches,
    ) {}

    public function confirm(Order $order, ?User $actor = null): void
    {
        if ($order->status !== OrderStatus::PENDING) {
            throw new InvalidArgumentException('Apenas pedidos pendentes podem ser confirmados.');
        }
        $this->apply($order, OrderStatus::CONFIRMED, $actor);
    }

    public function cancel(Order $order, ?User $actor = null, ?string $note = null): void
    {
        if (! $order->status->canCancel()) {
            throw new InvalidArgumentException('Este pedido não pode ser cancelado no status atual.');
        }
        $this->apply($order, OrderStatus::CANCELLED, $actor, $note);
    }

    /**
     * Avança um passo no fluxo operacional (pagamento confirmado → separação → pronto para entrega → concluído).
     */
    public function advance(Order $order, ?User $actor = null): void
    {
        $store = $order->store;
        if ($store === null) {
            throw new InvalidArgumentException('Loja não encontrada.');
        }
        $next = $order->status->nextOperational($store);
        if ($next === null) {
            throw new InvalidArgumentException('Não há próximo passo para este pedido.');
        }
        if ($next === OrderStatus::COMPLETED) {
            $this->completeWithStock($order, $actor);

            return;
        }
        $this->apply($order, $next, $actor);
    }

    /**
     * Conclui a venda no sistema: pedidos já com reserva de estoque na confirmação do pagamento
     * não recebem nova baixa; pedidos legados sem reserva mantêm a baixa única aqui.
     */
    public function completeWithStock(Order $order, ?User $actor = null): void
    {
        if ($order->status === OrderStatus::COMPLETED) {
            return;
        }
        if ($order->status === OrderStatus::CANCELLED) {
            throw new InvalidArgumentException('Pedido cancelado não pode ser concluído.');
        }
        if ($order->status === OrderStatus::PENDING) {
            throw new InvalidArgumentException('Confirme o pedido antes de concluir a venda.');
        }
        if ($order->status !== OrderStatus::DELIVERED) {
            throw new InvalidArgumentException('O pedido precisa estar pronto para entrega antes da conclusão.');
        }

        $from = $order->status->value;
        $storeId = (int) $order->store_id;
        DB::transaction(function () use ($order, $actor, $from): void {
            $locked = Order::query()->whereKey($order->id)->lockForUpdate()->first();
            if ($locked === null) {
                return;
            }
            if ($locked->inventory_hold_applied_at === null) {
                ($this->decrementStockForOrderLineItems)($locked, allowOversell: true);
            }
            $locked->status = OrderStatus::COMPLETED;
            $locked->completed_at = now();
            $locked->payment_status = 'paid';
            $locked->save();
            ($this->recordHistory)($locked, $from, OrderStatus::COMPLETED->value, $actor?->id, null);
            $this->syncSaleFromOrders->syncForOrder($locked->fresh());
        });

        $this->invalidateStoreCatalogAndDashboardCaches->execute($storeId);
    }

    public function bulkConfirm(iterable $orders, ?User $actor = null): int
    {
        $n = 0;
        foreach ($orders as $order) {
            if (! $order instanceof Order) {
                continue;
            }
            if ($order->status !== OrderStatus::PENDING) {
                continue;
            }
            $this->apply($order, OrderStatus::CONFIRMED, $actor);
            ++$n;
        }

        return $n;
    }

    public function bulkCancel(iterable $orders, ?User $actor = null): int
    {
        $n = 0;
        foreach ($orders as $order) {
            if (! $order instanceof Order) {
                continue;
            }
            if (! $order->status->canCancel()) {
                continue;
            }
            $this->apply($order, OrderStatus::CANCELLED, $actor);
            ++$n;
        }

        return $n;
    }

    private function apply(Order $order, OrderStatus $to, ?User $actor = null, ?string $note = null): void
    {
        if ($order->status === $to) {
            return;
        }

        $storeId = (int) $order->store_id;

        if ($to === OrderStatus::CONFIRMED) {
            $this->applyPaymentConfirmedTransition($order, $actor, $note);
            $this->afterOrderMutation($order, $storeId, invalidateCatalog: true);

            return;
        }

        if ($to === OrderStatus::CANCELLED) {
            $this->applyCancelledTransition($order, $actor, $note);
            $this->afterOrderMutation($order, $storeId, invalidateCatalog: true);

            return;
        }

        $from = $order->status->value;
        $order->status = $to;
        if ($to === OrderStatus::COMPLETED) {
            $order->completed_at = now();
        }
        $order->save();
        ($this->recordHistory)($order, $from, $to->value, $actor?->id, $note);
        $this->afterOrderMutation($order, $storeId);
    }

    private function applyPaymentConfirmedTransition(Order $order, ?User $actor, ?string $note): void
    {
        DB::transaction(function () use ($order, $actor, $note): void {
            $locked = Order::query()->whereKey($order->id)->lockForUpdate()->first();
            if ($locked === null || $locked->status === OrderStatus::CONFIRMED) {
                return;
            }
            if ($locked->status !== OrderStatus::PENDING) {
                throw new InvalidArgumentException('Apenas pedidos pendentes podem ser confirmados.');
            }

            ($this->applyInventoryHoldWhenOrderPaymentConfirmed)($locked);

            $from = $locked->status->value;
            $locked->status = OrderStatus::CONFIRMED;
            $locked->payment_status = 'paid';
            $locked->save();
            ($this->recordHistory)($locked, $from, OrderStatus::CONFIRMED->value, $actor?->id, $note);
        });
    }

    private function applyCancelledTransition(Order $order, ?User $actor, ?string $note): void
    {
        DB::transaction(function () use ($order, $actor, $note): void {
            $locked = Order::query()->whereKey($order->id)->lockForUpdate()->first();
            if ($locked === null || $locked->status === OrderStatus::CANCELLED) {
                return;
            }
            if (! $locked->status->canCancel()) {
                throw new InvalidArgumentException('Este pedido não pode ser cancelado no status atual.');
            }

            ($this->releaseInventoryHoldWhenOrderCancelled)($locked);

            $from = $locked->status->value;
            $locked->status = OrderStatus::CANCELLED;
            $locked->save();
            ($this->recordHistory)($locked, $from, OrderStatus::CANCELLED->value, $actor?->id, $note);
        });
    }

    private function afterOrderMutation(Order $order, int $storeId, bool $invalidateCatalog = false): void
    {
        $this->syncSaleFromOrders->syncForOrder($order->fresh());
        $this->forgetStoreDashboardMetrics->execute($storeId);
        if ($invalidateCatalog) {
            $this->invalidateStoreCatalogAndDashboardCaches->execute($storeId);
        }
    }
}
