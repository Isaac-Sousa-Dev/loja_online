<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\Dashboard\GetPartnerDashboardOrderMetricsAction;
use App\Actions\Dashboard\GetSysAdminDashboardDataAction;
use App\Models\ClientStore;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __construct(
        private readonly GetPartnerDashboardOrderMetricsAction $partnerDashboardOrderMetrics,
        private readonly GetSysAdminDashboardDataAction $sysAdminDashboardData,
    ) {
    }

    public function index(): \Illuminate\Contracts\View\View
    {
        $userAuth = Auth::user();
        if ($userAuth->role == 'admin') {
            $data = $this->getDataForAdminDashboard($userAuth);

            return view('admin.dashboard', $data);
        } else {
            $data = $this->getDataForPartnerDashboard($userAuth);

            return view('partner.dashboard', $data);
        }
    }

    /**
     * @return array<string, mixed>
     */
    private function getDataForAdminDashboard(User $userAuth): array
    {
        return $this->sysAdminDashboardData->execute();
    }

    /**
     * @return array<string, mixed>
     */
    private function getDataForPartnerDashboard(User $userAuth): array
    {
        $partner = $userAuth->partner;
        $latestProducts = $partner->products()
            ->with(['images', 'brand'])
            ->latest()
            ->take(6)
            ->get();

        $store = $partner->store;
        $categoriesByStore = $store->categoriesByStore;
        $brands = $partner->brands;
        $clientsByStore = ClientStore::where('store_id', $store->id)->orderBy('created_at', 'desc')->get();

        $ordersByStore = $store->orders()
            ->with(['client', 'items.product'])
            ->latest()
            ->take(5)
            ->get();

        $orderMetrics = $this->partnerDashboardOrderMetrics->execute($store);

        return [
            'quantityStockProducts' => (int) Product::query()
                ->where('partner_id', $partner->id)
                ->sum('stock'),
            'latestProducts' => $latestProducts,
            'ordersByStore' => $ordersByStore,
            'quantityClients' => $clientsByStore->count(),
            'completedSalesThisMonth' => $orderMetrics['completed_sales_count'],
            'newOrdersThisMonth' => $orderMetrics['new_orders_count'],
            'monthlySalesTotal' => $orderMetrics['monthly_sales_total'],
            'user' => $userAuth,
            'store' => $store,
            'categoriesByStore' => $categoriesByStore,
            'brands' => $brands,
        ];
    }
}
