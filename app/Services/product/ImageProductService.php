<?php

namespace App\Services\product;

use App\Models\Image;
use App\Services\UploadFileService;

class ImageProductService {

    protected $imageModel;
    protected $uploadFileService;
    protected $productService;

    public function __construct(
        Image $imageModel,
        UploadFileService $uploadFileService,
        ProductService $productService
    )
    {
        $this->imageModel = $imageModel;
        $this->uploadFileService = $uploadFileService;
        $this->productService = $productService;
    }


    public function initUpdate($data, $product)
    {

        if(isset($data['delete-images'])): $this->deleteImages($data['delete-images']); 
        endif;

        if(isset($data['current-images'])): $this->updateOrdemImages($data['current-images']);
        endif;

        if(isset($data['news-images'])) {
            $lastIndex = $this->productService->getLastIndex($product);
            $this->updateNewsImages($data['news-images'], $product, $lastIndex); 
        } 


        $mainImage = array_filter($product->images->toArray(), function($image) {
            return $image['index'] === 0;
        });

        if(!empty($mainImage) || $mainImage != null){
            $this->setMainImage($mainImage, $product);
        }

    }


    public function storeNewsImages($files, $product)
    {
        foreach ($files as $key => $file) {
            $pathAndExtension = $this->uploadFileService->getPathAndExtensionTest($file);
            $this->imageModel->create([
                'product_id' => $product->id,
                'url' => $pathAndExtension['path'],
                'index' => $key,
                'mimeType' => $pathAndExtension['extension']
            ]);
        }
    
    }


    public function updateNewsImages($files, $product, $lastIndex)
    {
        $index = $lastIndex + 1;
        foreach ($files as $key => $file) {
            $pathAndExtension = $this->uploadFileService->getPathAndExtensionTest($file);
            $this->imageModel->create([
                'product_id' => $product->id,
                'url' => $pathAndExtension['path'],
                'index' => $index,
                'mimeType' => $pathAndExtension['extension']
            ]);
            $index++;
        }
    }


    public function updateOrdemImages($files)
    {
        // Certifique-se de que $files seja um array antes de prosseguir
        if (!is_array($files)) {
            throw new \InvalidArgumentException('O parâmetro $files deve ser um array.');
        }

        foreach ($files as $key => $fileId) {
            $image = Image::find($fileId);
            if ($image) { 
                $image->index = $key;
                $image->save();
            } 
        }
    }


    public function deleteImages($files)
    {
        foreach($files as $fileId) {
            $image = Image::find($fileId);
            if($image) {
                $image->delete();
            }
        }
    }


    public function setMainImage($mainImage, $product)
    {
        $product->image_main = array_values($mainImage)[0]['url'];
        $product->save();
    }


}