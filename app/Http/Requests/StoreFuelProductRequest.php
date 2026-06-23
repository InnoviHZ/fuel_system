<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreFuelProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()?->hasAnyRole(['admin', 'manager']);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:10', 'unique:fuel_products'],
            'current_price' => ['required', 'numeric', 'min:0', 'max:99999.99'],
            'is_active' => ['boolean'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Fuel product name is required',
            'code.required' => 'Fuel product code is required',
            'code.unique' => 'This fuel product code already exists',
            'current_price.required' => 'Current price is required',
            'current_price.numeric' => 'Price must be a valid number',
            'current_price.min' => 'Price must be at least 0',
        ];
    }
}
