<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\Partner\IndexSaleRequest;
use App\Models\Sale;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class SaleController extends Controller
{
    public function index(IndexSaleRequest $request): View
    {
        $user = Auth::user();
        if ($user === null || $user->partner === null || $user->partner->store === null) {
            abort(403);
        }

        $store = $user->partner->store;

        $base = Sale::query()
            ->where('store_id', $store->id)
            ->visibleAsSale();

        $this->applyFilters($base, $request);

        $statsRow = (clone $base)
            ->selectRaw(
                'COUNT(*) as sale_count, '
                .'COALESCE(SUM(COALESCE(subtotal, total_amount)), 0) as subtotal_sum, '
                .'COALESCE(SUM(discount), 0) as discount_sum, '
                .'COALESCE(SUM(total_amount), 0) as total_sum, '
                .'COALESCE(SUM(COALESCE(items_count, 0)), 0) as items_sum'
            )
            ->first();

        $sales = (clone $base)
            ->with(['client', 'seller'])
            ->latest()
            ->paginate(15)
            ->appends($request->query());

        $dashboard = [
            'sale_count' => (int) ($statsRow->sale_count ?? 0),
            'subtotal_sum' => (float) ($statsRow->subtotal_sum ?? 0),
            'discount_sum' => (float) ($statsRow->discount_sum ?? 0),
            'total_sum' => (float) ($statsRow->total_sum ?? 0),
            'items_sum' => (int) ($statsRow->items_sum ?? 0),
        ];

        return view('partner.sales.index', [
            'sales' => $sales,
            'dashboard' => $dashboard,
            'filters' => array_merge([
                'date_from' => null,
                'date_to' => null,
                'q' => null,
                'sale_status' => 'all',
                'payment_method' => null,
            ], $request->validated()),
        ]);
    }

    private function applyFilters(Builder $query, IndexSaleRequest $request): void
    {
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->input('date_from'));
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->input('date_to'));
        }

        $statusFilter = $request->input('sale_status', 'all');
        if ($statusFilter === 'confirmed') {
            $query->where('sale_status', 'confirmed');
        } elseif ($statusFilter === 'completed') {
            $query->where(function (Builder $w): void {
                $w->where('sale_status', 'completed')
                    ->orWhere(function (Builder $w2): void {
                        $w2->whereNull('sale_status')->where('status', 'completed');
                    });
            });
        }

        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->input('payment_method'));
        }

        if ($request->filled('q')) {
            $raw = $request->input('q', '');
            $term = '%'.addcslashes((string) $raw, '%_\\').'%';

            $query->where(function (Builder $w) use ($term): void {
                $w->where('order_ref', 'like', $term)
                    ->orWhere('items_summary', 'like', $term)
                    ->orWhere('nf_number', 'like', $term)
                    ->orWhere('observations', 'like', $term)
                    ->orWhereHas('client', function (Builder $cq) use ($term): void {
                        $cq->where('name', 'like', $term)
                            ->orWhere('phone', 'like', $term)
                            ->orWhere('email', 'like', $term);
                    });
            });
        }
    }
}
