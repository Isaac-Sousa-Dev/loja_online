<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class YearsByModel extends Model
{
    protected $fillable = [
        'name',
        'codigo',
        'brand_id'
    ];
}
