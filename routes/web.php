<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\Petambak\DashboardController as PetambakDashboardController;
use App\Http\Controllers\Petambak\TambakController as PetambakTambakController;
use App\Http\Controllers\Petambak\KualitasAirController as PetambakKualitasAirController;
use App\Http\Controllers\Petambak\PrediksiPanenController as PetambakPrediksiPanenController;
use App\Http\Controllers\Petambak\HasilPanenController as PetambakHasilPanenController;
use App\Http\Controllers\Petambak\DatasetController as PetambakDatasetController;
use App\Http\Controllers\Kud\DashboardController as KudDashboardController;
use App\Http\Controllers\Kud\HargaIkanController as KudHargaIkanController;
use App\Http\Controllers\Kud\HasilPanenController as KudHasilPanenController;

// Public Landing Page
Route::get('/', [LandingController::class, 'index'])->name('home');

// Authentication Routes (Guest Only)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
});

// Logout Route (Auth Only)
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Protected Routes: Petambak
Route::middleware(['auth', 'role:petambak'])->prefix('petambak')->name('petambak.')->group(function () {
    Route::get('/dashboard', [PetambakDashboardController::class, 'index'])->name('dashboard');

    // Data Tambak
    Route::get('/tambak', [PetambakTambakController::class, 'index'])->name('tambak.index');
    Route::post('/tambak', [PetambakTambakController::class, 'store'])->name('tambak.store');

    // Cek Kualitas Air (Gaussian Naive Bayes)
    Route::get('/kualitas-air', [PetambakKualitasAirController::class, 'index'])->name('kualitas-air.index');
    Route::post('/kualitas-air/proses', [PetambakKualitasAirController::class, 'proses'])->name('kualitas-air.proses');

    // Prediksi Hasil Panen (Regresi Linier)
    Route::get('/prediksi-panen', [PetambakPrediksiPanenController::class, 'index'])->name('prediksi.index');
    Route::post('/prediksi-panen/proses', [PetambakPrediksiPanenController::class, 'proses'])->name('prediksi.proses');

    // Informasi Hasil Panen Petambak
    Route::get('/hasil-panen', [PetambakHasilPanenController::class, 'index'])->name('panen.index');
    Route::post('/hasil-panen', [PetambakHasilPanenController::class, 'store'])->name('panen.store');

    // Tambah Sumber Data (Dataset Import)
    Route::get('/dataset', [PetambakDatasetController::class, 'index'])->name('dataset.index');
    Route::post('/dataset/upload', [PetambakDatasetController::class, 'upload'])->name('dataset.upload');
});

// Protected Routes: KUD
Route::middleware(['auth', 'role:kud'])->prefix('kud')->name('kud.')->group(function () {
    Route::get('/dashboard', [KudDashboardController::class, 'index'])->name('dashboard');

    // Update Harga Ikan
    Route::get('/harga-ikan', [KudHargaIkanController::class, 'index'])->name('harga.index');
    Route::post('/harga-ikan/update', [KudHargaIkanController::class, 'update'])->name('harga.update');

    // Rekapitulasi Hasil Panen Petambak
    Route::get('/rekap-panen', [KudHasilPanenController::class, 'index'])->name('panen.index');
});
