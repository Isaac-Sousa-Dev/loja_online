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
        'weight'
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

    // public function category()
    // {
    //     return $this->belongsTo(Category::class, 'category_id', 'id');
    // }
}
