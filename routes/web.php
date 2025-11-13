<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\PengawasController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PertanyaanController;
use App\Http\Controllers\InspeksiController;
use App\Http\Controllers\SOPController;

// Public routes
Route::get('/', function () {
    return view('absensi.index');
})->name('home');

// SOP Route
Route::get('/sop', [SOPController::class, 'index'])->name('sop.index');

// Absensi routes
Route::prefix('absensi')->name('absensi.')->group(function () {
    Route::get('/form', function () {
        return view('absensi.index');
    })->name('form');

    Route::post('/store', [AbsensiController::class, 'store'])->name('store');
    Route::get('/', [AbsensiController::class, 'index'])->name('index');
});

// Auth routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Pengawas routes
Route::prefix('pengawas')->name('pengawas.')->middleware('auth')->group(function () {
    // Dashboard
    Route::get('/dashboard', [PengawasController::class, 'dashboard'])->name('dashboard');

    // Inspeksi
    Route::get('/inspeksi', [InspeksiController::class, 'create'])->name('inspeksi');
    Route::post('/inspeksi', [InspeksiController::class, 'store'])->name('storeInspeksi');

    // Laporan
    Route::get('/laporan', [InspeksiController::class, 'laporan'])->name('laporan');
    Route::get('/laporan/{id}', [InspeksiController::class, 'show'])->name('laporan.detail');

    // JSON API untuk detail inspeksi
    Route::get('/inspeksi-detail/{id}', [InspeksiController::class, 'showDetail'])->name('inspeksi.detail');

    // Export routes
    Route::get('/inspeksi/{id}/export', [InspeksiController::class, 'export'])->name('inspeksi.export');
    Route::get('/inspeksi/export-all', [InspeksiController::class, 'exportAll'])->name('inspeksi.export.all');
    Route::post('/inspeksi/export-range', [InspeksiController::class, 'exportRange'])->name('inspeksi.export.range');
    Route::get('/inspeksi/export-preset/{preset}', [InspeksiController::class, 'exportPreset'])->name('inspeksi.export.preset');

    // Settings & Bantuan
    Route::get('/settings', [PengawasController::class, 'settings'])->name('settings');
    Route::post('/settings/update-password', [PengawasController::class, 'updatePassword'])->name('settings.update-password');
    Route::get('/bantuan', [PengawasController::class, 'bantuan'])->name('bantuan');

    // Edit, Update, Delete Inspeksi
    Route::get('/inspeksi/{id}/edit', [InspeksiController::class, 'edit'])->name('inspeksi.edit');
    Route::put('/inspeksi/{id}', [InspeksiController::class, 'update'])->name('inspeksi.update');
    Route::delete('/inspeksi/{id}', [InspeksiController::class, 'destroy'])->name('inspeksi.destroy');
});

// Admin routes
Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
    // Dashboard & Main Features
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/users', [AdminController::class, 'users'])->name('users');
    Route::get('/laporan', [AdminController::class, 'laporan'])->name('laporan');
    Route::get('/export', [AdminController::class, 'export'])->name('export');
    Route::get('/export-all/{type}', [AdminController::class, 'exportAll'])->name('export.all');

    // User management
    Route::get('/users/create', [AdminController::class, 'createUser'])->name('users.create');
    Route::post('/users/store', [AdminController::class, 'storeUser'])->name('users.store');
    Route::get('/users/{id}', [AdminController::class, 'showUser'])->name('users.show');
    Route::get('/users/{id}/edit', [AdminController::class, 'editUser'])->name('users.edit');
    Route::put('/users/{id}', [AdminController::class, 'updateUser'])->name('users.update');
    Route::delete('/users/{id}', [AdminController::class, 'destroyUser'])->name('users.destroy');

    // System Info & Bantuan
    Route::get('/system-info', [AdminController::class, 'systemInfo'])->name('system.info');
    Route::get('/bantuan', [AdminController::class, 'bantuan'])->name('bantuan');
});

// Pertanyaan routes (API)
Route::get('/pertanyaan/{kategoriId}', [PertanyaanController::class, 'getByKategori'])
    ->name('pertanyaan.getByKategori');

// Routes untuk manajemen pertanyaan
Route::prefix('pertanyaan')->group(function () {
    Route::get('/{kategori}/edit', [PertanyaanController::class, 'edit'])->name('pertanyaan.edit');
    Route::post('/{kategori}', [PertanyaanController::class, 'store'])->name('pertanyaan.store');
    Route::put('/{pertanyaan}', [PertanyaanController::class, 'update'])->name('pertanyaan.update');
    Route::delete('/{pertanyaan}', [PertanyaanController::class, 'destroy'])->name('pertanyaan.destroy');
    Route::post('/{kategori}/reorder', [PertanyaanController::class, 'reorder'])->name('pertanyaan.reorder');
});

// Fallback untuk handle 404
Route::fallback(function () {
    if (request()->expectsJson() || request()->is('api/*')) {
        return response()->json(['error' => 'Endpoint tidak ditemukan'], 404);
    }
    return response()->view('errors.404', [], 404);
});
