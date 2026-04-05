<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Models\SalesTeam;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

final class EnsurePartnerStoreNotSuspended
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if ($user === null || $user->role === 'admin') {
            return $next($request);
        }

        if ($request->routeIs('partner.store.suspended')) {
            return $next($request);
        }

        $store = null;

        if ($user->role === 'partner') {
            $store = $user->partner?->store;
        } elseif ($user->role === 'seller') {
            $row = SalesTeam::query()->where('user_id', $user->id)->first();
            $store = $row?->partner?->store;
        }

        if ($store !== null && $store->isSuspendedManually()) {
            return redirect()->route('partner.store.suspended');
        }

        return $next($request);
    }
}
