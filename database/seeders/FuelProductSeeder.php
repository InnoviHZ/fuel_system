<?php

namespace Database\Seeders;

use App\Models\FuelProduct;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FuelProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            [
                'name' => 'Premium Motor Spirit',
                'code' => 'PMS',
                'current_price' => 950.00,
                'is_active' => true,
            ],
            [
                'name' => 'Automotive Gas Oil',
                'code' => 'AGO',
                'current_price' => 1200.00,
                'is_active' => true,
            ],
            [
                'name' => 'Dual Purpose Kerosene',
                'code' => 'DPK',
                'current_price' => 850.00,
                'is_active' => true,
            ],
        ];

        foreach ($products as $product) {
            FuelProduct::updateOrCreate(
                ['code' => $product['code']],
                $product
            );
        }
    }
}
