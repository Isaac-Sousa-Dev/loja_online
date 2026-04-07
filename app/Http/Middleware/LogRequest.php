<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class LogRequest
{
    public function handle(Request $request, Closure $next): Response
    {
        $requestId = (string) Str::uuid();
        $request->merge(['_request_id' => $requestId]);
        app()->instance('request_id', $requestId);

        $startTime = microtime(true);
        $response = $next($request);
        $duration = round((microtime(true) - $startTime) * 1000);

        Log::channel('access')->info('http.request', [
            'request_id' => $requestId,
            'store_id' => Auth::user()?->partner?->store?->id ?? null,
            'method' => $request->method(),
            'route' => $request->path(),
            'status' => $response->getStatusCode(),
            'duration_time' => $duration,
            'ip' => $request->ip(),
        ]);

        return $response;
    }
}
