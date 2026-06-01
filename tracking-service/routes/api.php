<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TrackingController;

Route::get('/v1/track/{order_id}', [TrackingController::class, 'checkStatus']);
