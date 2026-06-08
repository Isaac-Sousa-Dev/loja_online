<?php

namespace App\Services;

use App\Mail\ForgotPasswordMail;
use App\Models\User;
use App\Services\partner\PartnerService;
use App\Services\store\StoreService;
use App\Services\user\UserService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;

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

    public function registerNewPartnerAndStore(array $data)
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

    public function sendPasswordResetLink(string $email): void
    {
        $user = User::where('email', $email)->first();

        // Não revela se o e-mail existe ou não (anti-enumeração)
        if (!$user) return;

        $token = Password::getRepository()->create($user);

        $resetUrl = rtrim(config('app.frontend_url', config('app.url')), '/')
            . '/reset-password?token=' . $token
            . '&email=' . urlencode($user->email);

        Mail::to($user->email)->send(new ForgotPasswordMail($user, $resetUrl));
    }

    public function resetPassword(array $data): string
    {
        $status = Password::reset(
            [
                'email'    => $data['email'],
                'token'    => $data['token'],
                'password' => $data['password'],
            ],
            function (User $user, string $password) {
                $user->forceFill(['password' => Hash::make($password)])->save();
            }
        );

        return $status;
    }
}