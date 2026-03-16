<?php

namespace App\Http\Requests\Partner;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{

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
            'name' => 'required|string',
            'description' => 'required|string',
            'price' => 'required',
            // 'stock' => 'required',
        ];
    }


    public function messages()
    {
        return [
            'name.required' => 'O campo nome é obrigatório',
            'description.required' => 'Descrição do veículo é obrigatória',
            'price.required' => 'Preço de venda é obrigatório',
            // 'stock.required' => 'Informe a quantidade em estoque',
        ];
    }
}
