<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCartRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'                    => ['required', 'string', 'max:255'],
            'phone'                   => ['required', 'string', 'max:30'],
            'store_id'                => ['required', 'integer', 'exists:stores,id'],
            'items'                   => ['required', 'array', 'min:1'],
            'items.*.product_id'      => ['required', 'integer', 'exists:products,id'],
            'items.*.quantity'        => ['required', 'integer', 'min:1'],
            'items.*.variant_id'      => ['nullable', 'integer', 'exists:product_variants,id'],
            'items.*.color'           => ['nullable', 'string', 'max:100'],
            'items.*.size'            => ['nullable', 'string', 'max:50'],
            'message'                 => ['nullable', 'string'],
            'payment_method'          => ['nullable', 'string', 'in:pix,credit_card,cash'],
            'delivery_type'           => ['nullable', 'string', 'in:pickup,delivery'],
            'delivery_address'        => ['nullable', 'string', 'max:255'],
            'delivery_city'           => ['nullable', 'string', 'max:100'],
            'delivery_state'          => ['nullable', 'string', 'max:2'],
            'delivery_zip'            => ['nullable', 'string', 'max:15'],
            'delivery_complement'     => ['nullable', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'items.required'               => 'O carrinho está vazio.',
            'items.min'                    => 'O carrinho está vazio.',
            'items.*.product_id.required'  => 'Produto inválido no carrinho.',
            'items.*.product_id.exists'    => 'Produto não encontrado.',
            'items.*.quantity.min'         => 'A quantidade mínima é 1.',
        ];
    }
}
