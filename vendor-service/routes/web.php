<?php

use Illuminate\Support\Facades\Route;
use App\Jobs\SendDriverAllocation;

Route::get('/allocate', function () {
    return view('allocate');
});

Route::get('/test-kirim', function () {
    $dataDummy = [
        'order_id' => 'ORD-' . rand(1000, 9999),
        'driver_id' => 7,
        'driver_name' => 'Anto Kurir Kilat',
        'vendor_name' => 'PT Trans Logistik'
    ];

    // Tentukan nama antrean 'driver-allocations' di sini secara aman
    dispatch(new SendDriverAllocation($dataDummy))->onQueue('driver-allocations');

    return "Data Driver sukses dikirim ke RabbitMQ!";
});