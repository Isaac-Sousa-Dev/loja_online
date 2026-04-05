<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SalesTeam extends Model
{
    protected $table = 'sales_teams';

    protected $fillable = [
        'phone',
        'status',
        'initial_message',
        'address',
        'city',
        'zip_code',
        'neighborhood',
        'number',
        'user_id',
        'partner_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function partner(): BelongsTo
    {
        return $this->belongsTo(Partner::class);
    }
}
