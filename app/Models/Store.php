<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        'partner_id',
        'plan_id'
    ];

    public function partner()
    {
        return $this->belongsTo(Partner::class);
    }

    public function sellers()
    {
        return $this->hasMany(Seller::class);
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

    public function subcategoriesByStore()
    {
        return $this->hasMany(StoreSubcategories::class);
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

    public function requests()
    {
        return $this->hasMany(Request::class);
    } 
}
