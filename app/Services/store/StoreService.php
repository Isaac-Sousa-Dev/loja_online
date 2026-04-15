<?php

declare(strict_types=1);

namespace App\Services\store;

use App\Enums\WholesaleCountMode;
use App\Interfaces\AbstractServiceInterface;
use App\Models\AddressStore;
use App\Models\Store;
use App\Models\StoreHour;
use App\Models\StoreWholesaleLevel;
use App\Repository\store\StoreRepository;
use App\Services\requests\RequestPlanService;
use Illuminate\Support\Facades\DB;

class StoreService implements AbstractServiceInterface {

    protected $storeModel;
    protected $requestPlanService;
    protected $storeRepository;
    public $partnerLink;

    public function __construct(
        Store $storeModel,
        RequestPlanService $requestPlanService,
        StoreRepository $storeRepository,
    )
    {
        $this->storeModel = $storeModel;
        $this->requestPlanService = $requestPlanService;
        $this->storeRepository = $storeRepository;
    }

    public function insert(array $data, $request = null)
    {
        $store = $this->storeModel->create($data);
        foreach(range(0, 6) as $i) {
            StoreHour::create([
                'store_id' => $store->id,
                'day_of_week' => $i,
                'open_time' => '08:00',
                'close_time' => '18:00',
                'is_open' => $i == 0 ? 0 : 1
            ]);
        }
    }

    public function update($data, $storeId)
    { 
        $store = Store::with('partner')->findOrFail($storeId);
        $this->partnerLink = $store->partner->partner_link;
        $response = $this->checkDiferentStoreName($data, $store);

        $data['accepted_payment_methods'] = $this->sanitizeSelection($data['accepted_payment_methods'] ?? null);
        $data['accepted_card_brands'] = $this->sanitizeSelection($data['accepted_card_brands'] ?? null);
        $data['wholesale_count_mode'] = $this->sanitizeWholesaleCountMode($data['wholesale_count_mode'] ?? 'cart');
        $wholesaleLevels = $this->sanitizeWholesaleLevels($data['wholesale_levels'] ?? null);

        $addressStore = AddressStore::firstOrCreate(['store_id' => $storeId]);

        DB::transaction(function () use ($addressStore, $data, $store, $wholesaleLevels): void {
            $addressStore->update($data);
            $store->update($data);
            $this->syncWholesaleLevels($store, $wholesaleLevels);
        });

        return $response;
    }

    public function delete($model)
    {}

    public function find($id)
    {}

    public function findAll()
    {}

    public function checkDiferentStoreName($data, $store)
    {
        if($data['store_name'] != $store->store_name):
            $this->partnerLink = strtolower(str_replace(' ', '-', $data['store_name']));
            $this->updatePartnerLink($store);
            return true;    
        endif;
        return false;
    }

    public function updatePartnerLink($store)
    {
        $store->partner->update([
            'partner_link' => $this->partnerLink
        ]);
    }

    private function sanitizeSelection(mixed $value): ?array
    {
        if (! is_array($value)) {
            return null;
        }

        $selection = array_values(array_filter($value, static fn (mixed $item): bool => is_string($item) && $item !== ''));

        return $selection === [] ? null : $selection;
    }

    private function sanitizeWholesaleCountMode(mixed $value): string
    {
        $normalized = is_string($value) ? trim($value) : '';

        return WholesaleCountMode::tryFrom($normalized)?->value ?? WholesaleCountMode::PRODUCT->value;
    }

    /**
     * @return array<int, array{position:int,label:string,min_quantity:int}>
     */
    private function sanitizeWholesaleLevels(mixed $value): array
    {
        if (! is_array($value)) {
            return [];
        }

        $levels = [];
        foreach ($value as $index => $row) {
            if (! is_array($row)) {
                continue;
            }

            $minQuantity = (int) ($row['min_quantity'] ?? 0);
            if ($minQuantity < 2) {
                continue;
            }

            $position = count($levels) + 1;
            $levels[] = [
                'position' => $position,
                'label' => 'Atacado '.$position,
                'min_quantity' => $minQuantity,
            ];
        }

        return $levels;
    }

    /**
     * @param array<int, array{position:int,label:string,min_quantity:int}> $levels
     */
    private function syncWholesaleLevels(Store $store, array $levels): void
    {
        $store->loadMissing('wholesaleLevels');
        $existingIds = [];

        foreach ($levels as $levelData) {
            $level = StoreWholesaleLevel::query()->updateOrCreate(
                [
                    'store_id' => $store->id,
                    'position' => $levelData['position'],
                ],
                [
                    'label' => $levelData['label'],
                    'min_quantity' => $levelData['min_quantity'],
                ]
            );

            $existingIds[] = $level->id;
        }

        if ($existingIds === []) {
            $store->wholesaleLevels()->delete();

            return;
        }

        $store->wholesaleLevels()
            ->whereNotIn('id', $existingIds)
            ->delete();
    }
}