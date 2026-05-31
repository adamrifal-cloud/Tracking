<?php

use Illuminate\Support\Facades\Route;

Route::get('/track-order', function () {
    return view('track');
});