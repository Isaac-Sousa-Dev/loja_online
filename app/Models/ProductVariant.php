<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'color',
        'color_hex',
        'size',
        'stock',
        'price_override',
        'active',
        'sku',
    ];

    protected $casts = [
        'active' => 'boolean',
        'price_override' => 'float',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
