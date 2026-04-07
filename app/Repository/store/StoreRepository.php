<?php

namespace App\Repository\store;

use App\Models\Partner;
use App\Models\Store;
use App\Repository\AbstractRepository;

class StoreRepository extends AbstractRepository
{

    public function __construct(Store $model)
    {
        parent::__construct($model);
    }
    
}