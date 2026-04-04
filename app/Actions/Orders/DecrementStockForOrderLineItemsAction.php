<?php

declare(strict_types=1);

namespace App\Actions\Orders;

use App\Actions\Products\SyncParentProductStockFromVariantsAction;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductVariant;
use InvalidArgumentException;

/**
 * Reduz estoque em produto ou variante conforme cada linha do pedido.
 *
 * @see ReleaseInventoryHoldWhenOrderCancelledAction
 */
final class DecrementStockForOrderLineItemsAction
{
    public function __construct(
        private readonly SyncParentProductStockFromVariantsAction $syncParentProductStockFromVariants,
    ) {}

    /**
     * @param bool $allowOversell Quando false, falha se não houver quantidade disponível; quando true, o saldo não fica negativo (legado na conclusão sem reserva prévia).
     */
    public function __invoke(Order $order, bool $allowOversell = false): void
    {
        $order->loadMissing('items');

        foreach ($order->items as $item) {
            if ($item->product_variant_id !== null) {
                $this->decrementVariant((int) $item->product_variant_id, (int) $item->quantity, $allowOversell);

                continue;
            }

            $this->decrementProduct((int) $item->product_id, (int) $item->quantity, $allowOversell);
        }
    }

    private function decrementVariant(int $variantId, int $quantity, bool $allowOversell): void
    {
        $variant = ProductVariant::query()->whereKey($variantId)->lockForUpdate()->first();
        if ($variant === null) {
            return;
        }

        $current = (int) $variant->stock;
        if (! $allowOversell && $current < $quantity) {
            throw new InvalidArgumentException(
                'Estoque insuficiente para confirmar o pagamento deste pedido (variação do produto).'
            );
        }

        $variant->stock = $allowOversell ? max(0, $current - $quantity) : $current - $quantity;
        $variant->save();
        ($this->syncParentProductStockFromVariants)((int) $variant->product_id);
    }

    private function decrementProduct(int $productId, int $quantity, bool $allowOversell): void
    {
        $product = Product::query()->whereKey($productId)->lockForUpdate()->first();
        if ($product === null) {
            return;
        }

        $current = (int) ($product->stock ?? 0);
        if (! $allowOversell && $current < $quantity) {
            throw new InvalidArgumentException(
                'Estoque insuficiente para confirmar o pagamento deste pedido.'
            );
        }

        $newStock = $allowOversell ? max(0, $current - $quantity) : $current - $quantity;
        $product->stock = $newStock;
        $product->save();
    }
}
