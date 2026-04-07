<?php

namespace App\Http\Requests\Partner;

use Illuminate\Foundation\Http\FormRequest;

class ClientRequest extends FormRequest
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

        dd($this->all());

        return [
            'name' => 'required|string',
            'email' => 'nullable|email',
            'phone' => 'nullable|string',
            'status' => 'nullable|string',
            'address' => 'required|string',
            'city' => 'nullable|string',
            'zip_code' => 'nullable|string',
            'neighborhood' => 'required|string',
            'number' => 'required|string'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'O campo nome é obrigatório',
            'email.required' => 'O campo email é obrigatório',
            'email.email' => 'O campo email deve ser um email válido',
            'phone.required' => 'O campo telefone é obrigatório',
            'status.required' => 'O campo status é obrigatório',
            'address.required' => 'O campo endereço é obrigatório',
            'city.required' => 'O campo cidade é obrigatório',
            'zip_code.required' => 'O campo CEP é obrigatório',
            'neighborhood.required' => 'O campo bairro é obrigatório',
            'number.required' => 'O campo número é obrigatório'
        ];
    }
}
