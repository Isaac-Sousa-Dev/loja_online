<?php

namespace App;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

trait Loggable
{
    protected function logEvent(string $action, array $meta = [], string $level = 'info'): void
    {
        Log::channel('app')->$level($action, array_merge([
            'request_id' => app('request_id'),
            'store_id' => Auth::user()?->partner?->store?->id ?? null,
            'user_id' => Auth::user()?->id ?? null,
            'action' => $action,
        ], $meta));
    }
}
