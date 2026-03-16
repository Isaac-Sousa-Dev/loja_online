<?php

namespace App\Repository\requests;

use App\Models\RequestPlan;
use App\Repository\AbstractRepository;

class RequestPlanRepository extends AbstractRepository
{

    public function __construct(RequestPlan $model)
    {
        parent::__construct($model);
    }
    
}