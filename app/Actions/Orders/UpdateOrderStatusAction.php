<?php

declare(strict_types=1);

namespace App\Actions\Orders;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\User;
use App\Services\Sale\SyncSaleFromOrdersService;
use InvalidArgumentException;

final class UpdateOrderStatusAction
{
    public function __construct(
        private readonly RecordOrderStatusHistoryAction $recordHistory,
        private readonly DebitOrderStockAction $debitStock,
        private readonly SyncSaleFromOrdersService $syncSaleFromOrders,
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
     * Conclui venda: baixa estoque e marca como concluído após ficar pronto para entrega.
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
        \Illuminate\Support\Facades\DB::transaction(function () use ($order, $actor, $from): void {
            $this->debitStock->__invoke($order);
            $order->status = OrderStatus::COMPLETED;
            $order->completed_at = now();
            $order->payment_status = 'paid';
            $order->save();
            ($this->recordHistory)($order, $from, OrderStatus::COMPLETED->value, $actor?->id, null);
            $this->syncSaleFromOrders->syncForOrder($order->fresh());
        });
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
        $from = $order->status->value;
        if ($order->status === $to) {
            return;
        }
        $order->status = $to;
        if ($to === OrderStatus::CONFIRMED) {
            $order->payment_status = 'paid';
        }
        if ($to === OrderStatus::COMPLETED) {
            $order->completed_at = now();
        }
        $order->save();
        ($this->recordHistory)($order, $from, $to->value, $actor?->id, $note);
        $this->syncSaleFromOrders->syncForOrder($order->fresh());
    }
}
