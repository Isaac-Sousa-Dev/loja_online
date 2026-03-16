<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Property extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'year_of_manufacture',
        'fuel',
        'license_plate',
        'miliage',
        'exchange',
        'bodywork',
        'accept_exchange',
        'review_done',
        'color',
        'chassis',
        'engine',
        'reindeer'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
