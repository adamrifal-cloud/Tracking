<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

// Create customer user if not exists
$customerId = DB::table('users')->where('role', 'CUSTOMER')->value('id');
if (!$customerId) {
    $customerId = DB::table('users')->insertGetId([
        'name' => 'Budi Customer',
        'email' => 'customer@example.com',
        'phone' => '08123456789',
        'password' => Hash::make('password'),
        'role' => 'CUSTOMER',
        'status' => 'ACTIVE',
        'created_at' => now(),
        'updated_at' => now(),
    ]);
}

// Create order if not exists
$orderExists = DB::table('orders')->where('order_id', 'ORD-E8E7EB60')->exists();
if (!$orderExists) {
    DB::table('orders')->insert([
        'order_id' => 'ORD-E8E7EB60',
        'user_id' => $customerId,
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
        'created_at' => now(),
        'updated_at' => now(),
    ]);
}

// Create tracking if not exists
$trackingExists = DB::table('trackings')->where('order_id', 'ORD-E8E7EB60')->exists();
if (!$trackingExists) {
    DB::table('trackings')->insert([
        'order_id' => 'ORD-E8E7EB60',
        'driver_id' => null,
        'status' => 'Dikemas',
        'latitude' => -6.200000,
        'longitude' => 106.816666,
        'terakhir_diupdate' => now(),
        'created_at' => now(),
        'updated_at' => now(),
    ]);
}

echo "SUCCESSFULLY SEEDED TEST ORDER ORD-E8E7EB60\n";
