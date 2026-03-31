<?php

declare(strict_types=1);

namespace App\Actions\Orders;

use App\Models\Order;
use App\Models\Product;
use App\Models\ProductVariant;

final class DebitOrderStockAction
{
    public function __invoke(Order $order): void
    {
        $order->load('items');

        foreach ($order->items as $item) {
            if ($item->product_variant_id !== null) {
                $variant = ProductVariant::query()->whereKey($item->product_variant_id)->lockForUpdate()->first();
                if ($variant !== null) {
                    $variant->stock = max(0, (int) $variant->stock - (int) $item->quantity);
                    $variant->save();
                }
            } else {
                $product = Product::query()->whereKey($item->product_id)->lockForUpdate()->first();
                if ($product !== null) {
                    $product->stock = max(0, (int) $product->stock - (int) $item->quantity);
                    $product->save();
                }
            }
        }
    }
}
