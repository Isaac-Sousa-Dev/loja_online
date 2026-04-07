<?php

namespace App\Services\store;

use App\Models\Store;

class UploadFileStoreService {

    protected $storeModel;

    public function __construct(
        Store $storeModel
    )
    {
        $this->storeModel = $storeModel;
    }


    public function insertImagesOfProducts($dataFile, $object = null)
    {
        // TODO - Implementar Repository
        // $this->imageModel->create([
        //     'product_id' => $object->id,
        //     'url' => $dataFile['path'],
        //     'mimeType' => $dataFile['extension']
        // ]);
    
    }


    public function getPathAndExtensionLogo($request, $object = null)
    {

        // dd($request->file('logo'));
        if($request->file('logo')){
            $path = $request->file('logo')->store('images', 'public');
            $extension = $request->file('logo')->getClientOriginalExtension();

            return [
                'path' => $path,
                'extension' => $extension
            ];
        }

    }


    public function getPathAndExtensionBanner($request, $object = null)
    {

        if($request->file('banner')){
            $path = $request->file('banner')->store('images', 'public');
            $extension = $request->file('banner')->getClientOriginalExtension();

            return [
                'path' => $path,
                'extension' => $extension
            ];
        }

    }


    public function uploadMainImage($request)
    {
        if($request->hasFile('images') || $request->hasFile('image')){

            if($request->file('images')){

                foreach($request->file('images') as $file){
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
    
        }
    }
}