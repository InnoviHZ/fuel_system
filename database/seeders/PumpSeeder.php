<?php

namespace Database\Seeders;

use App\Models\Pump;
use App\Models\Tank;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PumpSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get tanks
        $tank1 = Tank::where('name', 'Tank 1')->first();
        $tank2 = Tank::where('name', 'Tank 2')->first();
        $tank3 = Tank::where('name', 'Tank 3')->first();
        $tank4 = Tank::where('name', 'Tank 4')->first();

        // Pump 1 - Connected to Tank 1 (PMS)
        Pump::updateOrCreate(
            ['name' => 'Pump 1'],
            [
                'tank_id' => $tank1->id,
                'is_active' => true,
            ]
        );

        // Pump 2 - Connected to Tank 2 (PMS)
        Pump::updateOrCreate(
            ['name' => 'Pump 2'],
            [
                'tank_id' => $tank2->id,
                'is_active' => true,
            ]
        );

        // Pump 3 - Connected to Tank 3 (AGO)
        Pump::updateOrCreate(
            ['name' => 'Pump 3'],
            [
                'tank_id' => $tank3->id,
                'is_active' => true,
            ]
        );

        // Pump 4 - Connected to Tank 4 (DPK)
        Pump::updateOrCreate(
            ['name' => 'Pump 4'],
            [
                'tank_id' => $tank4->id,
                'is_active' => true,
            ]
        );
    }
}
