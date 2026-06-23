<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    /**
     * Display all settings
     */
    public function index()
    {
        // Get all settings from database
        $allSettings = Setting::query()->get();

        // Create key-value array for view
        $settings = $allSettings->pluck('value', 'key')->toArray();

        // Group settings by category
        $grouped = [
            'station' => [],
            'shift' => [],
            'financial' => [],
        ];

        foreach ($allSettings as $setting) {
            if (str_contains($setting->key, 'station_')) {
                $grouped['station'][$setting->key] = $setting->value;
            } elseif (str_contains($setting->key, 'shift_')) {
                $grouped['shift'][$setting->key] = $setting->value;
            } elseif (str_contains($setting->key, 'cash_')) {
                $grouped['financial'][$setting->key] = $setting->value;
            }
        }

        return view('settings.index', compact('grouped', 'settings'));
    }

    /**
     * Update settings
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'station_name' => 'required|string|max:255',
            'station_address' => 'required|string|max:500',
            'station_phone' => 'required|string|max:20',
            'cash_tolerance_threshold' => 'required|numeric|min:0',
            'morning_shift_time' => 'required|date_format:H:i',
            'evening_shift_time' => 'required|date_format:H:i',
        ]);

        $oldValues = [];
        foreach ($validated as $key => $value) {
            $oldValues[$key] = Setting::get($key);
            Setting::set($key, $value);
        }

        AuditLog::record(
            'updated',
            'System settings updated',
            null,
            $oldValues,
            $validated
        );

        return redirect()->back()->with('success', 'Settings updated successfully');
    }
}
