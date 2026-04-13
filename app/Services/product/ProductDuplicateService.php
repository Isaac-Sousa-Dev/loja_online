<?php

declare(strict_types=1);

namespace App\Services\product;

use App\Models\Image;
use App\Models\Partner;
use App\Models\Product;
use App\Models\ProductWholesalePrice;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Throwable;

class ProductDuplicateService
{
    public function __construct(
        private readonly ProductColorImageService $colorImageService,
    ) {
    }

    /**
     * Clona produto (dados, variantes, imagens em disco). A cópia nasce inativa para revisão.
     */
    public function duplicateForPartner(Partner $partner, Product $source): Product
    {
        if ($source->partner_id !== $partner->id) {
            abort(403);
        }

        return DB::transaction(function () use ($partner, $source): Product {
            $source->load(['allVariants', 'images', 'wholesalePrices']);

            $copyName = $this->buildCopyName((string) $source->name);

            $newProduct = Product::create($this->productAttributesForCopy($source, $partner->id, $copyName));

            foreach ($source->allVariants as $variant) {
                ProductVariant::create([
                    'product_id' => $newProduct->id,
                    'color' => $variant->color,
                    'color_hex' => $variant->color_hex,
                    'size' => $variant->size,
                    'stock' => $variant->stock,
                    'price_override' => $variant->price_override,
                    'sku' => null,
                    'active' => (bool) $variant->active,
                ]);
            }

            foreach ($source->wholesalePrices as $wholesalePrice) {
                ProductWholesalePrice::create([
                    'product_id' => $newProduct->id,
                    'store_wholesale_level_id' => $wholesalePrice->store_wholesale_level_id,
                    'price' => $wholesalePrice->price,
                ]);
            }

            if ($source->allVariants->isNotEmpty()) {
                $totalStock = ProductVariant::where('product_id', $newProduct->id)->sum('stock');
                $newProduct->update(['stock' => $totalStock]);
            }

            foreach ($source->images as $image) {
                $this->cloneImageRow($newProduct->id, $image);
            }

            $this->colorImageService->syncProductMainImageFromGallery($newProduct->id);

            return $newProduct->fresh(['images', 'brand', 'category']);
        });
    }

    /**
     * @return array<string, mixed>
     */
    private function productAttributesForCopy(Product $source, int $partnerId, string $name): array
    {
        $attrs = $source->only([
            'description',
            'price',
            'price_wholesale',
            'price_promotional',
            'cost',
            'profit',
            'color',
            'brand_id',
            'category_id',
            'old_price',
            'installments',
            'discount_pix',
            'gender',
            'width',
            'height',
            'length',
            'weight',
            'size',
            'stock',
        ]);
        $attrs['name'] = $name;
        $attrs['partner_id'] = $partnerId;
        $attrs['is_active'] = false;
        $attrs['image_main'] = null;

        return $attrs;
    }

    private function buildCopyName(string $original): string
    {
        $suffix = ' (cópia)';
        $base = $original;
        if (str_ends_with(mb_strtolower($base), mb_strtolower($suffix))) {
            $base = mb_substr($base, 0, -mb_strlen($suffix));
        }
        $candidate = $base.$suffix;
        if (mb_strlen($candidate) <= 255) {
            return $candidate;
        }

        $max = 255 - mb_strlen($suffix);

        return mb_substr($base, 0, max(1, $max)).$suffix;
    }

    private function cloneImageRow(int $newProductId, Image $image): void
    {
        $relative = $this->normalizeStorageRelativePath((string) $image->url);
        if ($relative === '') {
            return;
        }

        $disk = Storage::disk('public');
        if (! $disk->exists($relative)) {
            Log::warning('Product duplicate: image file missing', ['path' => $relative, 'image_id' => $image->id]);

            return;
        }

        $ext = pathinfo($relative, PATHINFO_EXTENSION) ?: 'jpg';
        $dest = 'products/duplicates/'.Str::uuid()->toString().'.'.$ext;

        try {
            $disk->copy($relative, $dest);
        } catch (Throwable $e) {
            Log::error('Product duplicate: copy failed', ['exception' => $e, 'from' => $relative]);

            return;
        }

        $newUrl = 'public/'.$dest;

        Image::create([
            'product_id' => $newProductId,
            'variant_color' => $image->variant_color,
            'index' => $image->index,
            'url' => $newUrl,
            'mimeType' => $image->mimeType,
            'is_cover' => (bool) $image->is_cover,
        ]);
    }

    private function normalizeStorageRelativePath(string $storedUrl): string
    {
        $t = ltrim($storedUrl, '/');
        if (str_starts_with($t, 'public/')) {
            return substr($t, strlen('public/'));
        }

        return $t;
    }
}
