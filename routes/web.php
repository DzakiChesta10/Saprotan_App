<?php

use App\Http\Controllers\BarangController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', [BarangController::class, 'dashboard'])->name('barang.dashboard');
Route::get('/dashboard', [BarangController::class, 'dashboard'])->name('barang.dashboard');
Route::get('/barang/export-csv', [App\Http\Controllers\BarangController::class, 'exportCsv'])->name('barang.export');
Route::post('/barang/import', [App\Http\Controllers\BarangController::class, 'import'])->name('barang.import');
Route::delete('/barang/delete-by-date', [BarangController::class, 'destroyByDate'])->name('barang.destroyByDate');
Route::resource('barang', BarangController::class);


// Halaman Login & Register (Guest)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// Halaman Dashboard (Terproteksi Auth)
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [BarangController::class, 'dashboard'])->name('barang.dashboard');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});