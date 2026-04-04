<?php

declare(strict_types=1);

namespace App\Actions\Products;

use App\Models\Product;
use App\Models\ProductVariant;

/**
 * Mantém products.stock como a soma dos estoques das variantes quando o produto possui linhas em product_variants.
 * Produtos sem variantes não são alterados (estoque fica só em products.stock).
 */
final class SyncParentProductStockFromVariantsAction
{
    public function __invoke(int $productId): void
    {
        if (! ProductVariant::query()->where('product_id', $productId)->exists()) {
            return;
        }

        $total = (int) ProductVariant::query()
            ->where('product_id', $productId)
            ->sum('stock');

        Product::query()->whereKey($productId)->update(['stock' => $total]);
    }
}
