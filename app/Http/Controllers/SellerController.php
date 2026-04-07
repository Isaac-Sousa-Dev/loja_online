<?php

namespace App\Http\Controllers;

use App\Models\Seller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SellerController extends Controller
{
    public function listSellers()
    {
        $userAuth = Auth::user();
        $partner = $userAuth->partner;
        $store = $partner->store;
        
        $sellers = $store->sellers;
        return view('partner.seller.index', ['sellers' => $sellers]);
    }


    public function createSeller()
    {
        return view('partner.seller.create');
    }

    public function storeSeller(Request $request)
    {
        $userAuth = Auth::user();
        $partner = $userAuth->partner;
        $store = $partner->store;
        $data = $request->all();

        $userCreated = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'image_profile' => $data['image_profile'] ?? null,
            'role' => $data['role'],
            'phone' => $data['phone'],
            'password' => bcrypt($data['password']),
        ]);

        Seller::create([
            'user_id' => $userCreated->id,
            'store_id' => $store->id, // Assuming store_id is optional
        ]);

        return redirect()->route('sellers.index')->with('success', 'Membro criado com sucesso!');
    }

    public function editSeller($id)
    {
        $seller = Seller::findOrFail($id);
        return view('partner.seller.edit', ['seller' => $seller]);
    }

    public function updateSeller(Request $request, $id)
    {
        $seller = Seller::findOrFail($id);
        $user = $seller->user;

        // Validação, ignorando o e-mail do próprio usuário
        $request->validate([
            'name'  => 'string|max:255',
            'email' => 'email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'password' => 'nullable|string|min:6',
        ]);

        $data = $request->all();

        $user->update([
            'name' => $data['name'],
            'email' => $data['email'],
            'image_profile' => $data['image_profile'] ?? $user->image_profile,
            'role' => $data['role'],
            'phone' => $data['phone'],
            'password' => bcrypt($data['password']),
        ]);

        return redirect()->route('sellers.index')->with('success', 'Membro atualizado com sucesso!');
    }
}
