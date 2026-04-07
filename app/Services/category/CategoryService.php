<?php

namespace App\Services\category;

use App\Models\Category;
use App\Models\Product;
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
                'description' => $data['description'] ?? null,
                'image_url' => $data['image_url'] ?? null
            ]);
        }
    }


    public function update(array $data, $partner = null)
    {
        $data['created_by'] = $partner->id;
        $storeId = $partner->store->id;
        $categoryId = $data['category_id'];
        $storeCategoryUpdate = StoreCategories::where('store_id', $storeId)->where('category_id', $categoryId)->first();

        $previousCategoryId = (int) $storeCategoryUpdate->category_id;

        $checkExistenceCategory = $this->checkExistenceCategory($data['name']);
        $responseCreated = null;
        if(!$checkExistenceCategory) {
            $responseCreated = $this->categoryRepository->create($data);
        }

        $newCategoryId = $responseCreated != null ? (int) $responseCreated->id : (int) $checkExistenceCategory->id;

        $updateData = [
            'store_id' => $partner->store->id,
            'category_id' => $newCategoryId,
            'description' => $data['description']
        ];
        
        if (isset($data['image_url'])) {
            $updateData['image_url'] = $data['image_url'];
        }

        $storeCategoryUpdate->update($updateData);

        if ($previousCategoryId !== $newCategoryId) {
            Product::query()
                ->where('partner_id', $partner->id)
                ->where('category_id', $previousCategoryId)
                ->update(['category_id' => $newCategoryId]);
        }
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