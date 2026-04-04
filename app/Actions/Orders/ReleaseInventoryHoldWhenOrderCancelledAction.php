<?php

declare(strict_types=1);

namespace App\Actions\Orders;

use App\Models\Order;

/**
 * Quando um pedido com reserva de estoque é cancelado (ex.: reembolso), devolve as quantidades.
 */
final class ReleaseInventoryHoldWhenOrderCancelledAction
{
    public function __construct(
        private readonly IncrementStockForOrderLineItemsAction $incrementStockForOrderLineItems,
    ) {}

    public function __invoke(Order $order): void
    {
        if ($order->inventory_hold_applied_at === null) {
            return;
        }

        ($this->incrementStockForOrderLineItems)($order);
        $order->forceFill(['inventory_hold_applied_at' => null])->save();
    }
}
