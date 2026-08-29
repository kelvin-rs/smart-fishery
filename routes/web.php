<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PrediksiController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/naive-bayes', function () {
    return view('naive-bayes');
});

Route::post('/hitung-prediksi', [PrediksiController::class, 'hitungPrediksi']);
