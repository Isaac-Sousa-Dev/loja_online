<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AddressStore extends Model
{
    use HasFactory;

    protected $fillable = [
        'store_id',
        'country',
        'state',
        'city',
        'neighborhood',
        'street',
        'number',
        'zip_code',
    ];  

    public function store()
    {
        return $this->belongsTo(Store::class);
    }
}
