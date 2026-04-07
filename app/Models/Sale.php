<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Sale extends Model
{
    use HasFactory;

    protected $fillable = [
        'store_id',
        'order_ref',
        'client_id',
        'seller_id',
        'product_id',
        'total_amount',
        'subtotal',
        'shipping_amount',
        'items_count',
        'items_summary',
        'sale_status',
        'type',
        'status',
        'payment_method',
        'nf_number',
        'delivery_date',
        'fees',
        'discount',
        'observations',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'shipping_amount' => 'decimal:2',
        'fees' => 'decimal:2',
        'discount' => 'decimal:2',
        'delivery_date' => 'date',
        'items_count' => 'integer',
    ];

    /**
     * Vendas exibíveis: confirmadas a partir de pedidos ou registros legados concluídos.
     */
    public function scopeVisibleAsSale(Builder $query): Builder
    {
        return $query->where(function (Builder $w): void {
            $w->whereIn('sale_status', ['confirmed', 'completed'])
                ->orWhere(function (Builder $w2): void {
                    $w2->whereNull('sale_status')->where('status', 'completed');
                });
        });
    }

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function seller(): BelongsTo
    {
        return $this->belongsTo(User::class, 'seller_id');
    }
}
