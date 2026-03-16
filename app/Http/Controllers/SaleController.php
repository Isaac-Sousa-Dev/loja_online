<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class SaleController extends Controller
{
    public function index()
    {
        $userAuth = Auth::user();
        $partner = $userAuth->partner;
        $store = $partner->store;
        
        $salesByStore = Sale::where('store_id', $store->id)->get();
    
        return view('partner.sales.index', ['salesByStore' => $salesByStore]);
    }
}
