<?php

namespace App\Http\Controllers;

use App\Actions\Cache\FlushPartnerCatalogAndPanelCachesAction;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BrandController extends Controller
{
    public function __construct(
        private readonly FlushPartnerCatalogAndPanelCachesAction $flushPartnerCatalogAndPanelCaches,
    ) {}

    public function index()
    {
        $partner = Auth::user()->partner;
        $brands = Brand::where('partner_id', $partner->id)->orderBy('id', 'desc')->get();
        return view('partner.brands.index', compact('brands'));
    }

    public function create()
    {
        return view('partner.brands.create');
    }

    public function store(Request $request)
    {
        $this->validations($request);
        $userAuth = Auth::user();
        $partner = $userAuth->partner;

        $data = $request->all();
        $data['partner_id'] = $partner->id;

        if ($request->hasFile('logo_brand')) {
            $data['logo_brand'] = $request->file('logo_brand')->store('brands', 'public');
        }

        Brand::create($data);

        // Se for requisição ajax (como ao criar marca no modal do produto)
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['message' => 'Marca cadastrada com sucesso!', 'data' => $data['name']], 201);
        }

        return session()->flash('success', 'Marca cadastrada!');
    }

    public function edit(string $id)
    {
        $brand = Brand::where('id', $id)->where('partner_id', Auth::user()->partner->id)->firstOrFail();
        return view('partner.brands.edit', compact('brand'));
    }

    public function update(Request $request, string $id)
    {
        $this->validations($request);
        $brand = Brand::where('id', $id)->where('partner_id', Auth::user()->partner->id)->firstOrFail();
        
        $data = $request->all();

        if ($request->hasFile('logo_brand')) {
            $data['logo_brand'] = $request->file('logo_brand')->store('brands', 'public');
        }

        $brand->update($data);

        $this->flushPartnerCatalogAndPanelCaches->execute(Auth::user()->partner);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['message' => 'Marca atualizada com sucesso!']);
        }

        return redirect()->route('brands.index')->with('success', 'Marca atualizada!');
    }

    public function destroy(string $id)
    {
        $brand = Brand::where('id', $id)->where('partner_id', Auth::user()->partner->id)->firstOrFail();
        $brand->delete();
        $this->flushPartnerCatalogAndPanelCaches->execute(Auth::user()->partner);
        return redirect()->route('brands.index')->with('success', 'Marca removida!');
    }

    private function validations($request)
    {
        $request->validate([
            'name' => 'required'
        ]);
    }
}
