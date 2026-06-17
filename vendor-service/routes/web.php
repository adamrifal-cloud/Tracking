<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\VendorAuthController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\VendorDashboardController;
use App\Jobs\SendDriverAllocation;

// Redirect root to dashboard based on role or to a default login
Route::get('/', [VendorDashboardController::class, 'rootRedirect']);

// Admin Auth Routes
Route::middleware('guest')->group(function () {
    Route::get('/admin/login', [AdminAuthController::class, 'showLogin'])->name('admin.login');
    Route::post('/admin/login', [AdminAuthController::class, 'login'])->name('admin.login.post');
    
    Route::get('/vendor/login', [VendorAuthController::class, 'showLogin'])->name('vendor.login');
    Route::post('/vendor/login', [VendorAuthController::class, 'login'])->name('vendor.login.post');
});

// Protected Admin Routes
Route::middleware(['auth', 'is_admin'])->group(function () {
    Route::post('/admin/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');
    Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/admin/vendors', [AdminDashboardController::class, 'vendorsPage'])->name('admin.vendors.index');
    Route::delete('/admin/orders/{order_id}', [AdminDashboardController::class, 'destroyOrder'])->name('admin.orders.destroy');
    Route::get('/admin/unread-orders', [AdminDashboardController::class, 'getUnallocatedOrders'])->name('admin.unread-orders');

    Route::post('/admin/allocate', [AdminDashboardController::class, 'allocateDriver'])->name('allocate.post');

    Route::post('/admin/vendors/{vendor}/verify', [AdminDashboardController::class, 'verifyVendor'])->name('admin.vendors.verify');
});

// Protected Vendor Routes
Route::middleware(['auth', 'is_vendor'])->group(function () {
    Route::post('/vendor/logout', [VendorAuthController::class, 'logout'])->name('vendor.logout');
    Route::get('/vendor/dashboard', [VendorDashboardController::class, 'index'])->name('vendor.dashboard');

    // Vendor Profile Routes
    Route::get('/vendor/profile', [\App\Http\Controllers\VendorProfileController::class, 'index'])->name('vendor.profile');
    Route::post('/vendor/profile', [\App\Http\Controllers\VendorProfileController::class, 'update'])->name('vendor.profile.update');



    // Driver Routes
    Route::get('/vendor/drivers', [VendorDashboardController::class, 'driversPage'])->name('vendor.drivers.index');
    Route::post('/vendor/drivers', [VendorDashboardController::class, 'storeDriver'])->name('vendor.drivers.store');
    Route::put('/vendor/drivers/{id}', [VendorDashboardController::class, 'updateDriver'])->name('vendor.drivers.update');
    Route::delete('/vendor/drivers/{id}', [VendorDashboardController::class, 'destroyDriver'])->name('vendor.drivers.destroy');

    // Notification Routes
    Route::get('/vendor/notifications', [VendorDashboardController::class, 'notificationsPage'])->name('vendor.notifications.index');
    Route::post('/vendor/notifications/{notification}/read', [VendorDashboardController::class, 'readNotification'])->name('vendor.notifications.read');
});