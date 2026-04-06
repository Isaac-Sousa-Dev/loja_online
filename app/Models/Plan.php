<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'price',
        'duration',
        'status',
        'type',
        'qtd_products',
    ];

    public function subscription()
    {
        return $this->hasMany(Subscription::class);
    }

    public function modules()
    {
        return $this->hasMany(PlanModules::class);
    }
}
