<?php

declare(strict_types=1);

namespace App\Http\Requests\Partner;

use App\Models\Product;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateProductVisibilityRequest extends FormRequest
{
    public function authorize(): bool
    {
        if (! Auth::check() || Auth::user()->partner === null) {
            return false;
        }

        $product = $this->route('product');
        if (! $product instanceof Product) {
            return false;
        }

        return $product->partner_id === Auth::user()->partner->id;
    }

    /**
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'is_active' => ['required', 'boolean'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $raw = $this->input('is_active');
        if ($raw === '0' || $raw === 0 || $raw === 'false' || $raw === false) {
            $this->merge(['is_active' => false]);

            return;
        }
        if ($raw === '1' || $raw === 1 || $raw === 'true' || $raw === true) {
            $this->merge(['is_active' => true]);
        }
    }
}
