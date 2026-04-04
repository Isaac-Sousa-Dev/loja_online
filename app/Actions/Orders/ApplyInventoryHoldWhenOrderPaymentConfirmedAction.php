<?php

declare(strict_types=1);

namespace App\Actions\Orders;

use App\Models\Order;

/**
 * Na confirmação do pagamento (pedido → confirmed + paid), retira do estoque físico
 * as quantidades das linhas (produto ou variante cor/tamanho), marcando o pedido para
 * não haver segunda baixa na conclusão.
 */
final class ApplyInventoryHoldWhenOrderPaymentConfirmedAction
{
    public function __construct(
        private readonly DecrementStockForOrderLineItemsAction $decrementStockForOrderLineItems,
    ) {}

    public function __invoke(Order $order): void
    {
        if ($order->inventory_hold_applied_at !== null) {
            return;
        }

        ($this->decrementStockForOrderLineItems)($order, allowOversell: false);
        $order->forceFill(['inventory_hold_applied_at' => now()])->save();
    }
}
