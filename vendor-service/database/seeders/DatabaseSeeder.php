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
        // Default Admin
        User::firstOrCreate(
            ['email' => 'admin@vendor.com'],
            [
                'name' => 'System Admin',
                'password' => \Illuminate\Support\Facades\Hash::make('admin123'),
                'role' => User::ROLE_ADMIN,
                'status' => 'active',
            ]
        );

        // Default Vendor
        User::firstOrCreate(
            ['email' => 'mitra@vendor.com'],
            [
                'name' => 'PT. Logistik Maju Jaya',
                'password' => \Illuminate\Support\Facades\Hash::make('vendor123'),
                'role' => User::ROLE_VENDOR,
                'status' => 'active',
            ]
        );
    }
}
