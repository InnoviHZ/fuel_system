<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSaleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->hasRole('attendant');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'pump_id' => 'required|exists:pumps,id',
            'quantity_litres' => 'required|numeric|min:0.01',
            'payment_method' => 'required|in:cash,pos,bank_transfer,credit',
            'credit_customer_id' => 'required_if:payment_method,credit|nullable|exists:credit_customers,id',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'pump_id.required' => 'Pump selection is required',
            'pump_id.exists' => 'Selected pump does not exist',
            'quantity_litres.required' => 'Quantity is required',
            'quantity_litres.numeric' => 'Quantity must be a number',
            'quantity_litres.min' => 'Quantity must be greater than 0',
            'payment_method.required' => 'Payment method is required',
            'payment_method.in' => 'Invalid payment method selected',
            'credit_customer_id.required_if' => 'Credit customer is required for credit sales',
            'credit_customer_id.exists' => 'Selected credit customer does not exist',
        ];
    }
}
