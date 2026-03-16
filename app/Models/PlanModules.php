<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlanModules extends Model
{
    protected $fillable = [
        "plan_id",
        "module"
    ];
}
