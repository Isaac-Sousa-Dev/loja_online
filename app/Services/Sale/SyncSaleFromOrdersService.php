<?php

declare(strict_types=1);

namespace App\Services\Sale;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\Sale;
use Illuminate\Support\Collection;

final class SyncSaleFromOrdersService
{
    public function syncForOrder(Order $anchor): void
    {
        $storeId = (int) $anchor->store_id;
        $groupKey = $this->groupKey($anchor);

        $orders = $this->ordersInGroup($storeId, $anchor);

        if ($orders->isEmpty()) {
            $this->deleteByGroupKey($storeId, $groupKey);

            return;
        }

        $hasInvalidStatus = $orders->contains(
            static fn (Order $order): bool => ! in_array(
                $order->status,
                [OrderStatus::PAID->value, OrderStatus::SOLD->value],
                true
            )
        );

        if ($hasInvalidStatus) {
            $this->deleteByGroupKey($storeId, $groupKey);

            return;
        }

        $orders->load('product');

        $subtotal = $orders->sum(
            static function (Order $order): float {
                $unit = (float) ($order->product?->price ?? 0);

                return $unit * max(1, (int) $order->quantity);
            }
        );

        $allSold = $orders->every(
            static fn (Order $order): bool => $order->status === OrderStatus::SOLD->value
        );
        $saleStatus = $allSold ? 'completed' : 'confirmed';

        /** @var Order $first */
        $first = $orders->sortBy('id')->first();

        $itemsSummary = $orders->map(function (Order $order): string {
            $name = $order->product?->name ?? 'Produto';
            $bits = [];
            if ($order->selected_size !== null && $order->selected_size !== '') {
                $bits[] = $order->selected_size;
            }
            if ($order->selected_color !== null && $order->selected_color !== '') {
                $bits[] = $order->selected_color;
            }
            $suffix = $bits !== [] ? ' ('.implode(', ', $bits).')' : '';

            return max(1, (int) $order->quantity).'× '.$name.$suffix;
        })->implode(' · ');

        if (mb_strlen($itemsSummary) > 500) {
            $itemsSummary = mb_substr($itemsSummary, 0, 497).'...';
        }

        $discount = 0.0;
        $shipping = 0.0;
        $total = $subtotal - $discount + $shipping;

        Sale::query()->updateOrCreate(
            [
                'store_id' => $storeId,
                'order_ref' => $groupKey,
            ],
            [
                'client_id' => (int) $first->client_id,
                'seller_id' => $first->seller_id !== null ? (int) $first->seller_id : null,
                'product_id' => (int) $first->product_id,
                'total_amount' => $total,
                'subtotal' => $subtotal,
                'shipping_amount' => $shipping,
                'discount' => $discount,
                'items_count' => $orders->count(),
                'items_summary' => $itemsSummary,
                'sale_status' => $saleStatus,
                'status' => $saleStatus,
                'payment_method' => $first->payment_method ?? 'cash',
                'type' => 2,
            ]
        );
    }

    public function syncAfterOrderRemoved(int $storeId, ?string $orderRef, int $removedId): void
    {
        if ($orderRef !== null && $orderRef !== '') {
            $remaining = Order::query()
                ->where('store_id', $storeId)
                ->where('order_ref', $orderRef)
                ->first();

            if ($remaining !== null) {
                $this->syncForOrder($remaining);

                return;
            }

            $this->deleteByGroupKey($storeId, $orderRef);

            return;
        }

        $this->deleteByGroupKey($storeId, 'single_'.$removedId);
    }

    public function groupKey(Order $order): string
    {
        if ($order->order_ref !== null && $order->order_ref !== '') {
            return $order->order_ref;
        }

        return 'single_'.$order->id;
    }

    private function ordersInGroup(int $storeId, Order $anchor): Collection
    {
        if ($anchor->order_ref !== null && $anchor->order_ref !== '') {
            return Order::query()
                ->where('store_id', $storeId)
                ->where('order_ref', $anchor->order_ref)
                ->get();
        }

        return Order::query()->where('id', $anchor->id)->get();
    }

    private function deleteByGroupKey(int $storeId, string $groupKey): void
    {
        Sale::query()
            ->where('store_id', $storeId)
            ->where('order_ref', $groupKey)
            ->delete();
    }
}
