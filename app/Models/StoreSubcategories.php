<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoreSubcategories extends Model
{
    use HasFactory;

    protected $fillable = [
        'store_id',
        'subcategory_id'
    ];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function subcategory()
    {
        return $this->belongsTo(Subcategories::class);
    }
}
