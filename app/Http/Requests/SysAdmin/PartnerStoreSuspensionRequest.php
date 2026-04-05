<?php

declare(strict_types=1);

namespace App\Http\Requests\SysAdmin;

use Illuminate\Foundation\Http\FormRequest;

final class PartnerStoreSuspensionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->role === 'admin';
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [];
    }
}
