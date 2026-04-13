<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductWholesalePrice extends Model
{
    protected $fillable = [
        'product_id',
        'store_wholesale_level_id',
        'price',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'product_id' => 'integer',
            'store_wholesale_level_id' => 'integer',
            'price' => 'decimal:2',
        ];
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function wholesaleLevel(): BelongsTo
    {
        return $this->belongsTo(StoreWholesaleLevel::class, 'store_wholesale_level_id');
    }
}
