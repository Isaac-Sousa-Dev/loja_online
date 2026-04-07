<?php

namespace App\Services\subcategory;

use App\Models\StoreSubcategories;
use App\Models\Subcategories;
use App\Repository\subcategory\SubcategoryRepository;

class SubcategoryService {

    public $subcategoryRepository;

    public function __construct(
        SubcategoryRepository $subcategoryRepository
    )
    {
        $this->subcategoryRepository = $subcategoryRepository;
    }


    public function create(array $data, $partner = null)
    {
        $checkExistenceSubcategory = $this->checkExistenceSubcategory($data['name']);
        if(!$checkExistenceSubcategory) {
            $subcategoryCreated['name'] = $data['name'];
            $subcategoryCreated['created_by'] = $partner->id;   
            $responseCreated = $this->subcategoryRepository->create($subcategoryCreated);
        } else {
            $responseCreated = $checkExistenceSubcategory;
        }

        $checkExistenceStoreSubcategory = $this->checkExistenceStoreSubcategory($partner->store->id, $responseCreated->id);
        if(!$checkExistenceStoreSubcategory) {
            StoreSubcategories::create([
                'store_id' => $partner->store->id,
                'subcategory_id' => $responseCreated->id
            ]);
        }
    }


    public function update(array $data, $partner = null)
    {

        $data['created_by'] = $partner->id;
        $storeId = $partner->store->id;
        $categoryId = $data['subcategory_id'];
        $storeSubcategoryUpdate = StoreSubcategories::where('store_id', $storeId)->where('subcategory_id', $categoryId)->first();

        $checkExistenceCategory = $this->checkExistenceSubcategory($data['name']);
        $responseCreated = null;
        if(!$checkExistenceCategory) {
            $responseCreated = $this->subcategoryRepository->create($data);
        }

        $storeSubcategoryUpdate->update([
            'store_id' => $partner->store->id,
            'subcategory_id' => $responseCreated != null ? $responseCreated->id : $checkExistenceCategory->id,
        ]);
    }


    private function checkExistenceStoreSubcategory($store_id, $subcategory_id)
    {
        $exist = StoreSubcategories::where('store_id', $store_id)->where('subcategory_id', $subcategory_id)->first();
        if($exist) {
            return $exist;
        }
        return false;
    }

    private function checkExistenceSubcategory($name)
    {
        $exist = Subcategories::where('name', $name)->first();
        if($exist) {
            return $exist;
        }
        return false;
    }

}