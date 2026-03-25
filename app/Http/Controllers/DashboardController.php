<?php

namespace App\Http\Controllers;

use App\Models\ClientStore;
use App\Models\Plan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
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


    private function getDataForAdminDashboard($userAuth)
    {
        $users = User::where('role', 'partner')->with('partner')->orderBy('created_at', 'desc')->get();

        $filteredUsers = $users->filter(function ($user) {
            return $user->partner->status != 'pending';
        });

        $data = [];
        $data['allPartners'] = $filteredUsers;
        $data['quantityPartners'] = $filteredUsers->count();
        return $data;
    }


    private function getDataForPartnerDashboard($userAuth)
    {
        $partner = $userAuth->partner;
        // Pegar os últimos produtos cadastrados com imagens e marca
        $latestProducts = $partner->products()
            ->with(['images', 'brand'])
            ->latest()
            ->take(6)
            ->get();

        $store = $partner->store;
        $categoriesByStore = $store->categoriesByStore;
        $subcategoriesByStore = $store->subcategoriesByStore;
        $clientsByStore = ClientStore::where('store_id', $store->id)->orderBy('created_at', 'desc')->get();

        $requestsByStore = $store->requests()->with(['client', 'product'])->latest()->get();
        $quantitySales = $requestsByStore->where('status', 'sold')->count();

        $data = [];
        $data['quantityStockProducts'] = $partner->products->sum('stock');
        $data['latestProducts'] = $latestProducts;
        $data['requestsByStore'] = $requestsByStore->take(5);
        $data['quantityRequests'] = $store->requests->count();
        $data['quantityClients'] = $clientsByStore->count();
        $data['quantitySales'] = $quantitySales;
        $data['user'] = $userAuth;
        $data['store'] = $store;
        $data['categoriesByStore'] = $categoriesByStore;
        $data['subcategoriesByStore'] = $subcategoriesByStore;

        return $data;
    }
}
