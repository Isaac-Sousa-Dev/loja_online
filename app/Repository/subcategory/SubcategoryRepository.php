<?php

namespace App\Repository\subcategory;

use App\Models\Subcategories;
use App\Repository\AbstractRepository;

class SubcategoryRepository extends AbstractRepository
{

    public $subcategoryModel;

    public function __construct(Subcategories $subcategoryModel)
    {
        $this->subcategoryModel = $subcategoryModel;
    }

    public function create(array $data)
    {
        return $this->subcategoryModel->create($data);
    }


    public function findImages($id)
    {
        return $this->subcategoryModel->find($id)->images;
    }
    
}