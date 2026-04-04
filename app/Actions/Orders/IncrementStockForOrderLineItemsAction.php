<?php

declare(strict_types=1);

namespace App\Actions\Orders;

use App\Actions\Products\SyncParentProductStockFromVariantsAction;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductVariant;

/**
 * Devolve ao estoque as quantidades das linhas do pedido (espelho da baixa na reserva).
 */
final class IncrementStockForOrderLineItemsAction
{
    public function __construct(
        private readonly SyncParentProductStockFromVariantsAction $syncParentProductStockFromVariants,
    ) {}

    public function __invoke(Order $order): void
    {
        $order->loadMissing('items');

        foreach ($order->items as $item) {
            if ($item->product_variant_id !== null) {
                $this->incrementVariant((int) $item->product_variant_id, (int) $item->quantity);

                continue;
            }

            $this->incrementProduct((int) $item->product_id, (int) $item->quantity);
        }
    }

    private function incrementVariant(int $variantId, int $quantity): void
    {
        $variant = ProductVariant::query()->whereKey($variantId)->lockForUpdate()->first();
        if ($variant === null) {
            return;
        }

        $variant->stock = (int) $variant->stock + $quantity;
        $variant->save();
        ($this->syncParentProductStockFromVariants)((int) $variant->product_id);
    }

    private function incrementProduct(int $productId, int $quantity): void
    {
        $product = Product::query()->whereKey($productId)->lockForUpdate()->first();
        if ($product === null) {
            return;
        }

        $current = $product->stock !== null ? (int) $product->stock : 0;
        $product->stock = $current + $quantity;
        $product->save();
    }
}
