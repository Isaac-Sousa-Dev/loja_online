<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

final class EnsurePartnerMustChangePassword
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if ($user === null || $user->role !== 'partner' || ! $user->must_change_password) {
            return $next($request);
        }

        if ($request->routeIs('partner.first-password.*', 'logout')) {
            return $next($request);
        }

        return redirect()->route('partner.first-password.edit');
    }
}
