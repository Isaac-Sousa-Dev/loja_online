<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientStore extends Model
{
    use HasFactory;

    protected $table = 'clients_stores';
    public $timestamps = true;  

    protected $fillable = [
        'store_id',
        'client_id'
    ];  


    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
