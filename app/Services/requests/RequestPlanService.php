<?php

namespace App\Services\requests;

use App\Interfaces\AbstractServiceInterface;
use App\Models\RequestPlan;
use App\Repository\requests\RequestPlanRepository;
use App\Services\validations\ValidationUserService;

class RequestPlanService implements AbstractServiceInterface {

    protected $requestPlanRepository;
    protected $validationUserService;

    public function __construct(
        RequestPlanRepository $requestPlanRepository,
        ValidationUserService $validationUserService
    )
    {
        $this->requestPlanRepository = $requestPlanRepository;
        $this->validationUserService = $validationUserService;
    }

    public function insert(array $data, $request = null)
    {
        $existUser = $this->validationUserService->existEmailOrPhone($data);
        if($existUser) {
            return 'error';
        } else {
            $data['phone'] = str_replace(['(', ')', '-', ' '], '', $data['phone']); 
            $requestPlanCreated = $this->requestPlanRepository->create($data);
            return $requestPlanCreated;
        }
    }

    public function update($data, $product)
    {}

    public function delete($model)
    {}

    public function find($id)
    {}

    public function findAll()
    {
        return $this->requestPlanRepository->all();
    }
}