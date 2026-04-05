<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\Orders\CountUnnotifiedPendingOrdersAction;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class OrderSseController extends Controller
{
    public function __construct(
        private readonly CountUnnotifiedPendingOrdersAction $countUnnotifiedPendingOrders,
    ) {}

    /**
     * Server-Sent Events: notifica parceiros sobre pedidos pendentes na loja.
     */
    public function stream(Request $request): StreamedResponse
    {
        if (! config('orders.sse_enabled')) {
            abort(404);
        }

        $user = $request->user();
        if ($user === null || $user->role === 'admin' || $user->partner === null) {
            abort(403);
        }

        $store = $user->partner->store;
        if ($store === null) {
            abort(403);
        }

        $storeId = $store->id;
        $counter = $this->countUnnotifiedPendingOrders;

        return response()->stream(function () use ($storeId, $counter): void {
            set_time_limit(0);
            $idleCycles = 0;
            while (! connection_aborted()) {
                $count = $counter->execute($storeId);

                echo 'data: '.json_encode(['count' => $count], JSON_THROW_ON_ERROR)."\n\n";

                if ($count > 0) {
                    $idleCycles = 0;
                    $sleepSeconds = 8;
                } else {
                    ++$idleCycles;
                    $sleepSeconds = $idleCycles >= 3 ? 25 : 8;
                }

                if (ob_get_level() > 0) {
                    ob_flush();
                }
                flush();
                sleep($sleepSeconds);
            }
        }, 200, [
            'Content-Type' => 'text/event-stream',
            'Cache-Control' => 'no-cache',
            'X-Accel-Buffering' => 'no',
        ]);
    }
}
