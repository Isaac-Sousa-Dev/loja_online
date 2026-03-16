<?php

namespace App\Http\Controllers;

use App\Http\Requests\Partner\StoreRequest;
use App\Models\Partner;
use App\Models\Store;
use App\Models\StoreHour;
use App\Models\User;
use App\Services\store\StoreService;
use App\Services\store\UploadFileStoreService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class StoreController extends Controller
{

    protected $uploadFileStoreService;
    protected $storeService;

    public function __construct(
        UploadFileStoreService $uploadFileStoreService,
        StoreService $storeService
    )
    {
        $this->uploadFileStoreService = $uploadFileStoreService;
        $this->storeService = $storeService;
    }


    public function newStorePage()
    {
        return view('new-store-page');
    }


    public function newStoreInfo()
    {
        return view('new-store-info');
    }


    public function configuredStore($storeId)
    {
        try {
            $store = Store::find($storeId);
            $store->configured_store = 1;
            $store->update();
            return response()->json(['success' => 'update configured store'], 200);
        } catch(Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }


    public function edit(Request $request)
    {
        $userId = $request->user()->id;
        $partner = Partner::where('user_id', $userId)->with('subscription')->first();

        $store = $partner->store;
        $logoStore = '/storage/'.$store->logo;
        $bannerStore = '/storage/'.$store->banner;

        return view('partner.my-store.edit', [
            'user' => $request->user(),
            'partner' => $partner,
            'logoStore' => $logoStore,
            'bannerStore' => $bannerStore,
            'store' => $store,
            'storeHours' => $store->storeHours
        ]);
    }

    public function update(StoreRequest $request, string $id)
    { 

        // Validação das imagens
        // $request->validate([
        //     'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:512',    // 512KB = 0.5MB
        //     'banner' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048', // 2048KB = 2MB
        // ], [
        //     'logo.max' => 'A logo deve ter no máximo 500KB.',
        //     'banner.max' => 'O banner deve ter no máximo 2MB.',
        // ]);

        $data = $request->all();

        // TODO - Implementar validação do tamanho das imagens
        $banner = ''; 
        $logo = '';
        if($request->file('logo') != null){
            $logo = $this->uploadFileStoreService->getPathAndExtensionLogo($request);
            $data['logo'] = $logo['path'];
        }
        if($request->file('banner') != null){
            $banner = $this->uploadFileStoreService->getPathAndExtensionBanner($request);
            $data['banner'] = $banner['path'];
        }

        $isNewLink = $this->storeService->update($data, $id); 
        return redirect()->route('store.edit')->with('success', 'Loja atualizada com sucesso!')->with('isNewLink', $isNewLink);
    }
    
    public function updateHour(Request $request, string $id)
    { 
        $data = $request->all();
        foreach($data['hours'] as $key => $value){
            $value['is_open'] = isset($value['is_open']) ? 1 : 0;
            $storeHour = StoreHour::where('store_id', $id)->where('day_of_week', $key)->first();
            $storeHour->update($value);
        }
        return redirect()->route('store.edit')->with('success', 'Horário de funcionamento atualizado com sucesso!');
    }

}
