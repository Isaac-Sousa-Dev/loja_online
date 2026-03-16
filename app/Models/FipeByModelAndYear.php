<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FipeByModelAndYear extends Model
{
    protected $fillable = [
        'vehicle_type',
        'price_fipe',
        'brand_name',
        'brand_id',
        'model_name',
        'model_year',
        'fuel',
        'codigo_fipe',
        'reference_month',
        'fuel_acronym'
    ];
}
