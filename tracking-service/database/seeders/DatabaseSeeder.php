<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Default Customer
        User::firstOrCreate(
            ['email' => 'customer@example.com'],
            [
                'name' => 'Budi Customer',
                'phone' => '08123456789',
                'password' => \Illuminate\Support\Facades\Hash::make('password'),
                'role' => User::ROLE_CUSTOMER,
                'status' => User::STATUS_ACTIVE,
            ]
        );

        // Default Driver
        User::firstOrCreate(
            ['email' => 'driver@example.com'],
            [
                'name' => 'Asep Kurir',
                'phone' => '08987654321',
                'password' => \Illuminate\Support\Facades\Hash::make('password'),
                'role' => User::ROLE_DRIVER,
                'status' => User::STATUS_ACTIVE,
                'vehicle_number' => 'B 1234 CD',
                'license_number' => 'SIM-987654321',
                'verified_at' => now(),
            ]
        );
    }
}
