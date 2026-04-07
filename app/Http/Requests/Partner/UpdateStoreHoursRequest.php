<?php

declare(strict_types=1);

namespace App\Http\Requests\Partner;

use App\Models\Partner;
use Illuminate\Foundation\Http\FormRequest;

class UpdateStoreHoursRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = $this->user();
        if ($user === null) {
            return false;
        }

        $partner = Partner::query()->where('user_id', $user->id)->first();
        if ($partner === null) {
            return false;
        }

        $store = $partner->store;
        if ($store === null) {
            return false;
        }

        return (string) $store->id === (string) $this->route('id');
    }

    /**
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $time = ['nullable', 'date_format:H:i'];

        return [
            'weekday_open' => array_merge($time, ['required_with:weekday_close']),
            'weekday_close' => array_merge($time, ['required_with:weekday_open']),
            'saturday_open' => array_merge($time, ['required_with:saturday_close']),
            'saturday_close' => array_merge($time, ['required_with:saturday_open']),
            'sunday_open' => array_merge($time, ['required_with:sunday_close']),
            'sunday_close' => array_merge($time, ['required_with:sunday_open']),
        ];
    }

    protected function prepareForValidation(): void
    {
        $keys = [
            'weekday_open',
            'weekday_close',
            'saturday_open',
            'saturday_close',
            'sunday_open',
            'sunday_close',
        ];

        $merged = [];
        foreach ($keys as $key) {
            $value = $this->input($key);
            $merged[$key] = ($value === '' || $value === null) ? null : $value;
        }

        $this->merge($merged);
    }
}
