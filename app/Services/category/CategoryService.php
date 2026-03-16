<?php

namespace App\Services\category;

use App\Models\Category;
use App\Models\StoreCategories;
use App\Models\StoreSubcategories;
use App\Models\Subcategories;
use App\Repository\category\CategoryRepository;

class CategoryService {

    public $categoryRepository;

    public function __construct(
        CategoryRepository $categoryRepository
    )
    {
        $this->categoryRepository = $categoryRepository;
    }


    public function create(array $data, $partner = null)
    {
        $data['created_by'] = $partner->id;

        $checkExistenceCategory = $this->checkExistenceCategory($data['name']);
        if (!$checkExistenceCategory) {
            $responseCreated = $this->categoryRepository->create($data);
        } else {
            $responseCreated = $checkExistenceCategory;
        }

        $checkExistenceStoreCategory = $this->checkExistenceStoreCategory($partner->store->id, $responseCreated->id);
        if(!$checkExistenceStoreCategory) {
            StoreCategories::create([
                'store_id' => $partner->store->id,
                'category_id' => $responseCreated->id,
                'description' => $data['description'] ?? null
            ]);
        }
    }


    public function update(array $data, $partner = null)
    {
        $data['created_by'] = $partner->id;
        $storeId = $partner->store->id;
        $categoryId = $data['category_id'];
        $storeCategoryUpdate = StoreCategories::where('store_id', $storeId)->where('category_id', $categoryId)->first();

        $checkExistenceCategory = $this->checkExistenceCategory($data['name']);
        $responseCreated = null;
        if(!$checkExistenceCategory) {
            $responseCreated = $this->categoryRepository->create($data);
        }

        $storeCategoryUpdate->update([
            'store_id' => $partner->store->id,
            'category_id' => $responseCreated != null ? $responseCreated->id : $checkExistenceCategory->id,
            'description' => $data['description']
        ]);
    }


    private function checkExistenceStoreCategory($store_id, $category_id)
    {
        $exist = StoreCategories::where('store_id', $store_id)->where('category_id', $category_id)->first();
        if($exist) {
            return $exist;
        }
        return false;
    }

    private function checkExistenceCategory($name)
    {
        $exist = Category::where('name', $name)->first();
        if($exist) {
            return $exist;
        }
        return false;
    }

}