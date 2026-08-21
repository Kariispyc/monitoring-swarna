<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\IotController;

// Rute untuk ESP32 dan Dashboard Web
Route::post('/telemetry', [IotController::class, 'storeData']);
Route::get('/controls', [IotController::class, 'getControlStatus']);
Route::post('/controls/update', [IotController::class, 'updateControl']);