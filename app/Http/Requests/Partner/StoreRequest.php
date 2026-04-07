<?php

declare(strict_types=1);

namespace App\Http\Requests\Partner;

use Illuminate\Foundation\Http\FormRequest;

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
            'accepted_payment_methods.*.in' => 'Selecione um método de pagamento válido.',
            'accepted_card_brands.*.in' => 'Selecione uma bandeira de cartão válida.',
        ];
    }
}
