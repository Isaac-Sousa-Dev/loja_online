<?php

namespace App\Services\partner;

use App\Interfaces\AbstractServiceInterface;
use App\Models\Partner;
use App\Services\store\StoreService;

class PartnerService implements AbstractServiceInterface {

    protected $storeService;
    public $partnerLink;

    public function __construct(
        StoreService $storeService
    )
    {
        $this->storeService = $storeService;
    }

    public function insert(array $data, $request = null)
    {
        $this->verifyExistPartnerLink($data);
        $partner = Partner::create([
            'user_id' => $data['user_id'],
            'partner_link' => $this->partnerLink,
            'is_testing' => $data['is_testing'] == 'on' ? 1 : 0
        ]);

        return $partner;
    }

    public function verifyExistPartnerLink($data)
    {
        $partnerLink = strtolower(str_replace(' ', '-', $data['store_name']));
        $exist = Partner::where('partner_link', $partnerLink)->first();
        if($exist) {
            $this->partnerLink = $partnerLink . '-' . rand(1, 665);
        } else {
            $this->partnerLink = $partnerLink;
        }
    }

    public function update($data, $product)
    {}

    public function delete($model)
    {}

    public function find($id)
    {}

    public function findAll()
    {}

}