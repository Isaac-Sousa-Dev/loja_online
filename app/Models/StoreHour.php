<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoreHour extends Model
{
    use HasFactory;


    protected $fillable = [
        'store_id',
        'day_of_week',
        'open_time',
        'close_time',
        'is_open'
    ];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }
}
