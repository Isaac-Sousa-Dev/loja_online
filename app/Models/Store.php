<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Store extends Model
{
    use HasFactory;

    protected $fillable = [
        'store_name',
        'store_email',
        'store_phone',
        'store_cpf_cnpj',
        'qtd_vehicles_in_stock',
        'logo',
        'banner',
        'wholesale_min_quantity',
        'accepted_payment_methods',
        'accepted_card_brands',
        'partner_id',
        'plan_id',
    ];

    protected $casts = [
        'wholesale_min_quantity' => 'integer',
        'accepted_payment_methods' => 'array',
        'accepted_card_brands' => 'array',
    ];

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
