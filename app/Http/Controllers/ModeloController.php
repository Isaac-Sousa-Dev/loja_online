<?php

namespace App\Http\Controllers;

use App\Models\Modelo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ModeloController extends Controller
{
    public function index()
    {
        $userAuth = Auth::user();
        $partner = $userAuth->partner;

        $modelos = $partner->modelos->sortByDesc('created_at');

        return view('partner.modelos.index', ['modelos' => $modelos]);
    }


    public function create()
    {

        $userAuth = Auth::user();
        $partner = $userAuth->partner;

        $subcategories = $partner->subcategories;

        return view('partner.modelos.create', ['subcategories' => $subcategories]);
    }


    public function store(Request $request)
    {
        $userAuth = Auth::user();
        $partner = $userAuth->partner;

        $data = $request->all();

        $data['slug'] = Str::slug($request->name);
        $data['partner_id'] = $partner->id;

        Modelo::create($data);

        return redirect()->route('modelos.index')
            ->with('success', 'Modelo criado com sucesso.');

    }


    public function edit(string $id)
    {
        $userAuth = Auth::user();
        $partner = $userAuth->partner;
        $modelo = Modelo::find($id);

        $subcategories = $partner->subcategories;

        return view('partner.modelos.edit', ['modelo' => $modelo, 'subcategories' => $subcategories]);
    }


    public function update(Request $request, string $id)
    {
        $userAuth = Auth::user();
        $partner = $userAuth->partner;

        $data = $request->all();

        $data['slug'] = Str::slug($request->name);
        $data['partner_id'] = $partner->id;

        $modelo = Modelo::find($id);
        $modelo->update($data);

        return redirect()->route('modelos.index')
            ->with('success', 'Modelo atualizado com sucesso.');
    }


    public function destroy(string $id)
    {
    
        $modelo = Modelo::find($id);
        $modelo->delete();

        return redirect()->route('modelos.index')
            ->with('success', 'Modelo deletado com sucesso.');
    }

}
