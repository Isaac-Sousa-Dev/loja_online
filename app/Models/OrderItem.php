<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    protected $table = 'order_items';

    protected $fillable = [
        'order_id',
        'store_id',
        'client_id',
        'product_id',
        'product_variant_id',
        'store_wholesale_level_id',
        'wholesale_applied_mode',
        'selected_color',
        'selected_size',
        'quantity',
        'unit_price',
        'line_subtotal',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'quantity' => 'integer',
            'unit_price' => 'decimal:2',
            'line_subtotal' => 'decimal:2',
            'store_wholesale_level_id' => 'integer',
        ];
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    public function variant(): BelongsTo
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id');
    }

    public function wholesaleLevel(): BelongsTo
    {
        return $this->belongsTo(StoreWholesaleLevel::class, 'store_wholesale_level_id');
    }

    public function variationSummary(): string
    {
        $this->loadMissing('variant');
        if ($this->variant !== null) {
            $bits = array_filter([$this->variant->color, $this->variant->size]);

            return $bits !== [] ? implode(' · ', $bits) : '—';
        }

        $bits = array_filter([$this->selected_color, $this->selected_size]);

        return $bits !== [] ? implode(' · ', $bits) : '—';
    }

    public function recalcLineSubtotal(): void
    {
        $qty = max(1, (int) $this->quantity);
        $this->line_subtotal = bcmul((string) $this->unit_price, (string) $qty, 2);
    }
}
