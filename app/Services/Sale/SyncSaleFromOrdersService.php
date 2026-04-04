<?php

declare(strict_types=1);

namespace App\Services\Sale;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Sale;

final class SyncSaleFromOrdersService
{
    public function syncForOrder(Order $anchor): void
    {
        $order = Order::query()
            ->with(['items.product'])
            ->find($anchor->id);

        if ($order === null) {
            return;
        }

        $groupKey = $this->groupKey($order);
        if (! in_array($order->status, [
            OrderStatus::CONFIRMED,
            OrderStatus::SEPARATING,
            OrderStatus::DELIVERED,
            OrderStatus::COMPLETED,
        ], true)) {
            $this->deleteByGroupKey((int) $order->store_id, $groupKey);

            return;
        }

        $itemsSummary = $order->items
            ->map(fn (OrderItem $item): string => $this->lineSummary($item))
            ->implode(' · ');

        if (mb_strlen($itemsSummary) > 500) {
            $itemsSummary = mb_substr($itemsSummary, 0, 497).'...';
        }

        $firstItem = $order->items->first();
        if ($firstItem === null) {
            $this->deleteByGroupKey((int) $order->store_id, $groupKey);

            return;
        }

        $saleStatus = $order->status === OrderStatus::COMPLETED ? 'completed' : 'confirmed';

        Sale::query()->updateOrCreate(
            [
                'store_id' => (int) $order->store_id,
                'order_ref' => $groupKey,
            ],
            [
                'client_id' => (int) $order->client_id,
                'seller_id' => $order->seller_id ?? $order->assigned_to,
                'product_id' => (int) $firstItem->product_id,
                'total_amount' => (float) $order->total,
                'subtotal' => (float) $order->subtotal,
                'shipping_amount' => (float) $order->shipping_amount,
                'discount' => (float) $order->discount_amount,
                'items_count' => $order->items->count(),
                'items_summary' => $itemsSummary,
                'sale_status' => $saleStatus,
                'status' => $saleStatus,
                'payment_method' => $order->payment_method ?? 'cash',
                'type' => 2,
                'observations' => $order->message,
                'delivery_date' => $order->completed_at?->toDateString(),
            ]
        );
    }

    public function syncAfterOrderRemoved(int $storeId, ?string $orderRef, int $removedId): void
    {
        $groupKey = $orderRef !== null && $orderRef !== ''
            ? $orderRef
            : 'single_'.$removedId;

        $this->deleteByGroupKey($storeId, $groupKey);
    }

    public function groupKey(Order $order): string
    {
        $code = trim((string) $order->code);

        return $code !== '' ? $code : 'single_'.$order->id;
    }

    private function deleteByGroupKey(int $storeId, string $groupKey): void
    {
        Sale::query()
            ->where('store_id', $storeId)
            ->where('order_ref', $groupKey)
            ->delete();
    }

    private function lineSummary(OrderItem $item): string
    {
        $label = $item->product?->name ?? 'Produto';
        $variation = $item->variationSummary();
        $suffix = $variation !== '—' ? ' ('.$variation.')' : '';

        return max(1, (int) $item->quantity).'× '.$label.$suffix;
    }
}
