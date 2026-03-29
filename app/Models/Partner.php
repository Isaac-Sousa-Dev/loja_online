<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Partner extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'partner_link'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function store()
    {
        return $this->hasOne(Store::class, 'partner_id', 'id');
    }

    public function subscription()
    {
        return $this->hasOne(Subscription::class, 'partner_id', 'id');
    }

    public function categories()
    {
        return $this->hasMany(Category::class);
    }

    public function subcategories()
    {
        return $this->hasManyThrough(Subcategories::class, Category::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'partner_id', 'id');
    }

    /** Catálogo público: apenas produtos marcados como ativos. */
    public function publishedProducts()
    {
        return $this->hasMany(Product::class, 'partner_id', 'id')->where('products.is_active', true);
    }

    public function requests()
    {
        return $this->hasMany(Request::class, 'store_id', 'store_id');
    }   

    public function sellers()
    {
        return $this->hasMany(Seller::class, 'store_id', 'store_id');
    }

}
