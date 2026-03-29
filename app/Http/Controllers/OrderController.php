<?php

namespace App\Http\Controllers;

use App\Models\Partner;
use App\Models\Product;
use App\Models\StoreCategories;
use App\Models\StoreHour;

class OrderController extends Controller
{
    public $logoStore;
   
    public function index($partner_link)
    {
        date_default_timezone_set('America/Sao_Paulo');

        $partner = Partner::where('partner_link', $partner_link)->first();
        $store = $partner->store;


        if($store->logo): $logoStore = '/storage/'.$store->logo; endif;
        if($store->banner): $bannerStore = '/storage/'.$store->banner; endif;

        $categoriesByPartner = StoreCategories::where('store_id', $partner->store->id)->get();

        $currentHour = date('H:i:s');
        $currentDay = date('w');
        $storeHours = StoreHour::where('day_of_week', $currentDay)->where('store_id', $store->id)->first();
        // dd($storeHours);

        // $itsOpen = false;
        // if($storeHours->is_open) {
        //     if($currentHour >= $storeHours->open_time && $currentHour <= $storeHours->close_time) {
        //         $itsOpen = true;
        //     }
        // }

        $products = $partner->publishedProducts;

        $productWithStock = [];
        foreach ($products as $product) {
            if ($product->stock > 0) {
                $productWithStock[] = $product;
            }
        }

        $qtdProducts = count($productWithStock);
        return view('orders.catalog.index', [
            'partner' => $partner,
            'store' => $store,
            'logoStore' => $logoStore ?? null,
            'itsOpen' => true,
            'bannerStore' => $bannerStore ?? null,
            'categories' => $categoriesByPartner,
            'qtdProducts' => $qtdProducts,
        ]);
    }


    public function getAllProductByPartner()
    {
        $partnerLink = explode('/', $_SERVER['HTTP_REFERER'])[4];
        $partner = Partner::where('partner_link', $partnerLink)->first();
        $products = $partner->publishedProducts;

        $productWithStock = [];
        foreach ($products as $product) {
            if ($product->stock > 0) {
                $productWithStock[] = $product;
            }
        }

        foreach ($productWithStock as $key => $product) {
            $product->properties = $product->properties;
            $product->images = $product->images;
        }

        return response()->json($productWithStock);
    }


    public function search()
    {
        $searchTerm = request()->query('search');
        $partnerLink = explode('/', $_SERVER['HTTP_REFERER'])[4];
        $partner = Partner::where('partner_link', $partnerLink)->first();

        $products = Product::select('products.*', 'subcategories.name as subcategory_name')
                            ->join('subcategories', 'products.subcategory_id', '=', 'subcategories.id')
                            ->where('products.name', 'like', '%' . $searchTerm . '%')
                            ->where('products.partner_id', $partner->id)
                            ->where('products.is_active', true)
                            ->orderBy('products.id', 'desc')
                            ->paginate(4);
        
        foreach ($products as $key => $product) {
            $product->properties = $product->properties;
        }

        return response()->json($products);
    }


    public function getProductsByCategory($category_id)
    {
        $products = Product::where('category_id', $category_id)
                            ->where('stock', '>', 0)
                            ->where('is_active', true)
                            ->orderBy('id', 'desc')
                            ->paginate(4);
        foreach ($products as $key => $product) {
            $product->properties = $product->properties;
        }
        return response()->json($products);
    }


    public function getProductPage($partnerLink, $productId) {

        $partner = Partner::where('partner_link', $partnerLink)->first();
        $store = $partner->store;

        if($store->logo != null): $this->logoStore = '/storage/'.$store->logo; endif;

        $product = Product::with(['images', 'brand', 'variants', 'category'])
            ->where('partner_id', $partner->id)
            ->where('is_active', true)
            ->find($productId);

        if ($product === null) {
            abort(404);
        }

        $images = $product->images;

        // Produtos relacionados: mesma categoria ou mesma marca, excluindo o atual
        $related = Product::with(['images'])
            ->where('partner_id', $partner->id)
            ->where('id', '!=', $productId)
            ->where('stock', '>', 0)
            ->where('is_active', true)
            ->where(function ($q) use ($product) {
                if ($product->category_id) {
                    $q->orWhere('category_id', $product->category_id);
                }
                if ($product->brand_id) {
                    $q->orWhere('brand_id', $product->brand_id);
                }
            })
            ->take(8)
            ->get();

        $category = $product->category;

        return view('orders.product-page.index', [
            'product'     => $product,
            'images'      => $images,
            'imagesLength'=> count($images),
            'partnerLink' => $partnerLink,
            'partner'     => $partner,
            'logoStore'   => $this->logoStore,
            'category'    => $category,
            'related'     => $related,
        ]);
    }
}
