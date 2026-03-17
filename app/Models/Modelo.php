<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Modelo extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'brand_id',
        'codigo'
    ];


    public function subcategory()
    {
        return $this->belongsTo(Subcategories::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
