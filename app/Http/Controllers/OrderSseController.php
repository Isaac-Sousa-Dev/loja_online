<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\OrderStatus;
use App\Models\Order;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class OrderSseController extends Controller
{
    /**
     * Server-Sent Events: notifica parceiros sobre pedidos pendentes na loja.
     */
    public function stream(Request $request): StreamedResponse
    {
        $user = $request->user();
        if ($user === null || $user->role === 'admin' || $user->partner === null) {
            abort(403);
        }

        $store = $user->partner->store;
        if ($store === null) {
            abort(403);
        }

        $storeId = $store->id;

        return response()->stream(function () use ($storeId): void {
            set_time_limit(0);
            while (! connection_aborted()) {
                $count = Order::query()
                    ->where('store_id', $storeId)
                    ->where('status', OrderStatus::PENDING->value)
                    ->whereNull('notified_at')
                    ->count();

                if ($count > 0) {
                    echo 'data: '.json_encode(['count' => $count], JSON_THROW_ON_ERROR)."\n\n";
                }

                if (ob_get_level() > 0) {
                    ob_flush();
                }
                flush();
                sleep(8);
            }
        }, 200, [
            'Content-Type' => 'text/event-stream',
            'Cache-Control' => 'no-cache',
            'X-Accel-Buffering' => 'no',
        ]);
    }
}
