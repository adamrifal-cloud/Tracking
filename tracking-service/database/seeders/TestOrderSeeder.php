<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Order;
use App\Models\Tracking;

class TestOrderSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::where('role', 'CUSTOMER')->first();
        if (!$user) {
            $user = User::create([
                'name' => 'Budi Customer',
                'email' => 'customer@example.com',
                'phone' => '08123456789',
                'password' => \Illuminate\Support\Facades\Hash::make('password'),
                'role' => User::ROLE_CUSTOMER,
                'status' => User::STATUS_ACTIVE,
            ]);
        }

        // Create unallocated order if it doesn't exist
        $order = Order::where('order_id', 'ORD-E8E7EB60')->first();
        if (!$order) {
            $order = Order::create([
                'order_id' => 'ORD-E8E7EB60',
                'user_id' => $user->id,
                'sender_name' => 'Budi Customer',
                'sender_phone' => '08123456789',
                'sender_address' => 'Jl. Merdeka No. 12',
                'receiver_name' => 'Siti Receiver',
                'receiver_phone' => '08987654321',
                'receiver_address' => 'Jl. Sudirman No. 45',
                'package_description' => 'Laptop ASUS ROG',
                'package_weight' => 3.5,
                'price' => 35000,
                'payment_status' => 'PAID',
            ]);
        }

        $tracking = Tracking::where('order_id', 'ORD-E8E7EB60')->first();
        if (!$tracking) {
            Tracking::create([
                'order_id' => 'ORD-E8E7EB60',
                'driver_id' => null,
                'status' => 'Dikemas',
                'latitude' => -6.200000,
                'longitude' => 106.816666,
                'terakhir_diupdate' => now(),
            ]);
        }
    }
}
