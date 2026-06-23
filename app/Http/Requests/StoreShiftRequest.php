<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreShiftRequest extends FormRequest
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
            'shift_date' => 'required|date|date_format:Y-m-d',
            'shift_type' => ['required', Rule::in(['morning', 'evening'])],
            'notes' => 'nullable|string|max:1000',
            'pump_readings' => 'required|array|min:1',
            'pump_readings.*.pump_id' => 'required|integer|exists:pumps,id',
            'pump_readings.*.opening_meter' => 'required|numeric|min:0|max:999999.99',
            'pump_readings.*.attendant_id' => 'required|integer|exists:users,id',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'shift_date.required' => 'Shift date is required',
            'shift_date.date' => 'Shift date must be a valid date',
            'shift_date.date_format' => 'Shift date must be in YYYY-MM-DD format',
            'shift_type.required' => 'Shift type is required',
            'shift_type.in' => 'Shift type must be either morning or evening',
            'notes.max' => 'Notes cannot exceed 1000 characters',
            'pump_readings.required' => 'At least one pump reading is required',
            'pump_readings.min' => 'At least one pump reading is required',
            'pump_readings.array' => 'Pump readings must be an array',
            'pump_readings.*.pump_id.required' => 'Pump selection is required',
            'pump_readings.*.pump_id.integer' => 'Pump ID must be an integer',
            'pump_readings.*.pump_id.exists' => 'Selected pump does not exist',
            'pump_readings.*.opening_meter.required' => 'Opening meter reading is required',
            'pump_readings.*.opening_meter.numeric' => 'Opening meter must be a number',
            'pump_readings.*.opening_meter.min' => 'Opening meter cannot be negative',
            'pump_readings.*.opening_meter.max' => 'Opening meter value is too high',
            'pump_readings.*.attendant_id.required' => 'Attendant assignment is required',
            'pump_readings.*.attendant_id.integer' => 'Attendant ID must be an integer',
            'pump_readings.*.attendant_id.exists' => 'Selected attendant does not exist',
        ];
    }
}