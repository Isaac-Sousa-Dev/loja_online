<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BrandController extends Controller
{
    public function store(Request $request)
    {
        $this->validations($request);
        $userAuth = Auth::user();
        $partner = $userAuth->partner;

        $data = $request->all();
        $data['partner_id'] = $partner->id;

        Brand::create($data);
        return response()->json(['message' => 'Marca cadastrada com sucesso!', 'data' => $data['name']], 201);
    }


    private function validations($request)
    {
        $request->validate([
            'name' => 'required'
        ]);
    }
}
