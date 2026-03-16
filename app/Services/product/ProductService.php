<?php

namespace App\Services\product;

use App\DTO\ProductDTO;
use App\Models\Image;
use App\Models\Product;
use App\Repository\product\ProductRepository;
use App\Services\UploadFileService;

class ProductService {

    public $uploadFileService;
    public $productRepository;

    public function __construct(
        UploadFileService $uploadFileService,
        ProductRepository $productRepository
    )
    {
        $this->uploadFileService = $uploadFileService;
        $this->productRepository = $productRepository;
    }


    public function insert(array $data, $request = null)
    {

        if($request->hasFile('crlv')): $data['crlv'] = $this->uploadFileService->getPathAndExtensionTest($request->file('crlv'), 'documents')['path']; endif;
        if($request->hasFile('dut')): $data['dut'] = $this->uploadFileService->getPathAndExtensionTest($request->file('dut'), 'documents')['path']; endif;
        if($request->hasFile('invoice')): $data['invoice'] = $this->uploadFileService->getPathAndExtensionTest($request->file('invoice'), 'documents')['path']; endif;
        if ($request->hasFile('product-images')): $data['image_main'] = $this->uploadFileService->uploadMainImage($request)['path']; endif;

        $data['price'] = $this->formattedPrice($data['price']);
        $data['price_promotional'] = $this->formattedPrice($data['price_promotional']);
        $data['cost'] = $this->formattedPrice($data['cost']);

        $dataForInsert = $this->prepareAddData($data);
        $productCreated = $this->productRepository->create($dataForInsert);
        $this->uploadFileService->getPathAndExtension($request, $productCreated);    

        return $productCreated;
    }


    public function update($data, $product)
    {
        $data['price'] = $this->formattedPrice($data['price']);
        $data['price_promotional'] = $this->formattedPrice($data['price_promotional']);
        $data['cost'] = $this->formattedPrice($data['cost']);

        $dataForUpdate = $this->prepareUpdateData($data);
        $dataForUpdate['id'] = $product->id;
        $this->productRepository->update($dataForUpdate);        
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
        if(isset($data['image_main'])){
            $dataForInsert['image_main'] = $data['image_main'];
        }

        $dataForInsert = [
            "name" => $data['name'],
            "description" => $data['description'],
            "price" => $data['price'],
            "price_promotional" => $data['price_promotional'] > 0 ? $data['price_promotional'] : 0,
            "cost" => $data['cost'],
            "profit" => $data['profit'],
            "accept_exchange" => $data['accept_exchange'],
            "reindeer" => $data['reindeer'],
            "chassis" => $data['chassis'],
            "engine" => $data['engine'],
            "review_done" => $data['review_done'],
            "partner_id" => $data['partner_id'],
            "brand_id" => $data['brand_id'],
            "model_id" => $data['model_id'],
            "type" => $data['type'] ?? null,
            "old_price" => null,
            "invoice" => $data['invoice'] != null ? $data['invoice'] : null,
            "crlv" => $data['crlv'] != null ? $data['crlv'] : null,
            "dut" => $data['dut'] != null ? $data['dut'] : null,
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

        if(isset($data['invoice'])): $dataForInsert['invoice'] = $data['invoice']; endif;
        if(isset($data['crlv'])): $dataForInsert['crlv'] = $data['crlv']; endif;
        if(isset($data['dut'])): $dataForInsert['dut'] = $data['dut']; endif;

        return $dataForInsert;
    }

    public function formattedPrice($price)
    {
        $price = str_replace('.', '', $price);
        $price = str_replace(',', '.', $price);
        return $price;
    }
}