<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\PartnerFirstLoginPasswordRequest;
use App\Support\PartnerFirstLogin;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class PartnerFirstLoginPasswordController extends Controller
{
    public function edit(): View
    {
        $fromSession = session(PartnerFirstLogin::SESSION_PLAIN_PASSWORD_KEY);
        $prefill = old('current_password', is_string($fromSession) ? $fromSession : '');

        return view('auth.partner-first-password', [
            'currentPasswordPrefill' => $prefill,
        ]);
    }

    public function update(PartnerFirstLoginPasswordRequest $request): RedirectResponse
    {
        $request->user()->update([
            'password' => Hash::make($request->validated('password')),
            'must_change_password' => false,
        ]);

        $request->session()->forget(PartnerFirstLogin::SESSION_PLAIN_PASSWORD_KEY);

        return redirect()
            ->intended(\App\Providers\RouteServiceProvider::HOME)
            ->with('status', 'first-password-changed');
    }
}
