<?php

declare(strict_types=1);

namespace App\Http\Requests\Partner;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'banner' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'wholesale_min_quantity' => 'nullable|integer|min:2',
            'wholesale_count_mode' => ['nullable', Rule::in(['product', 'cart'])],
            'wholesale_levels' => 'nullable|array',
            'wholesale_levels.*.min_quantity' => 'nullable|integer|min:2',
            'accepted_payment_methods' => 'nullable|array',
            'accepted_payment_methods.*' => 'string|in:pix,cash,credit_card,debit_card,boleto',
            'accepted_card_brands' => 'nullable|array',
            'accepted_card_brands.*' => 'string|in:visa,mastercard,elo,amex,hipercard,diners',
        ];
    }

    public function messages(): array
    {
        return [
            'logo.max' => 'A logo deve ter no máximo 2MB.',
            'banner.max' => 'O banner deve ter no máximo 2MB.',
            'wholesale_levels.*.min_quantity.min' => 'Cada nível de atacado deve ter no mínimo 2 peças.',
            'accepted_payment_methods.*.in' => 'Selecione um método de pagamento válido.',
            'accepted_card_brands.*.in' => 'Selecione uma bandeira de cartão válida.',
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator): void {
            $levels = $this->normalizedWholesaleLevels();
            if ($levels === []) {
                return;
            }

            $quantities = array_column($levels, 'min_quantity');
            if (count($quantities) !== count(array_unique($quantities))) {
                $validator->errors()->add('wholesale_levels', 'Os níveis de atacado não podem repetir a mesma quantidade mínima.');
            }

            $sorted = $quantities;
            sort($sorted);
            if ($sorted !== $quantities) {
                $validator->errors()->add('wholesale_levels', 'As quantidades mínimas do atacado devem estar em ordem crescente.');
            }
        });
    }

    /**
     * @return array<int, array{min_quantity:int}>
     */
    public function normalizedWholesaleLevels(): array
    {
        $rawLevels = $this->input('wholesale_levels', []);
        if (! is_array($rawLevels)) {
            return [];
        }

        $levels = [];
        foreach ($rawLevels as $level) {
            if (! is_array($level)) {
                continue;
            }

            $quantity = (int) ($level['min_quantity'] ?? 0);
            if ($quantity < 2) {
                continue;
            }

            $levels[] = [
                'min_quantity' => $quantity,
            ];
        }

        return array_values($levels);
    }
}
