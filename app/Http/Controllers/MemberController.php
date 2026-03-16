<?php

namespace App\Http\Controllers;

use App\Models\Seller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MemberController extends Controller
{
    public function list()
    {
        $userAuth = Auth::user();
        $partner = $userAuth->partner;
        $store = $partner->store;
        
        $members = $store->sellers;
        return view('partner.member.index', ['members' => $members]);
    }


    public function create()
    {
        return view('partner.member.create');
    }

    public function store(Request $request)
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
            'store_id' => $store->id,
        ]);

        return redirect()->route('members.index')->with('success', 'Membro criado com sucesso!');
    }

    public function edit($id)
    {
        $member = Seller::findOrFail($id);
        return view('partner.member.edit', ['member' => $member]);
    }

    public function update(Request $request, $id)
    {
        $seller = Seller::findOrFail($id);
        $user = $seller->user;

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

        return redirect()->route('members.index')->with('success', 'Membro atualizado com sucesso!');
    }
    

    public function destroy($id)
    {
        $seller = Seller::findOrFail($id);
        $user = $seller->user;

        // Delete the user and associated seller
        $user->delete();
        $seller->delete();

        return redirect()->route('members.index')->with('success', 'Membro excluído com sucesso!');
    }
}
