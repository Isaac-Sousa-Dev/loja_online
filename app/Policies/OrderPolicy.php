<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Order;
use App\Models\User;

class OrderPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->role !== 'admin' && $user->partner !== null && $user->partner->store !== null;
    }

    public function view(User $user, Order $order): bool
    {
        return $this->ownsPartnerStore($user, $order);
    }

    public function update(User $user, Order $order): bool
    {
        return $this->ownsPartnerStore($user, $order);
    }

    private function ownsPartnerStore(User $user, Order $order): bool
    {
        if ($user->role === 'admin' || $user->partner === null) {
            return false;
        }
        $store = $user->partner->store;

        return $store !== null && (int) $store->id === (int) $order->store_id;
    }
}
