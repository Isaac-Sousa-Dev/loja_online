<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subcategories extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'created_by'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }


    public function modelos()
    {
        return $this->hasMany(Modelo::class);
    }
}
