<?php

namespace App\Repository\category;

use App\Models\Category;
use App\Repository\AbstractRepository;

class CategoryRepository extends AbstractRepository
{

    public function __construct(Category $model)
    {
        parent::__construct($model);
    }

    // public function create(array $data)
    // {
    //     return $this->categoryModel->create($data);
    // }


    public function findImages($id)
    {
        return $this->model->find($id)->images;
    }
    
}