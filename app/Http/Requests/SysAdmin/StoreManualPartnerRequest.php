<?php

declare(strict_types=1);

namespace App\Http\Requests\SysAdmin;

use Illuminate\Foundation\Http\FormRequest;

class StoreManualPartnerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null && $this->user()->role === 'admin';
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['required', 'string', 'max:32'],
            'store_name' => ['required', 'string', 'max:255'],
            'plan_id' => ['required', 'integer', 'exists:plans,id'],
            'start_date' => ['required', 'date'],
            'payment_method' => ['nullable', 'string', 'max:64'],
            'qtd_vehicles_in_stock' => ['nullable', 'string', 'max:64'],
            'store_cpf_cnpj' => ['nullable', 'string', 'max:32'],
            'is_testing' => ['sometimes', 'boolean'],
            'grant_access' => ['sometimes', 'boolean'],
            'manual_receipt' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'plan_id.exists' => 'O plano selecionado é inválido.',
        ];
    }

    protected function prepareForValidation(): void
    {
        $rawGrant = $this->input('grant_access');
        $grantAccess = false;
        if (is_array($rawGrant)) {
            $grantAccess = in_array('1', $rawGrant, true);
        } else {
            $grantAccess = (string) $rawGrant === '1';
        }

        $this->merge([
            'is_testing' => $this->boolean('is_testing'),
            'grant_access' => $grantAccess,
        ]);
    }
}
