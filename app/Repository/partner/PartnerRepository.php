<?php

namespace App\Repository\partner;

use App\Models\Partner;
use App\Repository\AbstractRepository;

class PartnerRepository extends AbstractRepository
{

    public function __construct(Partner $model)
    {
        parent::__construct($model);
    }

    public function findImages($id)
    {
        return $this->model->find($id)->images;
    }
    
}