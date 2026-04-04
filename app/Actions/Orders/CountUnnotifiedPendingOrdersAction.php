<?php

declare(strict_types=1);

namespace App\Actions\Orders;

use App\Enums\OrderStatus;
use App\Models\Order;

final class CountUnnotifiedPendingOrdersAction
{
    public function execute(int $storeId): int
    {
        return Order::query()
            ->where('store_id', $storeId)
            ->where('status', OrderStatus::PENDING->value)
            ->whereNull('notified_at')
            ->count();
    }
}
