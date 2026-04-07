<?php

namespace App\Repository\user;

use App\Models\User;
use App\Repository\AbstractRepository;

class UserRepository extends AbstractRepository
{

    public function __construct(User $model)
    {
        parent::__construct($model);
    }
    
}