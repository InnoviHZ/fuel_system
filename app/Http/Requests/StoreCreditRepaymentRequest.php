<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCreditRepaymentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->hasAnyRole(['supervisor', 'manager', 'admin']);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'credit_customer_id' => 'required|integer|exists:credit_customers,id',
            'amount' => 'required|numeric|min:0.01',
            'payment_method' => 'required|in:cash,pos,bank_transfer',
            'repayment_date' => 'required|date|date_format:Y-m-d',
            'notes' => 'nullable|string|max:500',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'credit_customer_id.required' => 'Customer is required',
            'credit_customer_id.exists' => 'Selected customer does not exist',
            'amount.required' => 'Amount is required',
            'amount.numeric' => 'Amount must be a number',
            'amount.min' => 'Amount must be greater than zero',
            'payment_method.required' => 'Payment method is required',
            'payment_method.in' => 'Payment method must be cash, POS, or bank transfer',
            'repayment_date.required' => 'Repayment date is required',
            'repayment_date.date' => 'Repayment date must be a valid date',
            'repayment_date.date_format' => 'Repayment date must be in YYYY-MM-DD format',
            'notes.max' => 'Notes cannot exceed 500 characters',
        ];
    }
}
