<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CloseShiftRequest extends FormRequest
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
            'pump_readings' => 'required|array|min:1',
            'pump_readings.*.pump_id' => 'required|integer|exists:pumps,id',
            'pump_readings.*.closing_meter' => 'required|numeric|min:0|max:999999.99',
            'notes' => 'nullable|string|max:1000',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'pump_readings.required' => 'Pump readings are required',
            'pump_readings.min' => 'At least one pump reading is required',
            'pump_readings.array' => 'Pump readings must be an array',
            'pump_readings.*.pump_id.required' => 'Pump ID is required',
            'pump_readings.*.pump_id.integer' => 'Pump ID must be an integer',
            'pump_readings.*.pump_id.exists' => 'Selected pump does not exist',
            'pump_readings.*.closing_meter.required' => 'Closing meter reading is required',
            'pump_readings.*.closing_meter.numeric' => 'Closing meter must be a number',
            'pump_readings.*.closing_meter.min' => 'Closing meter cannot be negative',
            'pump_readings.*.closing_meter.max' => 'Closing meter value is too high',
            'notes.max' => 'Notes cannot exceed 1000 characters',
        ];
    }
}