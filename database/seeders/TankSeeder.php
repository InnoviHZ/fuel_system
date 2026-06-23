<?php

namespace Database\Seeders;

use App\Models\Tank;
use App\Models\FuelProduct;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TankSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get fuel products
        $pms = FuelProduct::where('code', 'PMS')->first();
        $ago = FuelProduct::where('code', 'AGO')->first();
        $dpk = FuelProduct::where('code', 'DPK')->first();

        // Tank 1 - PMS
        Tank::updateOrCreate(
            ['name' => 'Tank 1'],
            [
                'fuel_product_id' => $pms->id,
                'capacity_litres' => 45000.00,
                'current_stock_litres' => 35000.00,
                'low_stock_threshold' => 9000.00,
                'critical_stock_threshold' => 4500.00,
                'is_active' => true,
            ]
        );

        // Tank 2 - PMS
        Tank::updateOrCreate(
            ['name' => 'Tank 2'],
            [
                'fuel_product_id' => $pms->id,
                'capacity_litres' => 45000.00,
                'current_stock_litres' => 32000.00,
                'low_stock_threshold' => 9000.00,
                'critical_stock_threshold' => 4500.00,
                'is_active' => true,
            ]
        );

        // Tank 3 - AGO
        Tank::updateOrCreate(
            ['name' => 'Tank 3'],
            [
                'fuel_product_id' => $ago->id,
                'capacity_litres' => 33000.00,
                'current_stock_litres' => 28000.00,
                'low_stock_threshold' => 6600.00,
                'critical_stock_threshold' => 3300.00,
                'is_active' => true,
            ]
        );

        // Tank 4 - DPK
        Tank::updateOrCreate(
            ['name' => 'Tank 4'],
            [
                'fuel_product_id' => $dpk->id,
                'capacity_litres' => 33000.00,
                'current_stock_litres' => 25000.00,
                'low_stock_threshold' => 6600.00,
                'critical_stock_threshold' => 3300.00,
                'is_active' => true,
            ]
        );
    }
}
