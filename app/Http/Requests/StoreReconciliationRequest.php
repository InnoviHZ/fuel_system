<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreReconciliationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->hasRole('supervisor');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'shift_id' => 'required|integer|exists:shifts,id',
            'actual_cash' => 'required|numeric|min:0',
            'actual_pos' => 'required|numeric|min:0',
            'actual_bank_transfer' => 'required|numeric|min:0',
            'supervisor_note' => 'nullable|string|max:1000',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'shift_id.required' => 'Shift is required',
            'shift_id.exists' => 'Selected shift does not exist',
            'actual_cash.required' => 'Actual cash amount is required',
            'actual_cash.numeric' => 'Actual cash must be a number',
            'actual_cash.min' => 'Actual cash cannot be negative',
            'actual_pos.required' => 'Actual POS amount is required',
            'actual_pos.numeric' => 'Actual POS must be a number',
            'actual_pos.min' => 'Actual POS cannot be negative',
            'actual_bank_transfer.required' => 'Actual bank transfer amount is required',
            'actual_bank_transfer.numeric' => 'Actual bank transfer must be a number',
            'actual_bank_transfer.min' => 'Actual bank transfer cannot be negative',
            'supervisor_note.max' => 'Supervisor note cannot exceed 1000 characters',
        ];
    }
}
