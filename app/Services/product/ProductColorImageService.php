<?php

declare(strict_types=1);

namespace App\Services\product;

use App\Models\Image;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
class ProductColorImageService
{
    public function storeWizardUploads(Product $product, Request $request): void
    {
        $flatJson = $request->input('color_photos_flat');
        if ($flatJson === null || $flatJson === '') {
            return;
        }

        /** @var list<array{color: string, is_cover: bool}>|null $flat */
        $flat = json_decode((string) $flatJson, true);
        if (! is_array($flat) || $flat === []) {
            return;
        }

        /** @var array<int, UploadedFile>|null $files */
        $files = $request->file('color_photo_files');
        if (! is_array($files)) {
            return;
        }

        if (count($files) !== count($flat)) {
            return;
        }

        $baseIndex = (int) (Image::where('product_id', $product->id)->max('index') ?? 0);

        foreach ($flat as $i => $meta) {
            if (! isset($files[$i]) || ! $files[$i] instanceof UploadedFile) {
                continue;
            }
            $color = isset($meta['color']) ? (string) $meta['color'] : '';
            $isCover = ! empty($meta['is_cover']);

            $file = $files[$i];
            $path = $file->store('public');
            $extension = $file->getClientOriginalExtension() ?: 'jpg';

            ++$baseIndex;

            Image::create([
                'product_id'    => $product->id,
                'variant_color' => $color !== '' ? $color : null,
                'url'           => $path,
                'index'         => $baseIndex,
                'mimeType'      => $extension,
                'is_cover'      => $isCover,
            ]);
        }
    }
}
