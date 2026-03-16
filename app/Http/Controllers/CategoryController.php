<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\StoreCategories;
use App\Services\category\CategoryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{

    public $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }   

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
        $this->categoryService->create($request->all(), $partner);

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
        $this->categoryService->update($request->all(), $partner);

        return session()->flash('success', 'Categoria atualizada!');
    }


    public function destroy(string $id)
    {
        $storeCategory = StoreCategories::where('id', $id)->where('store_id', Auth::user()->partner->store->id)->first();
        $storeCategory->delete();
        return redirect()->route('categories.index')->with('success', 'Categoria removida!');
    }
}
