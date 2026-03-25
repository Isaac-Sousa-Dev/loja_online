<?php

namespace App\Http\Controllers;

use App\Http\Requests\Partner\ProductRequest;
use App\Models\Brand;
use Illuminate\Http\Request;
use App\Models\Modelo;
use App\Models\Product;
use App\Models\StoreCategories;
use App\Services\product\ImageProductService;
use App\Services\UploadFileService;
use App\Services\product\ProductService;
use Illuminate\Support\Facades\Auth;
use App\Services\PropertyService;
use Exception;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{

    protected $uploadFileService;
    private $productService;
    public $propertyService;
    protected $imageProductService;

    public function __construct(
        UploadFileService $uploadFileService,
        ProductService $productService,
        PropertyService $propertyService,
        ImageProductService $uploadProductService
    ) {
        $this->uploadFileService = $uploadFileService;
        $this->productService = $productService;
        $this->propertyService = $propertyService;
        $this->imageProductService = $uploadProductService;
    }

    public function index()
    {
        $userAuth = Auth::user();
        $partner = $userAuth->partner;
        $mainImageProduct = '';

        $productsPaginator = $partner->products()->orderBy('id', 'desc')->paginate(4);
        $products = $productsPaginator->getCollection();

        if ($products->isNotEmpty()) {
            foreach ($products as $product) {
                if ($product->images->isNotEmpty()) {
                    $mainImageProduct = $product->images[0]->url;
                    break;
                }
            }
        }

        return view('partner.products.index', ['products' => $products, 'mainImageProduct' => $mainImageProduct, 'productsPaginator' => $productsPaginator]);
    }


    public function create()
    {
        $userAuth = Auth::user();
        $partner = $userAuth->partner;

        $categoriesByPartner = StoreCategories::where('store_id', $partner->id)->get();

        $models = Modelo::all();
        $brands = Brand::where('partner_id', $partner->id)->get();

        return view('partner.products.create', [
            'brandsByPartner' => $brands,
            'models' => $models,
            'categoriesByPartner' => $categoriesByPartner
        ]);
    }


    public function store(ProductRequest $request)
    {
        try {
            $userAuth = Auth::user();
            $partner = $userAuth->partner;
            $products = $partner->products;

            if ($products->count() < 30) {

                DB::beginTransaction();
                $data = $request->all();
                $data['partner_id'] = $partner->id;

                $product = $this->productService->insert($data, $request);
                // $this->propertyService->insert($data, $product->id);
                DB::commit();

                session()->flash('success', 'Produto cadastrado!');
            } else {
                DB::rollBack();
                session()->flash('error', 'Você atingiu o limite máximo de 30 produtos cadastrados!');
            }
        } catch (Exception $e) {
            dd($e->getMessage());
            DB::rollBack();
            session()->flash('error', 'Erro ao cadastrar produto');
        }
    }


    public function edit(string $id)
    {
        $userAuth = Auth::user();
        $partner = $userAuth->partner;
        $product = Product::find($id);

        $images = $product->images()->orderBy('index', 'asc')->get();

        $models = Modelo::all();
        $brands = Brand::all();
        $categoriesByPartner = StoreCategories::where('store_id', $partner->id)->get();

        return view('partner.products.edit', [
            'product' => $product,
            'models' => $models,
            'brands' => $brands,
            'images' => $images,
            'categoriesByPartner' => $categoriesByPartner
        ]);
    }


    public function update(ProductRequest $request, string $id)
    {
        $data = $request->all();
        $product = Product::find($id);

        if ($request->hasFile('crlv')): $data['crlv'] = $this->uploadFileService->getPathAndExtensionTest($request->file('crlv'), 'documents')['path'];
        endif;
        if ($request->hasFile('dut')): $data['dut'] = $this->uploadFileService->getPathAndExtensionTest($request->file('dut'), 'documents')['path'];
        endif;
        if ($request->hasFile('invoice')): $data['invoice'] = $this->uploadFileService->getPathAndExtensionTest($request->file('invoice'), 'documents')['path'];
        endif;

        $this->imageProductService->initUpdate($data, $product);
        $this->propertyService->update($data, $id);
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
