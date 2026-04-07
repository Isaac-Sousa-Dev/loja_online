<?php

declare(strict_types=1);

namespace App\Http\Requests\Partner;

use App\Models\Brand;
use App\Models\StoreCategories;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class IndexProductsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check() && Auth::user()->partner !== null;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'name' => $this->input('name') === '' ? null : $this->input('name'),
            'category_id' => $this->input('category_id') === '' || $this->input('category_id') === null ? null : $this->input('category_id'),
            'brand_id' => $this->input('brand_id') === '' || $this->input('brand_id') === null ? null : $this->input('brand_id'),
            'gender' => $this->input('gender') === '' ? null : $this->input('gender'),
            'status' => $this->input('status') === '' || $this->input('status') === null ? 'all' : $this->input('status'),
        ]);
    }

    /**
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $partner = Auth::user()->partner;
        $storeId = $partner && $partner->store ? $partner->store->id : $partner?->id;

        return [
            'name' => ['sometimes', 'nullable', 'string', 'max:255'],
            'category_id' => [
                'sometimes',
                'nullable',
                'integer',
                Rule::exists((new StoreCategories())->getTable(), 'category_id')->where('store_id', $storeId),
            ],
            'brand_id' => [
                'sometimes',
                'nullable',
                'integer',
                Rule::exists((new Brand())->getTable(), 'id')->where('partner_id', $partner->id),
            ],
            'gender' => ['sometimes', 'nullable', 'string', 'in:feminine,masculine'],
            'status' => ['sometimes', 'nullable', 'string', 'in:all,active,inactive'],
        ];
    }

    /**
     * @return array{name?: string, category_id?: int, brand_id?: int, gender?: string, status: string}
     */
    public function filters(): array
    {
        /** @var array{name?: string|null, category_id?: int|null, brand_id?: int|null, gender?: string|null, status?: string|null} $validated */
        $validated = $this->validated();

        $status = $validated['status'] ?? 'all';
        if ($status === '' || $status === null) {
            $status = 'all';
        }

        $out = ['status' => $status];

        if (! empty($validated['name'])) {
            $out['name'] = (string) $validated['name'];
        }
        if (isset($validated['category_id']) && $validated['category_id'] !== null) {
            $out['category_id'] = (int) $validated['category_id'];
        }
        if (isset($validated['brand_id']) && $validated['brand_id'] !== null) {
            $out['brand_id'] = (int) $validated['brand_id'];
        }
        if (! empty($validated['gender'])) {
            $out['gender'] = (string) $validated['gender'];
        }

        return $out;
    }
}
