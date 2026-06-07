<?php

namespace App\Services;

use App\Models\User;
use App\Services\partner\PartnerService;
use App\Services\store\StoreService;
use App\Services\user\UserService;
use Illuminate\Support\Facades\DB;

class AuthService
{

    public function __construct(
        private UserService $userService,
        private PartnerService $partnerService,
        private StoreService $storeService
    ) {}

    public function resolveLoginContext(User $user)
    {
        
    }

    public function registerNewPartnerAndStore(
        array $data
    )
    {
        return DB::transaction(function() use ($data) {
            $user = $this->userService->userRegistration($data);
    
            $data['user_id'] = $user->id;
            $partner = $this->partnerService->insert($data);
    
            $dataStore = $data['store'];
            $dataStore['partner_id'] = $partner->id;
            $this->storeService->insert($dataStore);
        });
    }
}