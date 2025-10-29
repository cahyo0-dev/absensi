<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\PengawasController;
use App\Http\Controllers\AdminController;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\PengawasMiddleware;
use App\Http\Controllers\PertanyaanController;
use App\Http\Controllers\InspeksiController;

// Public routes
Route::get('/', function () {
    return view('absensi.index');
});
//absensi
Route::prefix('absensi')->name('absensi.')->group(function () {
    Route::get('/form', function () {
        return view('absensi.index');
    })->name('form');
    
    Route::post('/store', [AbsensiController::class, 'store'])->name('store');
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
Route::prefix('pengawas')->name('pengawas.')->middleware('auth')->group(function () {
    Route::get('/dashboard', function () {return view('pengawas.dashboard');})->name('dashboard');
    Route::get('/inspeksi', [InspeksiController::class, 'create'])->name('inspeksi');
    Route::post('/inspeksi', [InspeksiController::class, 'store'])->name('storeInspeksi');
    Route::get('/laporan', [InspeksiController::class, 'laporan'])->name('laporan');
    Route::get('/laporan/{id}', [InspeksiController::class, 'show'])->name('laporan.detail');
});

// Admin routes
Route::middleware(['auth', AdminMiddleware::class])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/admin/users', [AdminController::class, 'users'])->name('admin.users');
    Route::get('/admin/laporan', [AdminController::class, 'laporan'])->name('admin.laporan');
    Route::get('/admin/export', [AdminController::class, 'export'])->name('admin.export');
});

//pertanyaan routes
Route::get('/pertanyaan/{kategoriId}', [PertanyaanController::class, 'getByKategori'])->name('pertanyaan.getByKategori');

// Export routes
Route::get('/pengawas/inspeksi/{id}/export', [InspeksiController::class, 'export'])
    ->middleware('auth')
    ->name('inspeksi.export');
Route::get('/pengawas/inspeksi/export-all', [InspeksiController::class, 'exportAll'])
    ->middleware('auth')
    ->name('inspeksi.export.all');
// Tambahkan route untuk export rentang waktu
Route::post('/pengawas/inspeksi/export-range', [InspeksiController::class, 'exportRange'])
    ->middleware('auth')
    ->name('inspeksi.export.range');
// Route untuk preset waktu
Route::get('/pengawas/inspeksi/export-preset/{preset}', [InspeksiController::class, 'exportPreset'])
    ->middleware('auth')
    ->name('inspeksi.export.preset');

// Untuk edit inspeksi
Route::get('/pengawas/inspeksi/{id}/edit', [InspeksiController::class, 'create'])->name('pengawas.inspeksi.edit');

// Untuk update inspeksi  
Route::put('/pengawas/inspeksi/{id}', [InspeksiController::class, 'store'])->name('pengawas.inspeksi.update');

// Untuk hapus inspeksi
Route::delete('/pengawas/inspeksi/{id}', [InspeksiController::class, 'destroy'])->name('pengawas.inspeksi.destroy');

// Untuk detail inspeksi (JSON)
Route::get('/pengawas/laporan/{id}', [InspeksiController::class, 'show'])->name('pengawas.laporan.show');

// Untuk export single
Route::get('/pengawas/inspeksi/{id}/export', [InspeksiController::class, 'export'])->name('pengawas.inspeksi.export');