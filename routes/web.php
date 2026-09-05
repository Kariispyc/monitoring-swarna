<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AuthController;

// Halaman Publik
Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/live-data', [DashboardController::class, 'getLiveData'])->name('live.data');

// Endpoint AJAX Kontrol (Wajib Bisa Diakses JS Dashboard)
Route::post('/api/controls/update', [DashboardController::class, 'updateControl']);

// Halaman & Proses Login
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Fitur Khusus Rekap Data
Route::middleware(['auth'])->group(function () {
    Route::get('/export-excel', [DashboardController::class, 'exportExcel'])->name('export.excel');
    Route::get('/export-csv', [DashboardController::class, 'exportCSV'])->name('export.csv');
});