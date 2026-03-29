<?php

declare(strict_types=1);

namespace App\Services\product;

use App\Models\Image;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
class ProductColorImageService
{
    /**
     * URL pública para exibição (path gravado pelo store('public') ou legado com prefixo public/).
     */
    public function publicUrlForStoredPath(string $storedPath): string
    {
        $trimmed = ltrim($storedPath, '/');
        if (str_starts_with($trimmed, 'public/')) {
            $trimmed = substr($trimmed, strlen('public/'));
        }

        return asset('storage/'.$trimmed);
    }

    /**
     * Payload para o wizard de edição: [nomeDaCor => [{ serverImageId, previewUrl, isCapa, posicao, label }]].
     *
     * @return array<string, list<array{serverImageId: int, previewUrl: string, isCapa: bool, posicao: int, label: string}>>
     */
    public function buildWizardExistingPhotosByColor(int $productId): array
    {
        $images = Image::query()
            ->where('product_id', $productId)
            ->whereNotNull('variant_color')
            ->where('variant_color', '!=', '')
            ->orderBy('index')
            ->orderBy('id')
            ->get();

        $out = [];
        foreach ($images as $image) {
            $color = (string) $image->variant_color;
            if ($color === '') {
                continue;
            }
            if (! isset($out[$color])) {
                $out[$color] = [];
            }
            $out[$color][] = [
                'serverImageId' => (int) $image->id,
                'previewUrl'    => $this->publicUrlForStoredPath((string) $image->url),
                'isCapa'        => (bool) $image->is_cover,
                'posicao'       => (int) $image->index,
                'label'         => basename((string) $image->url) ?: 'foto',
            ];
        }

        return $out;
    }

    public function deleteRemovedWizardImages(Product $product, Request $request): void
    {
        $raw = $request->input('color_photos_removed_ids');
        if ($raw === null || $raw === '') {
            return;
        }

        $ids = json_decode((string) $raw, true);
        if (! is_array($ids)) {
            return;
        }

        $ids = array_values(array_unique(array_filter(
            array_map(static fn ($v): int => (int) $v, $ids),
            static fn (int $id): bool => $id > 0
        )));

        if ($ids === []) {
            return;
        }

        Image::query()
            ->where('product_id', $product->id)
            ->whereIn('id', $ids)
            ->delete();
    }

    /**
     * Aplica capa escolhida no wizard para imagens já salvas (uma por cor).
     *
     * @param  array<string, int>|null  $map
     */
    public function applyWizardCoverSelection(int $productId, ?string $json): void
    {
        if ($json === null || $json === '') {
            return;
        }

        /** @var mixed $decoded */
        $decoded = json_decode($json, true);
        if (! is_array($decoded)) {
            return;
        }

        foreach ($decoded as $color => $imageId) {
            $colorStr = (string) $color;
            $id = (int) $imageId;
            if ($colorStr === '' || $id < 1) {
                continue;
            }

            $img = Image::query()
                ->where('product_id', $productId)
                ->where('id', $id)
                ->where('variant_color', $colorStr)
                ->first();

            if ($img === null) {
                continue;
            }

            Image::query()
                ->where('product_id', $productId)
                ->where('variant_color', $colorStr)
                ->update(['is_cover' => false]);

            $img->update(['is_cover' => true]);
        }
    }

    /**
     * Garante exatamente uma capa por variant_color quando houver ambiguidade (ex.: após novos uploads).
     */
    public function normalizeCoverFlagsPerVariantColor(int $productId): void
    {
        $colors = Image::query()
            ->where('product_id', $productId)
            ->whereNotNull('variant_color')
            ->distinct()
            ->pluck('variant_color');

        foreach ($colors as $color) {
            if ($color === null || $color === '') {
                continue;
            }

            $images = Image::query()
                ->where('product_id', $productId)
                ->where('variant_color', $color)
                ->orderBy('index')
                ->orderBy('id')
                ->get();

            if ($images->isEmpty()) {
                continue;
            }

            $cover = $images->firstWhere('is_cover', true) ?? $images->first();
            foreach ($images as $image) {
                $shouldCover = $image->id === $cover->id;
                if ($image->is_cover !== $shouldCover) {
                    $image->update(['is_cover' => $shouldCover]);
                }
            }
        }
    }

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

            if ($isCover && $color !== '') {
                Image::query()
                    ->where('product_id', $product->id)
                    ->where('variant_color', $color)
                    ->update(['is_cover' => false]);
            }

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

    /**
     * Define products.image_main a partir da galeria (capa marcada ou primeira imagem por índice).
     */
    public function syncProductMainImageFromGallery(int $productId): void
    {
        $product = Product::find($productId);
        if ($product === null) {
            return;
        }

        $cover = Image::where('product_id', $productId)->where('is_cover', true)->orderBy('index')->first();
        if ($cover === null) {
            $cover = Image::where('product_id', $productId)->orderBy('index')->first();
        }
        if ($cover === null) {
            return;
        }

        $product->update(['image_main' => $cover->url]);
    }
}
