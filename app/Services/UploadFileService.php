<?php

namespace App\Services;

use App\Models\Image;

class UploadFileService {

    protected $imageModel;

    public function __construct(
        Image $imageModel
    )
    {
        $this->imageModel = $imageModel;
    }


    public function insertImagesOfProducts($dataFile, $object = null, $key = null)
    {
        // TODO - Implementar Repository
        $this->imageModel->create([
            'product_id' => $object->id,
            'url' => $dataFile['path'],
            'index' => $key,
            'mimeType' => $dataFile['extension']
        ]);
    
    }


    public function getPathAndExtensionTest($file, $folder = 'images')
    {
        $extension = $file->getClientOriginalExtension();
        $path = $file->store($folder, 'public');

        return [
            'path' => $path,
            'extension' => $extension
        ];
    }


    public function getPathAndExtension($request, $object = null)
    {

        if($request->hasFile('product-images') || $request->hasFile('product-images')){

            if($request->file('product-images')){

                foreach($request->file('product-images') as $key => $file){
                    $extension = $file->getClientOriginalExtension();
                    $path = $file->store('images', 'public');

                    $this->insertImagesOfProducts([
                        'path' => $path,
                        'extension' => $extension
                    ], $object, $key);
                }
            } else {
                $path = $request->file('image')->store('images', 'public');
                $extension = $request->file('image')->getClientOriginalExtension();

                $this->insertImagesOfProducts([
                    'path' => $path,
                    'extension' => $extension
                ], $object);
            }
    
        }

    }


    public function uploadMainImage($request)
    {
        // dd($request->hasFile('product-images') || $request->hasFile('products-images'));
        if($request->hasFile('product-images') || $request->hasFile('products-images')){
    
            if($request->file('product-images')){

                foreach($request->file('product-images') as $file){
                    $extension = $file->getClientOriginalExtension();
                    $path = $file->store('images', 'public');

                    return [
                        'path' => $path,
                        'extension' => $extension
                    ];
                }
            } else {
                
                $path = $request->file('image')->store('images', 'public');
                $extension = $request->file('image')->getClientOriginalExtension();

                return [
                    'path' => $path,
                    'extension' => $extension
                ];
                
            }
    
        } else {
            return false;
        }
    }
}