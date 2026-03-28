<?php

declare(strict_types=1);

namespace App\Services\product;

use App\Models\Product;
use App\Models\ProductVariant;

class ProductVariantSyncService
{
    public function sync(int $productId, array $variants): void
    {
        ProductVariant::where('product_id', $productId)->delete();

        foreach ($variants as $v) {
            ProductVariant::create([
                'product_id'     => $productId,
                'color'          => $v['color'] ?? null,
                'color_hex'      => $this->colorToHex((string) ($v['color'] ?? '')),
                'size'           => $v['size'] ?? null,
                'stock'          => max(0, (int) ($v['stock'] ?? 0)),
                'price_override' => isset($v['price']) && $v['price'] !== '' ? (float) $v['price'] : null,
                'sku'            => $v['sku'] ?? null,
                'active'         => true,
            ]);
        }

        $totalStock = ProductVariant::where('product_id', $productId)->sum('stock');
        Product::where('id', $productId)->update(['stock' => $totalStock]);
    }

    private function colorToHex(string $color): string
    {
        $map = [
            'preto' => '#1a1a1a', 'negro' => '#1a1a1a', 'branco' => '#ffffff', 'azul' => '#2563eb',
            'vermelho' => '#dc2626', 'verde' => '#16a34a', 'amarelo' => '#eab308', 'rosa' => '#ec4899',
            'cinza' => '#6b7280', 'laranja' => '#f97316', 'roxo' => '#7c3aed', 'marrom' => '#92400e',
            'bege' => '#d4b896', 'vinho' => '#7f1d1d', 'navy' => '#1e3a5f', 'azul marinho' => '#1e3a5f',
            'turquesa' => '#0891b2', 'dourado' => '#d97706', 'prata' => '#9ca3af',
        ];

        return $map[strtolower(trim($color))] ?? '#94a3b8';
    }
}
