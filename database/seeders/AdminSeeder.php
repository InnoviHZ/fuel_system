<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@fuelstation.com'],
            [
                'name' => 'Administrator',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'is_active' => true,
            ]
        );

        // Create additional test users for other roles
        User::updateOrCreate(
            ['email' => 'manager@fuelstation.com'],
            [
                'name' => 'Manager User',
                'password' => Hash::make('password'),
                'role' => 'manager',
                'is_active' => true,
            ]
        );

        User::updateOrCreate(
            ['email' => 'supervisor@fuelstation.com'],
            [
                'name' => 'Supervisor User',
                'password' => Hash::make('password'),
                'role' => 'supervisor',
                'is_active' => true,
            ]
        );

        User::updateOrCreate(
            ['email' => 'attendant@fuelstation.com'],
            [
                'name' => 'Attendant User',
                'password' => Hash::make('password'),
                'role' => 'attendant',
                'is_active' => true,
            ]
        );
    }
}
