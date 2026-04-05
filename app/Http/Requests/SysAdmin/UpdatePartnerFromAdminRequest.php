<?php

declare(strict_types=1);

namespace App\Http\Requests\SysAdmin;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class UpdatePartnerFromAdminRequest extends FormRequest
{
    public function authorize(): bool
    {
        if ($this->user() === null || $this->user()->role !== 'admin') {
            return false;
        }

        $target = $this->targetUser();

        return $target !== null
            && $target->role === 'partner'
            && $target->partner !== null
            && $target->partner->store !== null
            && $target->partner->subscription !== null;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $userId = (int) $this->route('id', 0);
        $partnerId = $this->targetUser()?->partner?->id;

        $partnerLinkRules = [
            'nullable',
            'string',
            'max:255',
            'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/',
        ];
        if ($partnerId !== null) {
            $partnerLinkRules[] = Rule::unique('partners', 'partner_link')->ignore($partnerId);
        }

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique('users', 'email')->ignore($userId)],
            'phone' => ['nullable', 'string', 'max:32'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'partner_link' => $partnerLinkRules,
            'is_testing' => ['sometimes', 'boolean'],
            'store_name' => ['required', 'string', 'max:255'],
            'store_email' => ['nullable', 'string', 'lowercase', 'email', 'max:255'],
            'store_phone' => ['nullable', 'string', 'max:32'],
            'store_cpf_cnpj' => ['nullable', 'string', 'max:32'],
            'qtd_vehicles_in_stock' => ['nullable', 'string', 'max:64'],
            'plan_id' => ['required', 'integer', 'exists:plans,id'],
            'subscription_status' => ['required', 'string', Rule::in(['active', 'pending', 'cancelled'])],
            'start_date' => ['required', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'payment_method' => ['nullable', 'string', 'max:64'],
            'street' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:255'],
            'state' => ['nullable', 'string', 'max:64'],
            'neighborhood' => ['nullable', 'string', 'max:255'],
            'number' => ['nullable', 'string', 'max:32'],
            'zip_code' => ['nullable', 'string', 'max:20'],
            'country' => ['nullable', 'string', 'max:64'],
            'panel_suspended' => ['sometimes', 'boolean'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'partner_link.regex' => 'O link público deve conter apenas letras minúsculas, números e hífens (ex.: minha-loja).',
            'plan_id.exists' => 'O plano selecionado é inválido.',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_testing' => $this->boolean('is_testing'),
            'panel_suspended' => $this->boolean('panel_suspended'),
        ]);
    }

    private function targetUser(): ?User
    {
        $id = $this->route('id');

        if (! is_numeric($id)) {
            return null;
        }

        return User::query()->with(['partner.store', 'partner.subscription'])->find((int) $id);
    }
}
