<?php

namespace App\Http\Controllers;

use App\Actions\Cache\FlushPartnerCatalogAndPanelCachesAction;
use App\Http\Requests\Partner\StoreRequest;
use App\Http\Requests\Partner\UpdateStoreHoursRequest;
use App\Models\Partner;
use App\Models\Store;
use App\Models\StoreHour;
use App\Models\User;
use App\Services\store\StoreService;
use App\Services\store\UploadFileStoreService;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class StoreController extends Controller
{

    protected $uploadFileStoreService;
    protected $storeService;

    public function __construct(
        UploadFileStoreService $uploadFileStoreService,
        StoreService $storeService,
        private readonly FlushPartnerCatalogAndPanelCachesAction $flushPartnerCatalogAndPanelCaches,
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
        $store = Store::query()->findOrFail($id);
        if ($store->partner !== null) {
            $this->flushPartnerCatalogAndPanelCaches->execute($store->partner);
        }
        return redirect()->route('store.edit')->with('success', 'Loja atualizada com sucesso!')->with('isNewLink', $isNewLink);
    }
    
    public function updateHour(UpdateStoreHoursRequest $request, string $id): RedirectResponse
    {
        $validated = $request->validated();
        $storeId = (int) $id;

        $weekdayOpen = $validated['weekday_open'] ?? null;
        $weekdayClose = $validated['weekday_close'] ?? null;
        foreach ([1, 2, 3, 4, 5] as $dayOfWeek) {
            $this->syncStoreHourRow($storeId, $dayOfWeek, $weekdayOpen, $weekdayClose);
        }

        $this->syncStoreHourRow(
            $storeId,
            6,
            $validated['saturday_open'] ?? null,
            $validated['saturday_close'] ?? null,
        );
        $this->syncStoreHourRow(
            $storeId,
            0,
            $validated['sunday_open'] ?? null,
            $validated['sunday_close'] ?? null,
        );

        $store = Store::query()->findOrFail($storeId);
        if ($store->partner !== null) {
            $this->flushPartnerCatalogAndPanelCaches->execute($store->partner);
        }

        return redirect()->route('store.edit')->with('success', 'Horário de funcionamento atualizado com sucesso!');
    }

    private function syncStoreHourRow(int $storeId, int $dayOfWeek, ?string $openTime, ?string $closeTime): void
    {
        $isOpen = $openTime !== null && $closeTime !== null;

        $payload = [
            'open_time' => $isOpen ? $openTime : null,
            'close_time' => $isOpen ? $closeTime : null,
            'is_open' => $isOpen ? 1 : 0,
        ];

        $storeHour = StoreHour::query()
            ->where('store_id', $storeId)
            ->where('day_of_week', $dayOfWeek)
            ->first();

        if ($storeHour === null) {
            StoreHour::query()->create(array_merge(
                [
                    'store_id' => $storeId,
                    'day_of_week' => $dayOfWeek,
                ],
                $payload,
            ));

            return;
        }

        $storeHour->update($payload);
    }

}
