<?php

namespace App\Services\validations;

use App\Models\Partner;
use App\Models\User;

class ValidationUserService {

    public function existEmailOrPhone($data)
    {
        $data['phone'] = str_replace(['(', ')', '-', ' '], '', $data['phone']);
        $response = false;
        $exist = User::where('email', $data['email'])->orWhere('phone', $data['phone'])->first();
        if($exist != null): $response = true; endif;

        return $response;   
    }
}