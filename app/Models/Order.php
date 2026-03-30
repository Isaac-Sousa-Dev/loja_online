<?php

namespace App\Models;

use App\Enums\OrderStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'store_id',
        'product_id',
        'product_variant_id',
        'selected_color',
        'selected_size',
        'payment_method',
        'delivery_type',
        'delivery_address',
        'delivery_city',
        'delivery_state',
        'delivery_zip',
        'delivery_complement',
        'shift',
        'finance',
        'message',
        'status',
        'order_ref',
        'quantity',
    ];

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id');
    }

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
            OrderStatus::PENDING->value => 'Em aberto',
            OrderStatus::PAID->value => 'Pago',
            OrderStatus::CANCELED->value => 'Cancelado',
            OrderStatus::SOLD->value => 'Vendido',
            default => 'Desconhecido',
        };
    }

    public function statusColor(): string
    {
        return match ($this->status) {
            OrderStatus::PENDING->value => 'bg-gray-300 text-gray-800',
            OrderStatus::PAID->value => 'bg-green-500 text-white',
            OrderStatus::CANCELED->value => 'bg-red-500 text-white',
            OrderStatus::SOLD->value => 'bg-blue-500 text-white',
            default => 'bg-gray-400 text-white',
        };
    }
    
}
