<?php

namespace App\Http\Controllers;

use App\Http\Requests\Partner\ClientRequest;
use App\Models\Client;
use App\Models\ClientStore;
use Illuminate\Http\Request;

class ClientController extends Controller
{

    public function index()
    {
        $userAuth = auth()->user(); 
        if($userAuth->role == 'partner') {
            $partner = $userAuth->partner;
            $store = $partner->store;

            $clientsByStore = ClientStore::where('store_id', $store->id)->orderBy('created_at', 'desc')->get();
            return view('partner.clients.index', ['clients' => $clientsByStore]);
        }
        
    }


    public function create()
    {
        return view('partner.clients.create');
    }


    public function store(ClientRequest $request)
    {
        $data = $request->all();
        Client::create($data);

        return redirect()->route('clients.index')->with('success', 'Cliente cadastrado com sucesso!');
    }


    public function show(string $id)
    {
        //
    }


    public function edit(string $id)
    {
        $client = Client::find($id);
        return view('partner.clients.edit', ['client' => $client]);
    }


    public function update(ClientRequest $request, string $id)
    {
        $client = Client::find($id);

        $data = $request->all();
        $client->update($data);

        return redirect()->route('clients.index')->with('success', 'Cliente atualizado com sucesso!');
    }


    public function destroy(string $id)
    {
        $client = Client::find($id);

        $client->delete();

        return redirect()->route('clients.index')->with('success', 'Cliente deletado com sucesso!');
    }
}
