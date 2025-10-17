<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\PengawasController;
use App\Http\Controllers\AdminController;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\PengawasMiddleware;
use App\Http\Controllers\PertanyaanController;

// Public routes
Route::get('/', function () {
    return view('absensi.index');
});

Route::get('/absensi', [AbsensiController::class, 'index'])->name('absensi.index');
Route::post('/absensi', [AbsensiController::class, 'store'])->name('absensi.store');

// Auth routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Pengawas routes
Route::middleware(['auth', PengawasMiddleware::class])->group(function () {
    Route::get('/pengawas/dashboard', [PengawasController::class, 'dashboard'])->name('pengawas.dashboard');
    Route::get('/pengawas/inspeksi', [PengawasController::class, 'inspeksi'])->name('pengawas.inspeksi');
    Route::post('/pengawas/inspeksi', [PengawasController::class, 'storeInspeksi'])->name('pengawas.storeInspeksi');
    Route::get('/pengawas/laporan', [PengawasController::class, 'laporan'])->name('pengawas.laporan');
});

// Admin routes
Route::middleware(['auth', AdminMiddleware::class])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/admin/users', [AdminController::class, 'users'])->name('admin.users');
    Route::get('/admin/laporan', [AdminController::class, 'laporan'])->name('admin.laporan');
    Route::get('/admin/export', [AdminController::class, 'export'])->name('admin.export');
});
//pertanyaan routes
// routes/web.php
Route::get('/pertanyaan/{kategoriId}', [PertanyaanController::class, 'getByKategori'])->name('pertanyaan.getByKategori');