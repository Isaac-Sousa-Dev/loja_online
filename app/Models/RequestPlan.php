<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'store_name',
        'qtd_vehicles_in_stock',
        'plan_slug',
        'payment_method',
        'notes',
    ];

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }
}
