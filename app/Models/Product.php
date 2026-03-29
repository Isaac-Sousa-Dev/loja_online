<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'image_main',
        'price',
        'price_wholesale',
        'price_promotional',
        'cost',
        'profit',
        'color',
        'brand_id',
        'stock',
        'partner_id',
        'old_price',
        'category_id',
        'installments',
        'discount_pix',
        'gender',
        'width',
        'height',
        'length',
        'weight',
        'size',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function images()
    {
        return $this->hasMany(Image::class, 'product_id', 'id');
    }

    public function properties()
    {
        return $this->hasOne(Property::class, 'product_id', 'id');
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brand_id', 'id');
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class)->where('active', true);
    }

    public function allVariants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    // Retorna cores únicas disponíveis nas variantes
    public function availableColors()
    {
        return $this->variants()->whereNotNull('color')->select('color', 'color_hex')->distinct()->get();
    }

    // Retorna tamanhos únicos disponíveis nas variantes
    public function availableSizes()
    {
        return $this->variants()->whereNotNull('size')->pluck('size')->unique()->values();
    }
}
