<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;

    protected $fillable = [
        "partner_id",
        "plan_id",
        "status",
        "start_date",
        "end_date",
        "payment_method",
        "appellant"
    ];

    public function partner()
    {
        return $this->belongsTo(Partner::class);
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }
}
