<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoreCategories extends Model
{
    use HasFactory;

    protected $fillable = ['store_id', 'category_id', 'description', 'image_url'];


    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
