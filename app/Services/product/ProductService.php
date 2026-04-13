<?php

namespace App\Services\product;

use App\DTO\ProductDTO;
use App\Models\Image;
use App\Models\Product;
use App\Models\ProductWholesalePrice;
use App\Repository\product\ProductRepository;
use App\Services\UploadFileService;

class ProductService
{

    public $uploadFileService;
    public $productRepository;

    public function __construct(
        UploadFileService $uploadFileService,
        ProductRepository $productRepository
    ) {
        $this->uploadFileService = $uploadFileService;
        $this->productRepository = $productRepository;
    }


    public function insert(array $data, $request = null)
    {
        if ($request !== null && $request->hasFile('product-images')) {
            $main = $this->uploadFileService->uploadMainImage($request);
            if (is_array($main) && isset($main['path'])) {
                $data['image_main'] = $main['path'];
            }
        }
        $data['price'] = $this->formattedPrice($data['price'] ?? '');
        $data['price_promotional'] = $this->formattedPrice($data['price_promotional'] ?? null);
        $data['price_wholesale'] = $this->formattedPrice($data['price_wholesale'] ?? null);
        $data['cost'] = $this->formattedPrice($data['cost'] ?? null);

        $productCreated = $this->productRepository->create($data);
        $this->syncWholesalePrices($productCreated, $data['wholesale_prices'] ?? []);
        if ($request !== null) {
            $this->uploadFileService->getPathAndExtension($request, $productCreated);
        }

        return $productCreated;
    }


    public function update($data, $product)
    {
        $data['price'] = $this->formattedPrice($data['price']);
        $data['price_promotional'] = $this->formattedPrice($data['price_promotional']);
        $data['price_wholesale'] = $this->formattedPrice($data['price_wholesale']);
        $data['cost'] = $this->formattedPrice($data['cost']);

        $dataForUpdate = $this->prepareUpdateData($data);
        $dataForUpdate['id'] = $product->id;
        $this->productRepository->update($dataForUpdate);
    }

    /**
     * Atualiza campos do catálogo de moda vindos do wizard (paridade com cadastro).
     *
     * @param array<string, mixed> $data
     */
    public function applyWizardAttributesToExistingProduct(array $data, Product $product): void
    {
        $update = [
            'name' => $data['name'],
            'description' => $data['description'],
            'price' => $this->formattedPrice($data['price'] ?? '') ?? $product->price,
            'price_wholesale' => $this->formattedPrice($data['price_wholesale'] ?? null),
            'price_promotional' => $this->formattedPrice($data['price_promotional'] ?? null),
            'cost' => $this->formattedPrice($data['cost'] ?? null),
            'brand_id' => $data['brand_id'],
            'category_id' => $data['category_id'],
            'gender' => $data['gender'] ?? null,
            'weight' => $data['weight'] ?? null,
            'width' => $data['width'] ?? null,
            'height' => $data['height'] ?? null,
            'length' => $data['length'] ?? null,
            'installments' => $data['installments'] ?? null,
            'discount_pix' => $data['discount_pix'] ?? null,
        ];
        if (array_key_exists('profit', $data)) {
            $update['profit'] = $data['profit'];
        }
        if (array_key_exists('is_active', $data)) {
            $update['is_active'] = (bool) $data['is_active'];
        }

        $product->update($update);
        $this->syncWholesalePrices($product, $data['wholesale_prices'] ?? []);
    }

    public function updatePricePromotional($data)
    {
        $data['pricePromotional'] = explode(',', $data['pricePromotional'])[0];
        $data['pricePromotional'] = str_replace('.', '', $data['pricePromotional']);

        $product = Product::find($data['productId']);
        $product->price_promotional = $data['pricePromotional'];
        $product->save();
    }

    public function updatePrice($data)
    {
        $data['price'] = explode(',', $data['price'])[0];
        $data['price'] = str_replace('.', '', $data['price']);

        $product = Product::find($data['productId']);
        $product->price = $data['price'];
        $product->save();
    }

    public function deleteImages($idsOfimages)
    {
        $idsOfimages = explode(',', $idsOfimages);

        foreach ($idsOfimages as $idImage) {
            $image = Image::find($idImage);

            if ($image) {
                $image->delete();
            }
        }
    }


    public function getLastIndex($product)
    {
        $images = $product->images;
        $lastIndex = 0;

        foreach ($images as $image) {
            if ($image->index > $lastIndex) {
                $lastIndex = $image->index;
            }
        }

        return $lastIndex;
    }

    public function prepareAddData($data)
    {
        if (isset($data['image_main'])) {
            $dataForInsert['image_main'] = $data['image_main'];
        }

        $dataForInsert = [
            "name" => $data['name'],
            "description" => $data['description'],
            "profit" => $data['profit'],
            "partner_id" => $data['partner_id'],
            "brand_id" => $data['brand_id'],
            "category_id" => $data['category_id'],
            "color" => $data['color'],
            "width" => $data['width'],
            "height" => $data['height'],
            "length" => $data['length'],
            "weight" => $data['weight'],
            "discount_pix" => $data['discount_pix'],
            "installments" => $data['installments']
        ];

        return $dataForInsert;
    }

    public function prepareUpdateData($data)
    {
        $dataForInsert = [
            "name" => $data['name'],
            "description" => $data['description'],
            "price" => $data['price'],
            "price_promotional" => $data['price_promotional'],
            "cost" => $data['cost'],
            "profit" => $data['profit'],
            "accept_exchange" => $data['accept_exchange'],
            "review_done" => $data['review_done'],
            "type" => $data['type'],
            "old_price" => null,
            "brand_id" => $data['brand_id'],
            "model_id" => $data['model_id'],
        ];

        if (isset($data['invoice'])): $dataForInsert['invoice'] = $data['invoice'];
        endif;
        if (isset($data['crlv'])): $dataForInsert['crlv'] = $data['crlv'];
        endif;
        if (isset($data['dut'])): $dataForInsert['dut'] = $data['dut'];
        endif;

        return $dataForInsert;
    }

    public function formattedPrice($price): ?string
    {
        if ($price === null || $price === '') {
            return null;
        }
        $price = str_replace('.', '', (string) $price);
        $price = str_replace(',', '.', $price);

        return $price;
    }

    /**
     * @param array<int, array{store_wholesale_level_id:int,price:?string}> $wholesalePrices
     */
    public function syncWholesalePrices(Product $product, array $wholesalePrices): void
    {
        $persistedLevelIds = [];

        foreach ($wholesalePrices as $row) {
            $levelId = (int) ($row['store_wholesale_level_id'] ?? 0);
            if ($levelId < 1) {
                continue;
            }

            $persistedLevelIds[] = $levelId;
            ProductWholesalePrice::query()->updateOrCreate(
                [
                    'product_id' => $product->id,
                    'store_wholesale_level_id' => $levelId,
                ],
                [
                    'price' => $this->formattedPrice($row['price'] ?? null),
                ]
            );
        }

        if ($persistedLevelIds === []) {
            $product->wholesalePrices()->delete();

            return;
        }

        $product->wholesalePrices()
            ->whereNotIn('store_wholesale_level_id', $persistedLevelIds)
            ->delete();
    }
}
