<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// use App\Models\StoreCategories;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'created_by'   
    ];

    
    public function storeCategory()
    {
        $this->belongsTo(StoreCategories::class, 'category_id', 'id');
    }
    
}
