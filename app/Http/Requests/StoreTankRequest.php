<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTankRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()?->isAdmin();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255', 'unique:tanks'],
            'fuel_product_id' => ['required', 'exists:fuel_products,id'],
            'capacity_litres' => ['required', 'numeric', 'min:0', 'max:999999.99'],
            'current_stock_litres' => ['required', 'numeric', 'min:0', 'max:999999.99'],
            'low_stock_threshold' => ['required', 'numeric', 'min:0', 'max:999999.99'],
            'critical_stock_threshold' => ['required', 'numeric', 'min:0', 'max:999999.99'],
            'is_active' => ['boolean'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Tank name is required',
            'name.unique' => 'This tank name already exists',
            'fuel_product_id.required' => 'Please select a fuel product',
            'fuel_product_id.exists' => 'Selected fuel product does not exist',
            'capacity_litres.required' => 'Tank capacity is required',
            'current_stock_litres.required' => 'Current stock is required',
            'low_stock_threshold.required' => 'Low stock threshold is required',
            'critical_stock_threshold.required' => 'Critical stock threshold is required',
        ];
    }
}
