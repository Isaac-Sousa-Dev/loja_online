<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    protected $fillable = [
        'request_id',
        'user_id',
        'store_id',
        'table',
        'entity_id',
        'operation',
        'before',
        'after',
    ];
}
