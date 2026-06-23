<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            [
                'key' => 'station_name',
                'value' => 'Fuel Station',
                'description' => 'Name of the fuel station',
            ],
            [
                'key' => 'station_address',
                'value' => '123 Main Street, City, State',
                'description' => 'Physical address of the fuel station',
            ],
            [
                'key' => 'station_phone',
                'value' => '+1-555-0123',
                'description' => 'Phone number of the fuel station',
            ],
            [
                'key' => 'morning_shift_time',
                'value' => '06:00',
                'description' => 'Start time for morning shift (HH:MM format)',
            ],
            [
                'key' => 'evening_shift_time',
                'value' => '14:00',
                'description' => 'Start time for evening shift (HH:MM format)',
            ],
            [
                'key' => 'cash_tolerance_threshold',
                'value' => '10.00',
                'description' => 'Maximum allowed cash variance during reconciliation ($)',
            ],
        ];

        foreach ($settings as $setting) {
            Setting::firstOrCreate(['key' => $setting['key']], $setting);
        }
    }
}
