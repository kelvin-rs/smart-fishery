<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\SensorController;
use App\Http\Controllers\Api\TimbanganController;

/*
|--------------------------------------------------------------------------
| API Routes for Smart Fishery IoT & Real-time Integration
|--------------------------------------------------------------------------
*/

// Endpoint IoT Sensor Kualitas Air
Route::post('/sensor/data', [SensorController::class, 'store'])->name('api.sensor.store');
Route::get('/sensor/realtime/{idTambak}', [SensorController::class, 'getRealtimeChart'])->name('api.sensor.realtime');

// Endpoint IoT Timbangan Digital
Route::post('/timbangan/data', [TimbanganController::class, 'store'])->name('api.timbangan.store');
