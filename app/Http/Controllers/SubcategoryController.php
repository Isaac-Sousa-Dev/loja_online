<?php

namespace App\Http\Controllers;

use App\Http\Requests\Partner\SubcategoryRequest;
use App\Models\StoreSubcategories;
use App\Services\subcategory\SubcategoryService;
use Illuminate\Support\Facades\Auth;

class SubcategoryController extends Controller
{

    public $subcategoryService;

    public function __construct(SubcategoryService $subcategoryService)
    {
        $this->subcategoryService = $subcategoryService;
    }

    public function index()
    {
        $userAuth = Auth::user();
        $partner = $userAuth->partner;
        $subcategoriesByPartner = StoreSubcategories::where('store_id', $partner->store->id)->get();

        return view('partner.subcategories.index', [
            'subcategoriesByPartner' => $subcategoriesByPartner
        ]);
    }


    public function create()
    {
        return view('partner.subcategories.create');
    }


    public function store(SubcategoryRequest $request)
    {
        $userAuth = Auth::user();
        $partner = $userAuth->partner;
        $this->subcategoryService->create($request->all(), $partner);

        return session()->flash('success', 'Marca cadastrada!');
    }


    public function edit(string $id)
    {
        $storeSubcategory = StoreSubcategories::where('subcategory_id', $id)->where('store_id', Auth::user()->partner->store->id)->first();
        return view('partner.subcategories.edit', ['storeSubcategory' => $storeSubcategory]);

    }


    public function update(SubcategoryRequest $request)
    {
        $userAuth = Auth::user();
        $partner = $userAuth->partner;
        $this->subcategoryService->update($request->all(), $partner);

        return session()->flash('success', 'Marca atualizada!');
    }


    public function destroy(string $id)
    {
        $storeSubcategory = StoreSubcategories::where('subcategory_id', $id)->where('store_id', Auth::user()->partner->store->id)->first();
        $storeSubcategory->delete();
        return redirect()->route('subcategories.index')
            ->with('success', 'Marca removida.');
    }

}
