<?php

namespace App\Repository\product;

use App\Repository\AbstractRepository;

class ImageRepository extends AbstractRepository
{
    public function find($id)
    {
        return $this->model->find();
    }
}