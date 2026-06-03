<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;

Route::get('/', function () {
    return view('splash');
});

// Auth Routes (Public)
Route::middleware('guest')->group(function () {
    Route::get('/choice', [AuthController::class, 'showChoiceScreen'])->name('choice');

    Route::get('/customer/login', [AuthController::class, 'showCustomerLogin'])->name('customer.login');
    Route::post('/customer/login', [AuthController::class, 'customerLogin'])->name('customer.login.post');
    Route::get('/customer/register', [AuthController::class, 'showCustomerRegister'])->name('customer.register');
    Route::post('/customer/register', [AuthController::class, 'customerRegister'])->name('customer.register.post');

    Route::get('/driver/login', [AuthController::class, 'showDriverLogin'])->name('driver.login');
    Route::post('/driver/login', [AuthController::class, 'driverLogin'])->name('driver.login.post');
    
    // To serve as the default login route for Auth::routes/redirects
    Route::get('/login', [AuthController::class, 'showChoiceScreen'])->name('login');
});


// Protected Routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Customer Routes
    Route::middleware('role:CUSTOMER')->group(function () {
        Route::get('/customer/dashboard', function () {
            return redirect()->route('track');
        })->name('customer.dashboard');

        Route::get('/customer/order/create', [App\Http\Controllers\OrderController::class, 'create'])->name('customer.order.create');
        Route::post('/customer/order/store', [App\Http\Controllers\OrderController::class, 'store'])->name('customer.order.store');
        Route::get('/customer/order/{order_id}/payment', [App\Http\Controllers\OrderController::class, 'showPayment'])->name('customer.payment');
        Route::post('/customer/order/{order_id}/payment', [App\Http\Controllers\OrderController::class, 'processPayment'])->name('customer.payment.post');
        Route::post('/customer/profile/update', [App\Http\Controllers\ProfileController::class, 'update'])->name('customer.profile.update');
        Route::post('/customer/recent-searches/{id}/delete', [App\Http\Controllers\TrackingController::class, 'deleteRecentSearch'])->name('customer.recent.delete');
        Route::post('/customer/recent-searches/clear', [App\Http\Controllers\TrackingController::class, 'clearRecentSearches'])->name('customer.recent.clear');
        Route::post('/customer/order/{order_id}/cancel', [App\Http\Controllers\OrderController::class, 'cancel'])->name('customer.order.cancel');
        Route::post('/customer/order/{order_id}/delete', [App\Http\Controllers\OrderController::class, 'destroy'])->name('customer.order.destroy');
    });

    // Driver Routes
    Route::middleware('role:DRIVER')->group(function () {
        Route::get('/driver/dashboard', [App\Http\Controllers\DriverController::class, 'index'])->name('driver.dashboard');
        Route::post('/driver/task/{order_id}/claim', [App\Http\Controllers\DriverController::class, 'claimTask'])->name('driver.task.claim');
        Route::post('/driver/task/{order_id}/update-status', [App\Http\Controllers\DriverController::class, 'updateStatus'])->name('driver.task.updateStatus');
        Route::post('/driver/task/{order_id}/update-location', [App\Http\Controllers\DriverController::class, 'updateLocation'])->name('driver.task.updateLocation');
        Route::post('/driver/profile/update', [App\Http\Controllers\ProfileController::class, 'update'])->name('driver.profile.update');
    });

    // Shared APIs (Customer & Driver)
    Route::get('/api/v1/notifications', [App\Http\Controllers\NotificationController::class, 'index']);
    Route::post('/api/v1/notifications/{id}/read', [App\Http\Controllers\NotificationController::class, 'markAsRead']);
});

Route::post('/api/v1/orders', function (Illuminate\Http\Request $request) {
    $orderId = 'ORD-' . str_pad(mt_rand(1, 999), 3, '0', STR_PAD_LEFT);
    $status = 'Dikemas';
    
    // Insert into trackings database
    \Illuminate\Support\Facades\DB::table('trackings')->insert([
        'order_id' => $orderId,
        'driver_id' => null,
        'status' => $status,
        'terakhir_diupdate' => now(),
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    if (auth()->check()) {
        \Illuminate\Support\Facades\DB::table('recent_searches')->insert([
            'user_id' => auth()->id(),
            'order_id' => $orderId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    return response()->json([
        'status' => 'Success',
        'order_id' => $orderId,
        'message' => 'Pesanan berhasil dibuat!'
    ]);
});

Route::get('/track-order', [App\Http\Controllers\TrackingController::class, 'index'])->name('track');
Route::get('/track/status/{order_id}', [App\Http\Controllers\TrackingController::class, 'checkStatus'])->name('track.status');