<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Carbon\CarbonImmutable;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\Partner;
use App\Models\Product;
use App\Models\StoreCategories;

class CatalogController extends Controller
{
    private const PRODUCTS_PER_PAGE = 12;

    public ?string $logoStore = null;

    public function index(string $partner_link): View
    {
        date_default_timezone_set('America/Sao_Paulo');

        $partner = Partner::where('partner_link', $partner_link)->firstOrFail();
        $store = $partner->store()->with(['addressStore', 'storeHours'])->firstOrFail();

        $logoStore = null;
        $bannerStore = null;
        if ($store->logo) {
            $logoStore = '/storage/'.$store->logo;
        }
        if ($store->banner) {
            $bannerStore = '/storage/'.$store->banner;
        }

        $categoriesByPartner = StoreCategories::with('category')
            ->where('store_id', $store->id)
            ->get();

        $qtdProducts = $partner->publishedProducts()
            ->where('products.stock', '>', 0)
            ->count();

        return view('orders.catalog.index', [
            'partner' => $partner,
            'store' => $store,
            'logoStore' => $logoStore,
            'itsOpen' => $this->isStoreOpen($store),
            'bannerStore' => $bannerStore,
            'categories' => $categoriesByPartner,
            'qtdProducts' => $qtdProducts,
        ]);
    }

    public function getAllProductByPartner(): JsonResponse
    {
        $partner = $this->resolvePartner();
        if ($partner === null) {
            return response()->json([], 404);
        }

        return response()->json(
            $this->paginateCatalogProducts(
                $partner,
                request()->query('search'),
                request()->query('category_id'),
            ),
        );
    }

    public function search(): JsonResponse
    {
        $partner = $this->resolvePartner();
        $searchTerm = request()->query('search');
        if ($partner === null || ! is_string($searchTerm) || trim($searchTerm) === '') {
            return response()->json([]);
        }

        return response()->json(
            $this->paginateCatalogProducts(
                $partner,
                $searchTerm,
                request()->query('category_id'),
            ),
        );
    }

    public function getProductsByCategory(int|string $category_id): JsonResponse
    {
        $partner = $this->resolvePartner();
        if ($partner === null) {
            return response()->json([]);
        }

        return response()->json(
            $this->paginateCatalogProducts(
                $partner,
                request()->query('search'),
                $category_id,
            ),
        );
    }

    public function getProductPage(string $partnerLink, int|string $productId): View
    {
        $partner = Partner::with(['store.addressStore', 'store.storeHours'])
            ->where('partner_link', $partnerLink)
            ->firstOrFail();
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
            'store' => $store,
            'logoStore' => $this->logoStore,
            'itsOpen' => $this->isStoreOpen($store),
            'category' => $category,
            'related' => $related,
            'wholesaleMinQty' => $store->wholesale_min_quantity,
        ]);
    }

    private function paginateCatalogProducts(
        Partner $partner,
        mixed $searchTerm = null,
        mixed $categoryId = null,
    ): LengthAwarePaginator {
        $query = Product::query()
            ->with(['images', 'brand'])
            ->where('partner_id', $partner->id)
            ->where('is_active', true)
            ->where('stock', '>', 0)
            ->orderByDesc('id');

        if ($categoryId !== null && $categoryId !== '' && $categoryId !== 'todos') {
            $query->where('category_id', $categoryId);
        }

        if (is_string($searchTerm) && trim($searchTerm) !== '') {
            $term = '%'.addcslashes(trim($searchTerm), '%_\\').'%';

            $query->where(function (Builder $builder) use ($term): void {
                $builder->where('name', 'like', $term)
                    ->orWhere('description', 'like', $term)
                    ->orWhereHas('brand', function (Builder $brandQuery) use ($term): void {
                        $brandQuery->where('name', 'like', $term);
                    });
            });
        }

        return $query->paginate(self::PRODUCTS_PER_PAGE)->withQueryString();
    }

    private function resolvePartner(): ?Partner
    {
        $partnerLink = $this->resolvePartnerLink();
        if ($partnerLink === null) {
            return null;
        }

        return Partner::where('partner_link', $partnerLink)->first();
    }

    private function isStoreOpen(\App\Models\Store $store): bool
    {
        $now = CarbonImmutable::now('America/Sao_Paulo');
        $currentHour = $store->storeHours
            ->firstWhere('day_of_week', (int) $now->dayOfWeek);

        if ($currentHour === null || ! $currentHour->is_open || $currentHour->open_time === null || $currentHour->close_time === null) {
            return false;
        }

        $currentTime = $now->format('H:i');
        $openTime = substr((string) $currentHour->open_time, 0, 5);
        $closeTime = substr((string) $currentHour->close_time, 0, 5);

        return $currentTime >= $openTime && $currentTime <= $closeTime;
    }

    private function resolvePartnerLink(): ?string
    {
        $fromQuery = request()->query('partner_link');
        if (is_string($fromQuery) && $fromQuery !== '') {
            return $fromQuery;
        }

        return $this->partnerLinkFromReferer();
    }

    private function partnerLinkFromReferer(): ?string
    {
        $referer = $_SERVER['HTTP_REFERER'] ?? '';
        $path = parse_url($referer, PHP_URL_PATH) ?? '';
        $segments = array_values(array_filter(explode('/', $path)));
        if (isset($segments[0]) && $segments[0] === 'catalog' && isset($segments[1])) {
            $slug = $segments[1];
            $reserved = ['products-by-partner', 'search', 'get-products-by-category', 'message-sent-page'];

            return in_array($slug, $reserved, true) ? null : $slug;
        }
        // Legado: /orders/{link}
        if (isset($segments[0]) && $segments[0] === 'orders' && isset($segments[1])) {
            return $segments[1];
        }

        return null;
    }
}
