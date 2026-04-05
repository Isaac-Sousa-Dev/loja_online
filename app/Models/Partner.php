<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Partner extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'partner_link',
        'is_testing',
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

    public function orders()
    {
        return $this->hasManyThrough(Order::class, Store::class, 'partner_id', 'store_id');
    }

    /**
     * Consultores / vendedores vinculados à loja (tabela sales_teams).
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<SalesTeam, Partner>
     */
    public function salesTeamMembers()
    {
        return $this->hasMany(SalesTeam::class, 'partner_id', 'id');
    }

    public function brands()
    {
        return $this->hasMany(Brand::class, 'partner_id', 'id');
    }

}
