<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Partner;
use App\Models\Product;
use App\Models\StoreCategories;

class CatalogController extends Controller
{
    public ?string $logoStore = null;

    public function index(string $partner_link): \Illuminate\Contracts\View\View
    {
        date_default_timezone_set('America/Sao_Paulo');

        $partner = Partner::where('partner_link', $partner_link)->firstOrFail();
        $store = $partner->store;

        $logoStore = null;
        $bannerStore = null;
        if ($store->logo) {
            $logoStore = '/storage/'.$store->logo;
        }
        if ($store->banner) {
            $bannerStore = '/storage/'.$store->banner;
        }

        $categoriesByPartner = StoreCategories::where('store_id', $partner->store->id)->get();
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
            'logoStore' => $logoStore,
            'itsOpen' => true,
            'bannerStore' => $bannerStore,
            'categories' => $categoriesByPartner,
            'qtdProducts' => $qtdProducts,
        ]);
    }

    public function getAllProductByPartner(): \Illuminate\Http\JsonResponse
    {
        $partnerLink = $this->partnerLinkFromReferer();
        if ($partnerLink === null) {
            return response()->json([], 400);
        }

        $partner = Partner::where('partner_link', $partnerLink)->first();
        if ($partner === null) {
            return response()->json([], 404);
        }

        $products = $partner->publishedProducts;

        $productWithStock = [];
        foreach ($products as $product) {
            if ($product->stock > 0) {
                $productWithStock[] = $product;
            }
        }

        foreach ($productWithStock as $product) {
            $product->images = $product->images;
        }

        return response()->json($productWithStock);
    }

    public function search(): \Illuminate\Http\JsonResponse
    {
        $searchTerm = request()->query('search');
        $partnerLink = $this->partnerLinkFromReferer();
        if ($partnerLink === null || $searchTerm === null) {
            return response()->json(['data' => []]);
        }

        $partner = Partner::where('partner_link', $partnerLink)->first();
        if ($partner === null) {
            return response()->json(['data' => []]);
        }

        $products = Product::select('products.*', 'subcategories.name as subcategory_name')
            ->join('subcategories', 'products.subcategory_id', '=', 'subcategories.id')
            ->where('products.name', 'like', '%'.$searchTerm.'%')
            ->where('products.partner_id', $partner->id)
            ->where('products.is_active', true)
            ->orderBy('products.id', 'desc')
            ->paginate(4);

        return response()->json($products);
    }

    public function getProductsByCategory(int|string $category_id): \Illuminate\Http\JsonResponse
    {
        $products = Product::where('category_id', $category_id)
            ->where('stock', '>', 0)
            ->where('is_active', true)
            ->orderBy('id', 'desc')
            ->paginate(4);

        return response()->json($products);
    }

    public function getProductPage(string $partnerLink, int|string $productId): \Illuminate\Contracts\View\View
    {
        $partner = Partner::where('partner_link', $partnerLink)->firstOrFail();
        $store = $partner->store;

        if ($store->logo !== null) {
            $this->logoStore = '/storage/'.$store->logo;
        }

        $product = Product::with(['images', 'brand', 'variants', 'category'])
            ->where('partner_id', $partner->id)
            ->where('is_active', true)
            ->find($productId);

        if ($product === null) {
            abort(404);
        }

        $images = $product->images;
        $variantsByColor = collect($product->variants)
            ->groupBy('color')
            ->map(function ($variants, $color) use ($images) {
                return [
                    'variants' => $variants,
                    'images' => $images->where('variant_color', $color)->values(),
                ];
            })
            ->values()
            ->toArray();

        $imagesByColor = collect($product->variants)
            ->pluck('color')
            ->unique()
            ->filter()
            ->mapWithKeys(function ($color) use ($images) {
                $colorImages = $images->where('variant_color', $color)->values();
                $urls = $colorImages->map(fn ($img) => asset('storage/'.str_replace('public/', '', $img->url)));
                if ($urls->isEmpty()) {
                    $fallback = $images->map(fn ($img) => asset('storage/'.str_replace('public/', '', $img->url)));

                    return [$color => $fallback->values()];
                }

                return [$color => $urls->values()];
            });

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
            'product' => $product,
            'variantsByColor' => $variantsByColor,
            'imagesByColor' => $imagesByColor,
            'images' => $images,
            'imagesLength' => count($images),
            'partnerLink' => $partnerLink,
            'partner' => $partner,
            'logoStore' => $this->logoStore,
            'category' => $category,
            'related' => $related,
            'wholesaleMinQty' => $store->wholesale_min_quantity,
        ]);
    }

    private function partnerLinkFromReferer(): ?string
    {
        $referer = $_SERVER['HTTP_REFERER'] ?? '';
        $path = parse_url($referer, PHP_URL_PATH) ?? '';
        $segments = array_values(array_filter(explode('/', $path)));
        if (isset($segments[0]) && $segments[0] === 'catalog' && isset($segments[1])) {
            return $segments[1];
        }
        // Legado: /orders/{link}
        if (isset($segments[0]) && $segments[0] === 'orders' && isset($segments[1])) {
            return $segments[1];
        }

        return null;
    }
}
