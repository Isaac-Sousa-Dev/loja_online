<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UpgradeController extends Controller
{
    public function index()
    {

        $userAuth = Auth::user();
        $partner = $userAuth->partner;

        return view('partner.upgrade.index', ['partner' => $partner]);
    }
}
