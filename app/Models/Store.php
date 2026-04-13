<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\WholesaleCountMode;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

class Store extends Model
{
    use HasFactory;

    protected $fillable = [
        'store_name',
        'store_email',
        'store_phone',
        'store_cpf_cnpj',
        'qtd_products_in_stock',
        'logo',
        'banner',
        'wholesale_min_quantity',
        'wholesale_count_mode',
        'accepted_payment_methods',
        'accepted_card_brands',
        'partner_id',
        'plan_id',
        'suspended_at',
    ];

    protected $casts = [
        'wholesale_min_quantity' => 'integer',
        'wholesale_count_mode' => WholesaleCountMode::class,
        'accepted_payment_methods' => 'array',
        'accepted_card_brands' => 'array',
        'suspended_at' => 'datetime',
    ];

    public function isSuspendedManually(): bool
    {
        return $this->suspended_at !== null;
    }

    public function partner()
    {
        return $this->belongsTo(Partner::class);
    }

    public function clients()
    {
        return $this->belongsToMany(Client::class)
                ->using(ClientStore::class)
                ->withPivot('store_id', 'client_id') // Campos da tabela pivot
                ->withTimestamps();
    }

    public function categoriesByStore()
    {   
        return $this->hasMany(StoreCategories::class);
    }

    public function brands()
    {
        return $this->hasMany(Brand::class);
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    public function addressStore()
    {
        return $this->hasOne(AddressStore::class);
    }

    public function storeHours()
    {
        return $this->hasMany(StoreHour::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function wholesaleLevels(): HasMany
    {
        return $this->hasMany(StoreWholesaleLevel::class)->orderBy('position');
    }

    public function hasFeature(string $module): bool
    {
        if ($this->plan_id === null) {
            return false;
        }

        return DB::table('plan_modules')
            ->where('plan_id', $this->plan_id)
            ->where('module', $module)
            ->exists();
    }
}
