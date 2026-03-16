<?php

namespace App\Services\user;

use App\Interfaces\AbstractServiceInterface;
use App\Models\User;
use App\Repository\user\UserRepository;
use App\Services\partner\PartnerService;
use App\Services\subscription\SubscriptionService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class UserService implements AbstractServiceInterface {

    public $userRepository;
    protected $partnerService;
    protected $subscriptionService;
    public function __construct(
        UserRepository $userRepository,
        PartnerService $partnerService,
        SubscriptionService $subscriptionService
    )   
    {
        $this->userRepository = $userRepository;
        $this->partnerService = $partnerService;
        $this->subscriptionService = $subscriptionService;
    }

    public function userRegistration(array $data, $request = null)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed'],
        ]);

        $data['password'] = Hash::make($data['password']);
        $userCreated = $this->userRepository->create($data);
        return $userCreated;
    }

    public function insert(array $data, $request = null)
    {}
    public function update($data, $model)
    {}
    public function delete($model)
    {}
    public function find($id)
    {}
    public function findAll()
    {}

}