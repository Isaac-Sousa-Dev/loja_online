<?php

namespace App\Http\Controllers;

use App\Http\Requests\Partner\DuplicateProductRequest;
use App\Http\Requests\Partner\IndexProductsRequest;
use App\Http\Requests\Partner\ProductRequest;
use App\Http\Requests\Partner\StoreProductWizardRequest;
use App\Http\Requests\Partner\UpdateProductVisibilityRequest;
use App\Http\Requests\Partner\UpdateProductWizardRequest;
use App\Models\Brand;
use Illuminate\Http\Request;        
use App\Models\Product;
use App\Models\StoreCategories;
use App\Services\product\ImageProductService;
use App\Services\UploadFileService;
use App\Services\product\PartnerProductListingService;
use App\Services\product\ProductDuplicateService;
use App\Services\product\ProductService;
use App\Services\product\ProductVariantSyncService;
use App\Services\product\ProductColorImageService;
use App\Services\product\ProductWizardService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class ProductController extends Controller
{

    protected $uploadFileService;
    private $productService;
    protected $imageProductService;

    public function __construct(
        UploadFileService $uploadFileService,
        ProductService $productService,
        ImageProductService $uploadProductService,
        private readonly ProductWizardService $productWizardService,
        private readonly ProductVariantSyncService $productVariantSyncService,
        private readonly ProductColorImageService $productColorImageService,
        private readonly PartnerProductListingService $partnerProductListingService,
        private readonly ProductDuplicateService $productDuplicateService,
    ) {
        $this->uploadFileService = $uploadFileService;
        $this->productService = $productService;
        $this->imageProductService = $uploadProductService;
    }

    public function index(IndexProductsRequest $request)
    {
        $userAuth = Auth::user();
        $partner = $userAuth->partner;
        $mainImageProduct = '';
        $filters = $request->filters();

        $storeId = $partner->store?->id ?? $partner->id;

        $query = Product::query()
            ->where('partner_id', $partner->id)
            ->with([
                'images' => static function ($q): void {
                    $q->orderBy('index')->orderBy('id');
                },
                'brand',
                'category',
            ])
            ->orderByDesc('id');

        $this->partnerProductListingService->applyFilters($query, $filters);

        $productsPaginator = $query->paginate(4)->withQueryString();
        $products = $productsPaginator->getCollection();

        if ($products->isNotEmpty()) {
            foreach ($products as $product) {
                if ($product->images->isNotEmpty()) {
                    $mainImageProduct = $product->images[0]->url;
                    break;
                }
            }
        }

        $categoriesByPartner = StoreCategories::where('store_id', $storeId)->with('category')->get();
        $brandsByPartner = Brand::where('partner_id', $partner->id)->orderBy('name')->get();

        return view('partner.products.index', [
            'products' => $products,
            'mainImageProduct' => $mainImageProduct,
            'productsPaginator' => $productsPaginator,
            'categoriesByPartner' => $categoriesByPartner,
            'brandsByPartner' => $brandsByPartner,
            'filters' => $filters,
        ]);
    }

    public function duplicate(DuplicateProductRequest $request, Product $product)
    {
        $partner = Auth::user()->partner;
        if ($partner === null) {
            abort(403);
        }

        if ($partner->products()->count() >= 30) {
            return redirect()
                ->route('products.index')
                ->with('error', 'Você atingiu o limite máximo de 30 produtos cadastrados.');
        }

        try {
            $newProduct = $this->productDuplicateService->duplicateForPartner($partner, $product);

            return redirect()
                ->route('products.edit', $newProduct->id)
                ->with('success', 'Produto duplicado. Ajuste o nome e os detalhes e ative quando estiver pronto.');
        } catch (Throwable $e) {
            Log::error('Product duplicate failed', ['exception' => $e]);

            return redirect()
                ->route('products.index')
                ->with('error', 'Não foi possível duplicar o produto. Tente novamente.');
        }
    }

    public function updateVisibility(UpdateProductVisibilityRequest $request, Product $product)
    {
        $product->update(['is_active' => $request->boolean('is_active')]);

        $message = $request->boolean('is_active')
            ? 'Produto ativado no catálogo.'
            : 'Produto desativado no catálogo.';

        return redirect()->back()->with('success', $message);
    }


    public function create()
    {
        $userAuth = Auth::user();
        $partner = $userAuth->partner;

        $storeId = $partner->store?->id ?? $partner->id;
        $categoriesByPartner = StoreCategories::where('store_id', $storeId)->get();

        $brands = Brand::where('partner_id', $partner->id)->get();

        return view('partner.products.create', [
            'brandsByPartner' => $brands,
            'categoriesByPartner' => $categoriesByPartner
        ]);
    }


    /**
     * Cadastro legado (FormRequest simples). Preferir storeWizard para o fluxo com variantes.
     */
    public function store(ProductRequest $request)
    {
        $userAuth = Auth::user();
        $partner = $userAuth->partner;
        if ($partner === null) {
            abort(403);
        }

        if ($partner->products()->count() >= 30) {
            $message = 'Você atingiu o limite máximo de 30 produtos cadastrados!';
            if ($request->wantsJson()) {
                return response()->json(['message' => $message], 422);
            }

            return redirect()->route('products.index')->with('error', $message);
        }

        try {
            DB::beginTransaction();
            $data = $request->all();
            $data['partner_id'] = $partner->id;
            $product = $this->productService->insert($data, $request);
            DB::commit();

            if ($request->wantsJson()) {
                return response()->json([
                    'product_id' => $product->id,
                    'message'    => 'Produto cadastrado!',
                ], 201);
            }

            session()->flash('success', 'Produto cadastrado!');

            return redirect()->route('products.index');
        } catch (Throwable $e) {
            DB::rollBack();
            Log::error('Product store failed', ['exception' => $e]);

            if ($request->wantsJson()) {
                return response()->json(['message' => 'Erro ao cadastrar produto.'], 500);
            }

            session()->flash('error', 'Erro ao cadastrar produto');

            return redirect()->back();
        }
    }

    public function storeWizard(StoreProductWizardRequest $request)
    {
        $userAuth = Auth::user();
        $partner = $userAuth->partner;
        if ($partner === null) {
            abort(403);
        }

        if ($partner->products()->count() >= 30) {
            $message = 'Você atingiu o limite máximo de 30 produtos cadastrados!';
            if ($request->wantsJson()) {
                return response()->json(['message' => $message], 422);
            }

            return redirect()->route('products.index')->with('error', $message);
        }

        try {
            $product = $this->productWizardService->createPartnerProduct($partner, $request);

            if ($request->wantsJson()) {
                return response()->json([
                    'product_id' => $product->id,
                    'message'    => 'Produto cadastrado!',
                ], 201);
            }

            session()->flash('success', 'Produto cadastrado!');

            return redirect()->route('products.index');
        } catch (Throwable $e) {
            Log::error('Product wizard store failed', ['exception' => $e]);

            if ($request->wantsJson()) {
                return response()->json(['message' => 'Erro ao cadastrar produto.'], 500);
            }

            session()->flash('error', 'Erro ao cadastrar produto');

            return redirect()->route('products.create');
        }
    }

    public function updateWizard(UpdateProductWizardRequest $request, string $id): JsonResponse
    {
        $userAuth = Auth::user();
        $partner = $userAuth->partner;
        if ($partner === null) {
            abort(403);
        }

        $product = Product::findOrFail($id);

        try {
            $this->productWizardService->updatePartnerProduct($partner, $product, $request);

            return response()->json([
                'message' => 'Produto atualizado!',
            ]);
        } catch (Throwable $e) {
            Log::error('Product wizard update failed', ['exception' => $e]);

            return response()->json(['message' => 'Erro ao atualizar produto.'], 500);
        }
    }


    public function edit(string $id)
    {
        $userAuth = Auth::user();
        $partner = $userAuth->partner;
        $product = Product::with(['variants', 'images'])->findOrFail($id);

        if ($product->partner_id !== $partner->id) {
            abort(403);
        }

        $brandsByPartner = Brand::where('partner_id', $partner->id)->get();
        $storeId = $partner->store?->id ?? $partner->id;
        $categoriesByPartner = StoreCategories::where('store_id', $storeId)->get();

        $existingVariantsJson = $product->variants->map(fn ($v) => [
            'id' => $v->id,
            'color' => $v->color,
            'size' => $v->size,
            'stock' => $v->stock,
            'price_override' => $v->price_override,
            'sku' => $v->sku,
            'color_hex' => $v->color_hex,
        ])->toJson(JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT);

        $existingColorPhotosForWizard = $this->productColorImageService->buildWizardExistingPhotosByColor((int) $product->id);

        $genderSelect = match ($product->gender) {
            'masculine' => 'M',
            'feminine' => 'F',
            default => '',
        };

        return view('partner.products.edit', [
            'product' => $product,
            'brandsByPartner' => $brandsByPartner,
            'categoriesByPartner' => $categoriesByPartner,
            'existingVariantsJson' => $existingVariantsJson,
            'existingColorPhotosForWizard' => $existingColorPhotosForWizard,
            'genderSelect' => $genderSelect,
        ]);
    }


    public function update(ProductRequest $request, string $id)
    {
        $data = $request->all();
        dd($data);
        $product = Product::find($id);

        if ($request->hasFile('crlv')): $data['crlv'] = $this->uploadFileService->getPathAndExtensionTest($request->file('crlv'), 'documents')['path'];
        endif;
        if ($request->hasFile('dut')): $data['dut'] = $this->uploadFileService->getPathAndExtensionTest($request->file('dut'), 'documents')['path'];
        endif;
        if ($request->hasFile('invoice')): $data['invoice'] = $this->uploadFileService->getPathAndExtensionTest($request->file('invoice'), 'documents')['path'];
        endif;

        $this->imageProductService->initUpdate($data, $product);
        $this->productService->update($data, $product);

        session()->flash('success', 'Produto atualizado!');
    }


    public function updatePricePromotional(Request $request)
    {
        $data = $request->all();
        $this->productService->updatePricePromotional($data);
    }


    public function updatePrice(Request $request)
    {
        $data = $request->all();
        $this->productService->updatePrice($data);
    }


    public function getVariants(string $id)
    {
        $product = Product::with('variants')->findOrFail($id);
        return response()->json($product->variants);
    }


    public function storeVariant(Request $request, string $id)
    {
        $request->validate([
            'color' => 'nullable|string|max:50',
            'size'  => 'nullable|string|max:20',
            'stock' => 'required|integer|min:0',
        ]);

        $variant = \App\Models\ProductVariant::create([
            'product_id'     => $id,
            'color'          => $request->color,
            'color_hex'      => $request->color_hex,
            'size'           => $request->size,
            'stock'          => $request->stock,
            'price_override' => $request->price_override ?: null,
            'active'         => true,
        ]);

        return response()->json(['success' => true, 'variant' => $variant], 201);
    }


    public function destroyVariant(string $variantId)
    {
        \App\Models\ProductVariant::findOrFail($variantId)->delete();
        return response()->json(['success' => true]);
    }

    /**
     * Sync all variants for a product (delete + recreate).
     */
    public function syncVariants(Request $request, string $id)
    {
        $variants = $request->input('variants', []);
        $this->productVariantSyncService->sync((int) $id, is_array($variants) ? $variants : []);

        $totalStock = \App\Models\ProductVariant::where('product_id', $id)->sum('stock');

        return response()->json(['success' => true, 'total_stock' => $totalStock]);
    }

    public function destroy(string $id)
    {
        $product = Product::find($id);

        $product->delete();

        return redirect()->route('products.index')->with('success', 'Produto deletado com sucesso!');
    }


    public function imageToBase64($imagePath)
    {
        // Caminho completo no sistema de arquivos
        $fullPath = storage_path('app/public/' . $imagePath);

        // Verifica se o arquivo existe
        if (!file_exists($fullPath)) {
            return null;
        }

        // Obtém o conteúdo do arquivo
        $fileContents = file_get_contents($fullPath);

        // Obtém o MIME type do arquivo
        $mimeType = mime_content_type($fullPath);

        // Converte para base64
        $base64 = 'data:' . $mimeType . ';base64,' . base64_encode($fileContents);

        return $base64;
    }

    /**
     * Exemplo de uso da função para testar no navegador.
     */
    public function showBase64Image($imagePath)
    {
        $base64 = $this->imageToBase64($imagePath);

        if ($base64) {
            return response($base64);
        }

        return response('Imagem não encontrada.', 404);
    }
}
