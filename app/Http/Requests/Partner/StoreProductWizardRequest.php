<?php

declare(strict_types=1);

namespace App\Http\Requests\Partner;

use App\Models\Brand;
use App\Models\StoreCategories;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class StoreProductWizardRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check() && Auth::user()->partner !== null;
    }

    public function rules(): array
    {
        $partner = Auth::user()->partner;
        $storeId = $partner && $partner->store ? $partner->store->id : $partner?->id;

        return [
            'category_id'   => [
                'required',
                'integer',
                Rule::exists((new StoreCategories())->getTable(), 'category_id')->where('store_id', $storeId),
            ],
            'brand_id'      => [
                'required',
                'integer',
                Rule::exists((new Brand())->getTable(), 'id')->where('partner_id', $partner->id),
            ],
            'name'          => ['required', 'string', 'max:255'],
            'description'   => ['required', 'string'],
            'price'         => ['required', 'string'],
            'price_wholesale' => ['nullable', 'string'],
            'price_promotional' => ['nullable', 'string'],
            'cost'          => ['nullable', 'string'],
            'profit'        => ['nullable', 'string'],
            'gender'        => ['nullable', 'string', 'in:M,F,U'],
            'weight'        => ['nullable', 'numeric'],
            'width'         => ['nullable', 'numeric'],
            'height'        => ['nullable', 'numeric'],
            'length'        => ['nullable', 'numeric'],
            'installments'  => ['nullable', 'integer', 'min:1', 'max:12'],
            'discount_pix'  => ['nullable', 'numeric', 'min:0', 'max:100'],
            'variants_payload' => ['required', 'string'],
            'color_photos_flat' => ['nullable', 'string'],
            'color_photo_files' => ['nullable', 'array'],
            'color_photo_files.*' => ['nullable', 'file', 'max:5120'],
            'color_photos_removed_ids' => ['nullable', 'string'],
            'color_photos_cover_by_color' => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'O campo nome é obrigatório',
            'description.required' => 'Descrição do produto é obrigatória',
            'price.required' => 'Preço de venda é obrigatório',
            'category_id.required' => 'Selecione uma categoria',
            'brand_id.required' => 'Selecione uma marca',
            'variants_payload.required' => 'Gere as variantes antes de salvar',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'name' => $this->input('name'),
            'profit' => $this->normalizeProfit($this->input('profit')),
            'price' => $this->normalizePriceString($this->input('price')),
            'price_wholesale' => $this->normalizePriceString($this->input('price_wholesale')),
            'price_promotional' => $this->normalizePriceString($this->input('price_promotional')),
            'cost' => $this->normalizePriceString($this->input('cost')),
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    public function getProductAttributes(): array
    {
        /** @var array<string, mixed> */
        $attrs = $this->only([
            'name',
            'description',
            'price',
            'price_wholesale',
            'price_promotional',
            'cost',
            'profit',
            'brand_id',
            'category_id',
            'weight',
            'width',
            'height',
            'length',
            'installments',
            'discount_pix',
        ]);
        $attrs['gender'] = $this->mapGender($this->input('gender'));

        return $attrs;
    }

    private function normalizeProfit(mixed $value): ?string
    {
        if ($value === null || $value === '') {
            return null;
        }
        $s = is_string($value) ? $value : (string) $value;

        return str_replace('%', '', trim($s));
    }

    private function normalizePriceString(mixed $value): ?string
    {
        if ($value === null || $value === '') {
            return null;
        }

        return is_string($value) ? $value : (string) $value;
    }

    private function mapGender(mixed $value): ?string
    {
        if ($value === null || $value === '') {
            return null;
        }
        $g = is_string($value) ? $value : (string) $value;

        return match ($g) {
            'M' => 'masculine',
            'F' => 'feminine',
            'U' => null,
            default => null,
        };
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator): void {
            $raw = $this->input('variants_payload');
            $decoded = is_string($raw) ? json_decode($raw, true) : null;
            if (! is_array($decoded) || $decoded === []) {
                $validator->errors()->add('variants_payload', 'Informe ao menos uma variante com estoque.');

                return;
            }
            $totalStock = 0;
            foreach ($decoded as $row) {
                if (! is_array($row)) {
                    continue;
                }
                $totalStock += max(0, (int) ($row['stock'] ?? 0));
            }
            if ($totalStock < 1) {
                $validator->errors()->add('variants_payload', 'Adicione estoque em ao menos uma variante.');
            }

            $flatJson = $this->input('color_photos_flat');
            if ($flatJson === null || $flatJson === '') {
                return;
            }
            $flat = json_decode((string) $flatJson, true);
            $files = $this->file('color_photo_files');
            if (! is_array($flat) || ! is_array($files)) {
                return;
            }
            if (count($flat) !== count($files)) {
                $validator->errors()->add('color_photo_files', 'Contagem de fotos por cor não confere com os arquivos enviados.');
            }
        });
    }
}
