<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'phone',
        'email'
    ];

    public function stores()
    {
        return $this->belongsToMany(Store::class)
                ->using(ClientStore::class) // Usa o modelo pivot
                ->withPivot('store_id', 'client_id') // Campos da tabela pivot
                ->withTimestamps(); // Se a pivot tem timestamps
    }

    public function sales()
    {
        return $this->hasMany(Sale::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
