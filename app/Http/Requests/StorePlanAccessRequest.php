<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePlanAccessRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $phone = preg_replace('/\D/', '', (string) $this->input('phone', ''));
        $this->merge([
            'phone' => $phone,
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $allowedPlans = array_keys(config('vistoo_plans', []));

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['required', 'string', 'digits_between:10,13'],
            'store_name' => ['required', 'string', 'max:255'],
            'qtd_products_in_stock' => ['nullable', 'string', 'max:64'],
            'plan_slug' => ['required', 'string', Rule::in($allowedPlans)],
            'notes' => ['nullable', 'string', 'max:2000'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'name' => 'nome completo',
            'email' => 'e-mail',
            'phone' => 'WhatsApp',
            'store_name' => 'nome da loja',
            'qtd_products_in_stock' => 'volume de produtos',
            'plan_slug' => 'plano',
            'notes' => 'mensagem',
        ];
    }
}
