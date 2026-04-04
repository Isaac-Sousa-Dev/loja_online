<?php

namespace App\Http\Controllers;

use App\Actions\Cache\FlushPartnerCatalogAndPanelCachesAction;
use App\Models\StoreCategories;
use App\Services\category\CategoryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    public function __construct(
        private readonly CategoryService $categoryService,
        private readonly FlushPartnerCatalogAndPanelCachesAction $flushPartnerCatalogAndPanelCaches,
    ) {}

    public function index()
    {
        $userAuth = Auth::user();
        $partner = $userAuth->partner;
        $categoriesByPartner = StoreCategories::where('store_id', $partner->store->id)->orderBy('id', 'desc')->get();
        return view('partner.categories.index', ['categoriesByPartner' => $categoriesByPartner]);
    }


    public function create()
    {
        return view('partner.categories.create');
    }


    public function store(Request $request)
    {
        $userAuth = Auth::user();
        $partner = $userAuth->partner;
        $data = $request->all();

        if ($request->hasFile('image')) {
            $data['image_url'] = $request->file('image')->store('categories', 'public');
        }

        $this->categoryService->create($data, $partner);

        $this->flushPartnerCatalogAndPanelCaches->execute($partner);

        return session()->flash('success', 'Categoria cadastrada!');
    }


    public function edit(string $id)
    {
        $storeCategory = StoreCategories::where('category_id', $id)->where('store_id', Auth::user()->partner->store->id)->first();
        return view('partner.categories.edit', ['storeCategory' => $storeCategory]);
    }


    public function update(Request $request, string $id)
    {
        $userAuth = Auth::user();
        $partner = $userAuth->partner;
        $data = $request->all();

        if ($request->hasFile('image')) {
            $data['image_url'] = $request->file('image')->store('categories', 'public');
        }

        $this->categoryService->update($data, $partner);

        $this->flushPartnerCatalogAndPanelCaches->execute($partner);

        return session()->flash('success', 'Categoria atualizada!');
    }


    public function destroy(string $id)
    {
        $storeCategory = StoreCategories::where('id', $id)->where('store_id', Auth::user()->partner->store->id)->first();
        $storeCategory->delete();
        $this->flushPartnerCatalogAndPanelCaches->execute(Auth::user()->partner);
        return redirect()->route('categories.index')->with('success', 'Categoria removida!');
    }
}
