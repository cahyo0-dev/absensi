@extends('layouts.app')

@section('title', 'Dashboard Pengawas')

@section('content')
    <div class="space-y-6">
        <!-- Welcome Header -->
        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Dashboard Pengawas</h1>
                    <p class="text-gray-600 mt-1">Selamat datang, <span class="font-semibold">{{ Auth::user()->name }}</span>
                    </p>
                </div>
                <div class="mt-3 sm:mt-0 text-sm text-gray-500 bg-gray-50 px-3 py-2 rounded-lg">
                    <i class="fas fa-calendar-alt mr-2"></i>
                    {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
                </div>
            </div>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Status Hari Ini -->
            <div class="bg-white shadow rounded-lg p-6 hover:shadow-md transition-shadow duration-200">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-check-circle text-green-600 text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-sm font-medium text-gray-900">Status Hari Ini</h3>
                        <p class="mt-1 text-lg font-semibold text-green-600">Sudah Inspeksi</p>
                        <p class="text-xs text-gray-500">Oleh: {{ Auth::user()->name }}</p>
                    </div>
                </div>
            </div>

            <!-- Total Inspeksi -->
            <div class="bg-white shadow rounded-lg p-6 hover:shadow-md transition-shadow duration-200">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-clipboard-list text-blue-600 text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-sm font-medium text-gray-900">Total Inspeksi</h3>
                        <p class="mt-1 text-2xl font-bold text-gray-900">3</p>
                        <p class="text-xs text-gray-500">Semua waktu</p>
                    </div>
                </div>
            </div>

            <!-- Bulan Ini -->
            <div class="bg-white shadow rounded-lg p-6 hover:shadow-md transition-shadow duration-200">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-calendar-alt text-purple-600 text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-sm font-medium text-gray-900">Bulan Ini</h3>
                        <p class="mt-1 text-2xl font-bold text-gray-900">3</p>
                        <p class="text-xs text-gray-500">{{ \Carbon\Carbon::now()->translatedFormat('F Y') }}</p>
                    </div>
                </div>
            </div>

            <!-- Total Pengawas -->
            <div class="bg-white shadow rounded-lg p-6 hover:shadow-md transition-shadow duration-200">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-users text-orange-600 text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-sm font-medium text-gray-900">Total Pengawas</h3>
                        <p class="mt-1 text-2xl font-bold text-gray-900">4</p>
                        <p class="text-xs text-gray-500">Aktif</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions & Laporan -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Quick Actions -->
            <div class="bg-white shadow rounded-lg p-6 hover:shadow-md transition-shadow duration-200">
                <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-bolt text-yellow-500 mr-2"></i>
                    Quick Actions
                </h2>
                <div class="space-y-4">
                    <div class="flex items-start p-3 hover:bg-gray-50 rounded-lg transition duration-200">
                        <div class="flex-shrink-0 mt-1">
                            <div class="w-6 h-6 border-2 border-gray-300 rounded flex items-center justify-center">
                                <!-- Empty checkbox -->
                            </div>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-900">Lakukan Inspeksi</p>
                            <p class="text-xs text-gray-500 mt-1">Mulai inspeksi baru</p>
                        </div>
                    </div>
                    <div class="flex items-start p-3 bg-green-50 rounded-lg transition duration-200">
                        <div class="flex-shrink-0 mt-1">
                            <div class="w-6 h-6 bg-green-500 rounded flex items-center justify-center">
                                <i class="fas fa-check text-white text-xs"></i>
                            </div>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-900">Inspeksi harian untuk hari ini</p>
                            <p class="text-xs text-gray-500 mt-1">(Hanya 1 inspeksi per hari untuk semua pengawas)</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Laporan -->
            <div class="bg-white shadow rounded-lg p-6 hover:shadow-md transition-shadow duration-200">
                <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-chart-bar text-blue-500 mr-2"></i>
                    Laporan
                </h2>
                <div class="space-y-4">
                    <div class="flex items-start p-3 hover:bg-gray-50 rounded-lg transition duration-200">
                        <div class="flex-shrink-0 mt-1">
                            <div class="w-6 h-6 border-2 border-gray-300 rounded flex items-center justify-center">
                                <!-- Empty checkbox -->
                            </div>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-900">Lihat Semua Laporan</p>
                            <p class="text-xs text-gray-500 mt-1">Akses laporan lengkap</p>
                        </div>
                    </div>
                    <div class="flex items-start p-3 bg-green-50 rounded-lg transition duration-200">
                        <div class="flex-shrink-0 mt-1">
                            <div class="w-6 h-6 bg-green-500 rounded flex items-center justify-center">
                                <i class="fas fa-check text-white text-xs"></i>
                            </div>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-900">Akses semua data inspeksi yang telah dilakukan</p>
                            <p class="text-xs text-gray-500 mt-1">Data historis tersedia</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
