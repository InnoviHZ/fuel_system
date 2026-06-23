<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDeliveryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->hasAnyRole(['supervisor', 'admin']);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'tank_id' => 'required|integer|exists:tanks,id',
            'supplier_name' => 'required|string|max:255',
            'waybill_number' => 'required|string|unique:deliveries,waybill_number',
            'quantity_litres' => 'required|numeric|min:0.01',
            'unit_cost' => 'required|numeric|min:0.01',
            'delivery_date' => 'required|date|date_format:Y-m-d',
            'notes' => 'nullable|string|max:1000',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'tank_id.required' => 'Tank selection is required',
            'tank_id.exists' => 'Selected tank does not exist',
            'supplier_name.required' => 'Supplier name is required',
            'supplier_name.max' => 'Supplier name cannot exceed 255 characters',
            'waybill_number.required' => 'Waybill number is required',
            'waybill_number.unique' => 'This waybill number already exists',
            'quantity_litres.required' => 'Quantity is required',
            'quantity_litres.numeric' => 'Quantity must be a number',
            'quantity_litres.min' => 'Quantity must be greater than zero',
            'unit_cost.required' => 'Unit cost is required',
            'unit_cost.numeric' => 'Unit cost must be a number',
            'unit_cost.min' => 'Unit cost must be greater than zero',
            'delivery_date.required' => 'Delivery date is required',
            'delivery_date.date' => 'Delivery date must be a valid date',
            'delivery_date.date_format' => 'Delivery date must be in YYYY-MM-DD format',
            'notes.max' => 'Notes cannot exceed 1000 characters',
        ];
    }
}
