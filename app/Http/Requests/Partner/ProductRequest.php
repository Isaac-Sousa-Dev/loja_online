<?php

declare(strict_types=1);

namespace App\Http\Requests\Partner;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string',
            'description' => 'required|string',
            'price' => 'required',
            'price_wholesale' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'O campo nome é obrigatório',
            'description.required' => 'Descrição do produto é obrigatória',
            'price.required' => 'Preço de venda é obrigatório',
        ];
    }

    protected function prepareForValidation(): void
    {
        $gender = $this->input('gender');
        $mapped = null;
        if (is_string($gender) && $gender !== '') {
            $mapped = match ($gender) {
                'M' => 'masculine',
                'F' => 'feminine',
                'U' => null,
                default => $gender,
            };
        }

        $profit = $this->input('profit');
        $profitClean = null;
        if ($profit !== null && $profit !== '') {
            $profitClean = str_replace('%', '', is_string($profit) ? $profit : (string) $profit);
        }

        $this->merge([
            'gender' => $mapped,
            'profit' => $profitClean,
        ]);
    }
}
