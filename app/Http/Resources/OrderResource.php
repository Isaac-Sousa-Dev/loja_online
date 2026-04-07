<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * JSON do pedido (cabeçalho + itens quando carregados).
 */
class OrderResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $status = $this->status;
        $fulfillment = $this->fulfillment_type;

        return [
            'id' => $this->id,
            'code' => $this->code,
            'status' => $status instanceof \BackedEnum ? $status->value : $status,
            'status_label' => $status instanceof \App\Enums\OrderStatus ? $status->label() : (string) $status,
            'fulfillment_type' => $fulfillment instanceof \BackedEnum ? $fulfillment->value : $fulfillment,
            'subtotal' => (string) $this->subtotal,
            'shipping_amount' => (string) $this->shipping_amount,
            'discount_amount' => (string) $this->discount_amount,
            'total' => (string) $this->total,
            'payment_method' => $this->payment_method,
            'payment_installments' => $this->payment_installments,
            'payment_status' => $this->payment_status,
            'message' => $this->message,
            'notified_at' => $this->notified_at?->toIso8601String(),
            'created_at' => $this->created_at?->toIso8601String(),
            'client' => [
                'id' => $this->client?->id,
                'name' => $this->client?->name,
                'phone' => $this->client?->phone,
                'email' => $this->client?->email,
            ],
            'items' => $this->whenLoaded('items', function () {
                return $this->items->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'product_id' => $item->product_id,
                        'product_variant_id' => $item->product_variant_id,
                        'quantity' => $item->quantity,
                        'unit_price' => (string) $item->unit_price,
                        'line_subtotal' => (string) $item->line_subtotal,
                        'variation_summary' => $item->variationSummary(),
                        'product_name' => $item->product?->name,
                    ];
                });
            }),
        ];
    }
}
