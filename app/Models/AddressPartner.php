<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AddressPartner extends Model
{
    use HasFactory;

    protected $fillable = [
        'city',
        'state',
        'street',
        'zipcode',
        'neighborhood',
        'number',
        'partner_id'
    ];
}
