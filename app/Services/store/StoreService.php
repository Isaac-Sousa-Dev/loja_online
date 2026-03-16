<?php

namespace App\Services\store;

use App\Interfaces\AbstractServiceInterface;
use App\Models\AddressStore;
use App\Models\Store;
use App\Models\StoreHour;
use App\Repository\store\StoreRepository;
use App\Services\requests\RequestPlanService;

class StoreService implements AbstractServiceInterface {

    protected $storeModel;
    protected $requestPlanService;
    protected $storeRepository;
    public $partnerLink;

    public function __construct(
        Store $storeModel,
        RequestPlanService $requestPlanService,
        StoreRepository $storeRepository,
    )
    {
        $this->storeModel = $storeModel;
        $this->requestPlanService = $requestPlanService;
        $this->storeRepository = $storeRepository;
    }

    public function insert(array $data, $request = null)
    {
        $store = $this->storeModel->create($data);
        foreach(range(0, 6) as $i) {
            StoreHour::create([
                'store_id' => $store->id,
                'day_of_week' => $i,
                'open_time' => '08:00',
                'close_time' => '18:00',
                'is_open' => $i == 0 ? 0 : 1
            ]);
        }
    }

    public function update($data, $storeId)
    { 
        $store = Store::find($storeId)->with('partner')->first();
        $this->partnerLink = $store->partner->partner_link;
        $response = $this->checkDiferentStoreName($data, $store);
        
        $addressStore = AddressStore::where('store_id', $storeId)->first();
        $addressStore->update($data);
        $store->update($data);

        return $response;
    }

    public function delete($model)
    {}

    public function find($id)
    {}

    public function findAll()
    {}

    public function checkDiferentStoreName($data, $store)
    {
        if($data['store_name'] != $store->store_name):
            $this->partnerLink = strtolower(str_replace(' ', '-', $data['store_name']));
            $this->updatePartnerLink($store);
            return true;    
        endif;
        return false;
    }

    public function updatePartnerLink($store)
    {
        $store->partner->update([
            'partner_link' => $this->partnerLink
        ]);
    }
}