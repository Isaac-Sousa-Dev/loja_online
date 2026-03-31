<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\FulfillmentType;
use App\Enums\OrderStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $fillable = [
        'code',
        'store_id',
        'client_id',
        'status',
        'fulfillment_type',
        'subtotal',
        'shipping_amount',
        'discount_amount',
        'total',
        'payment_method',
        'payment_installments',
        'payment_status',
        'message',
        'shift',
        'finance',
        'notified_at',
        'assigned_to',
        'separator_id',
        'completed_at',
        'seller_id',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status' => OrderStatus::class,
            'fulfillment_type' => FulfillmentType::class,
            'subtotal' => 'decimal:2',
            'shipping_amount' => 'decimal:2',
            'discount_amount' => 'decimal:2',
            'total' => 'decimal:2',
            'shift' => 'boolean',
            'finance' => 'boolean',
            'notified_at' => 'datetime',
            'completed_at' => 'datetime',
            'payment_installments' => 'integer',
        ];
    }

    protected static function booted(): void
    {
        static::created(function (Order $order): void {
            OrderStatusHistory::query()->create([
                'order_id' => $order->id,
                'from_status' => null,
                'to_status' => $order->status instanceof OrderStatus ? $order->status->value : (string) $order->status,
                'note' => null,
                'changed_by' => null,
            ]);
        });
    }

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function assignedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function separatorUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'separator_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function statusHistories(): HasMany
    {
        return $this->hasMany(OrderStatusHistory::class)->orderByDesc('created_at');
    }

    /**
     * Resumo de variações para listagem (sem fotos), ex.: "Tam. M × 2, G × 1".
     */
    public function itemsVariationSummary(): string
    {
        $items = $this->relationLoaded('items') ? $this->items : $this->items()->get();
        $parts = [];
        foreach ($items as $item) {
            $label = $item->variationSummary();
            $key = $label;
            if (! isset($parts[$key])) {
                $parts[$key] = ['label' => $label, 'qty' => 0];
            }
            $parts[$key]['qty'] += (int) $item->quantity;
        }

        return collect($parts)
            ->map(fn (array $row) => $row['label'].' × '.$row['qty'])
            ->implode(', ');
    }

    public static function generateUniqueCode(): string
    {
        do {
            $code = '#'.strtoupper(substr(bin2hex(random_bytes(5)), 0, 8));
        } while (self::query()->where('code', $code)->exists());

        return $code;
    }

    public function recalculateTotals(): void
    {
        $this->loadMissing('items');
        $subtotal = '0.00';
        foreach ($this->items as $item) {
            $item->recalcLineSubtotal();
            $item->saveQuietly();
            $subtotal = bcadd($subtotal, (string) $item->line_subtotal, 2);
        }
        $this->subtotal = $subtotal;
        $this->total = bcsub(
            bcadd($subtotal, (string) $this->shipping_amount, 2),
            (string) $this->discount_amount,
            2
        );
        $this->saveQuietly();
    }
}
