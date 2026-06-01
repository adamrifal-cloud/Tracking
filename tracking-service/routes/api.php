<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\TrackingController;

Route::middleware('auth')->group(function () {
    Route::get('/v1/notifications', [NotificationController::class, 'index']);
    Route::post('/v1/notifications/{id}/read', [NotificationController::class, 'markAsRead']);
});

Route::get('/v1/track/{order_id}', [TrackingController::class, 'checkStatus']);
