<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InternalApiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/v1/internal/drivers', [InternalApiController::class, 'drivers']);
Route::get('/v1/internal/unallocated-orders', [InternalApiController::class, 'unallocatedOrders']);
Route::get('/v1/internal/allocations/count', [InternalApiController::class, 'allocationsCount']);
Route::get('/v1/internal/queue/status', [InternalApiController::class, 'queueStatus']);
Route::get('/v1/internal/admin/shipments', [InternalApiController::class, 'adminShipments']);

// CRUD endpoints for Drivers (Internal API accessed by vendor-service)
Route::get('/v1/internal/drivers/all', [InternalApiController::class, 'allDrivers']);
Route::post('/v1/internal/drivers', [InternalApiController::class, 'storeDriver']);
Route::put('/v1/internal/drivers/{id}', [InternalApiController::class, 'updateDriver']);
Route::delete('/v1/internal/drivers/{id}', [InternalApiController::class, 'destroyDriver']);
Route::delete('/v1/internal/orders/{order_id}', [InternalApiController::class, 'destroyOrder']);

// Order API (moved from web.php to disable CSRF)
Route::post('/v1/orders', [App\Http\Controllers\OrderController::class, 'storeApi']);

