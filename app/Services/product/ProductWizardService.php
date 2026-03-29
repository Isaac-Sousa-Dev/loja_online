<?php

declare(strict_types=1);

namespace App\Services\product;

use App\Http\Requests\Partner\StoreProductWizardRequest;
use App\Http\Requests\Partner\UpdateProductWizardRequest;
use App\Models\Partner;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class ProductWizardService
{
    public function __construct(
        private readonly ProductService $productService,
        private readonly ProductVariantSyncService $variantSyncService,
        private readonly ProductColorImageService $colorImageService,
    ) {
    }

    public function createPartnerProduct(Partner $partner, StoreProductWizardRequest $request): Product
    {
        return DB::transaction(function () use ($partner, $request): Product {
            $data = $request->getProductAttributes();
            $data['partner_id'] = $partner->id;

            $product = $this->productService->insert($data, $request);

            $variants = json_decode((string) $request->input('variants_payload', ''), true);
            if (is_array($variants) && $variants !== []) {
                $this->variantSyncService->sync($product->id, $variants);
            }

            $this->colorImageService->storeWizardUploads($product, $request);
            $this->colorImageService->syncProductMainImageFromGallery($product->id);

            return $product->fresh();
        });
    }

    public function updatePartnerProduct(Partner $partner, Product $product, UpdateProductWizardRequest $request): Product
    {
        if ($product->partner_id !== $partner->id) {
            abort(403);
        }

        return DB::transaction(function () use ($product, $request): Product {
            $data = $request->getProductAttributes();
            $this->productService->applyWizardAttributesToExistingProduct($data, $product);

            $variants = json_decode((string) $request->input('variants_payload', ''), true);
            if (is_array($variants) && $variants !== []) {
                $this->variantSyncService->sync($product->id, $variants);
            }

            $this->colorImageService->storeWizardUploads($product->fresh(), $request);
            $this->colorImageService->syncProductMainImageFromGallery($product->id);

            return $product->fresh();
        });
    }
}
