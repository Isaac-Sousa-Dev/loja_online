<?php

declare(strict_types=1);

namespace App\Services\Wholesale;

use App\Enums\WholesaleCountMode;
use App\Models\Product;
use App\Models\Store;
use App\Models\StoreWholesaleLevel;
use Illuminate\Support\Collection;

class WholesalePriceResolver
{
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
    public function resolveForQuantities(
        Product $product,
        ?Store $store,
        int $productQuantity,
        int $cartQuantity,
    ): array {
        $retailPrice = number_format((float) $product->price, 2, '.', '');
        $countMode = $this->resolveCountMode($store);
        $comparisonQuantity = $countMode === WholesaleCountMode::CART ? $cartQuantity : $productQuantity;
        $level = $this->resolveEligibleLevel($store, $comparisonQuantity);

        if ($level === null) {
            return [
                'unit_price' => $retailPrice,
                'pricing_mode' => 'retail',
                'store_wholesale_level_id' => null,
                'wholesale_level_label' => null,
                'wholesale_level_position' => null,
                'wholesale_applied_mode' => null,
            ];
        }

        $resolvedWholesalePrice = $this->resolveWholesalePriceForLevel($product, $level);

        return [
            'unit_price' => $resolvedWholesalePrice ?? $retailPrice,
            'pricing_mode' => 'wholesale',
            'store_wholesale_level_id' => $level['id'],
            'wholesale_level_label' => $level['label'],
            'wholesale_level_position' => $level['position'],
            'wholesale_applied_mode' => $countMode->value,
        ];
    }

    public function resolveCountMode(?Store $store): WholesaleCountMode
    {
        $countMode = $store?->wholesale_count_mode;

        return $countMode instanceof WholesaleCountMode
            ? $countMode
            : WholesaleCountMode::CART;
    }

    /**
     * @return array{id:?int,label:string,position:int,min_quantity:int}|null
     */
    public function resolveEligibleLevel(?Store $store, int $comparisonQuantity): ?array
    {
        if ($comparisonQuantity < 1) {
            return null;
        }

        $eligible = collect($this->levelsForStore($store))
            ->filter(static fn (array $level): bool => $comparisonQuantity >= $level['min_quantity'])
            ->sortByDesc('position')
            ->first();

        return is_array($eligible) ? $eligible : null;
    }

    /**
     * @return array<int, array{id:?int,label:string,position:int,min_quantity:int}>
     */
    public function levelsForStore(?Store $store): array
    {
        if ($store === null) {
            return [];
        }

        $levels = $store->relationLoaded('wholesaleLevels')
            ? $store->wholesaleLevels
            : $store->wholesaleLevels()->get();

        if ($levels->isNotEmpty()) {
            return $levels->map(static function (StoreWholesaleLevel $level): array {
                return [
                    'id' => (int) $level->id,
                    'label' => (string) $level->label,
                    'position' => (int) $level->position,
                    'min_quantity' => (int) $level->min_quantity,
                ];
            })->all();
        }

        $legacyMinimumQuantity = (int) ($store->wholesale_min_quantity ?? 0);
        if ($legacyMinimumQuantity < 2) {
            return [];
        }

        return [[
            'id' => null,
            'label' => 'Atacado 1',
            'position' => 1,
            'min_quantity' => $legacyMinimumQuantity,
        ]];
    }

    /**
     * @param  array{id:?int,label:string,position:int,min_quantity:int}  $level
     */
    public function resolveWholesalePriceForLevel(Product $product, array $level): ?string
    {
        $levelId = $level['id'];
        if ($levelId !== null) {
            $wholesalePrices = $product->relationLoaded('wholesalePrices')
                ? $product->wholesalePrices
                : $product->wholesalePrices()->get();

            $row = $wholesalePrices->firstWhere('store_wholesale_level_id', $levelId);
            if ($row !== null && $row->price !== null && (float) $row->price > 0) {
                return number_format((float) $row->price, 2, '.', '');
            }
        }

        if ((int) $level['position'] === 1 && (float) ($product->price_wholesale ?? 0) > 0) {
            return number_format((float) $product->price_wholesale, 2, '.', '');
        }

        return null;
    }

    /**
     * @return array<int, array{id:int,label:string,position:int,min_quantity:int,price:?string}>
     */
    public function levelsWithProductPrices(Product $product, ?Store $store): array
    {
        return collect($this->levelsForStore($store))
            ->map(function (array $level) use ($product): array {
                return [
                    'id' => (int) ($level['id'] ?? 0),
                    'label' => $level['label'],
                    'position' => $level['position'],
                    'min_quantity' => $level['min_quantity'],
                    'price' => $this->resolveWholesalePriceForLevel($product, $level),
                ];
            })
            ->filter(static fn (array $level): bool => $level['id'] > 0 || $level['price'] !== null)
            ->values()
            ->all();
    }
}
