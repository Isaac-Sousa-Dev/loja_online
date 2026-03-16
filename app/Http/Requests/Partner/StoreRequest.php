<?php

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
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',    // 2048KB = 2MB
            'banner' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048', // 2048KB = 2MB
        ];
    }

    public function messages()
    {
        return [
            'logo.max' => 'A logo deve ter no máximo 2MB.',
            'banner.max' => 'O banner deve ter no máximo 2MB.'
        ];
    }
}
