<?php

namespace App\Repository\product;

use App\Models\Product;
use App\Repository\AbstractRepository;

class ProductRepository extends AbstractRepository
{

    public function __construct(Product $model)
    {
        parent::__construct($model);
    }

    public function findImages($id)
    {
        return $this->model->find($id)->images;
    }
    
}