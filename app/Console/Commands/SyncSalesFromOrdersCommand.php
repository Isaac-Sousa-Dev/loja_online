<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\Store;
use App\Services\Sale\SyncSaleFromOrdersService;
use Illuminate\Console\Command;

class SyncSalesFromOrdersCommand extends Command
{
    protected $signature = 'sales:sync-from-orders {store_id? : ID da loja (opcional)}';

    protected $description = 'Regenera registros de vendas a partir de pedidos confirmados e concluídos';

    public function handle(SyncSaleFromOrdersService $sync): int
    {
        $storeIdArg = $this->argument('store_id');

        $stores = $storeIdArg !== null
            ? Store::query()->where('id', (int) $storeIdArg)->get()
            : Store::query()->get();

        if ($stores->isEmpty()) {
            $this->error('Nenhuma loja encontrada.');

            return self::FAILURE;
        }

        foreach ($stores as $store) {
            $anchors = Order::query()
                ->where('store_id', $store->id)
                ->whereIn('status', [
                    OrderStatus::CONFIRMED->value,
                    OrderStatus::SEPARATING->value,
                    OrderStatus::DELIVERED->value,
                    OrderStatus::COMPLETED->value,
                ])
                ->get()
                ->unique(function (Order $order) use ($sync): string {
                    return $sync->groupKey($order);
                })
                ->values();

            foreach ($anchors as $anchor) {
                $sync->syncForOrder($anchor);
            }

            $this->info(sprintf('Loja #%d: %d grupo(s) sincronizado(s).', $store->id, $anchors->count()));
        }

        return self::SUCCESS;
    }
}
