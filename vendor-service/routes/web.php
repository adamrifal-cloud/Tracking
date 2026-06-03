<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Jobs\SendDriverAllocation;

// Redirect root to dashboard (which redirects to login if unauthenticated)
Route::get('/', function () {
    return redirect()->route('dashboard');
});

// Auth Routes (Guest)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

// Protected Routes (Admin / Vendor)
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/dashboard', function () {
        return view('dashboard'); // We will create this view
    })->name('dashboard');

    // API to send the job
    Route::post('/allocate', function (\Illuminate\Http\Request $request) {
        $validated = $request->validate([
            'order_id' => 'required|string',
            'driver_id' => 'required|integer',
            'driver_name' => 'required|string',
            'vendor_name' => 'required|string',
        ]);

        dispatch(new SendDriverAllocation($validated))->onQueue('driver-allocations');

        return response()->json(['message' => 'Tugas ' . $validated['order_id'] . ' berhasil dikirim ke antrean kurir.']);
    })->name('allocate.post');
});