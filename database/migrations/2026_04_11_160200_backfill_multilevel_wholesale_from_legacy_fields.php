<?php

declare(strict_types=1);

use App\Models\Product;
use App\Models\ProductWholesalePrice;
use App\Models\Store;
use App\Models\StoreWholesaleLevel;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::transaction(function (): void {
            Store::query()
                ->whereNotNull('wholesale_min_quantity')
                ->where('wholesale_min_quantity', '>', 0)
                ->chunkById(100, function ($stores): void {
                    foreach ($stores as $store) {
                        $level = StoreWholesaleLevel::query()->firstOrCreate(
                            [
                                'store_id' => $store->id,
                                'position' => 1,
                            ],
                            [
                                'label' => 'Atacado 1',
                                'min_quantity' => (int) $store->wholesale_min_quantity,
                            ]
                        );

                        Product::query()
                            ->where('partner_id', $store->partner_id)
                            ->whereNotNull('price_wholesale')
                            ->where('price_wholesale', '>', 0)
                            ->chunkById(100, function ($products) use ($level): void {
                                foreach ($products as $product) {
                                    ProductWholesalePrice::query()->firstOrCreate(
                                        [
                                            'product_id' => $product->id,
                                            'store_wholesale_level_id' => $level->id,
                                        ],
                                        [
                                            'price' => $product->price_wholesale,
                                        ]
                                    );
                                }
                            });
                    }
                });
        });
    }

    public function down(): void
    {
        ProductWholesalePrice::query()->delete();
        StoreWholesaleLevel::query()->delete();
    }
};
