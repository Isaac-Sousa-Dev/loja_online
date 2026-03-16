<?php

namespace App\Models;

use App\Enums\RequestStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Request extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'store_id',
        'product_id',
        'shift',
        'finance',
        'message',
        'status'
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    public function statusLabel(): string
    {
        return match ($this->status) {
            
            RequestStatus::IN_OPEN->value => 'Em aberto',
            RequestStatus::IN_PROGRESS->value => 'Em andamento',
            RequestStatus::CANCELLED->value => 'Cancelado',
            RequestStatus::COMPLETED->value => 'Vendido',
            default => 'Desconhecido',
        };
    }

    public function statusColor(): string
    {
        return match ($this->status) {
            RequestStatus::IN_OPEN->value => 'bg-gray-300 text-gray-800',
            RequestStatus::IN_PROGRESS->value => 'bg-gray-500 text-white',
            RequestStatus::CANCELLED->value => 'bg-red-500 text-white',
            RequestStatus::COMPLETED->value => 'bg-blue-500 text-white',
            default => 'bg-gray-400 text-white',
        };
    }
    
}
