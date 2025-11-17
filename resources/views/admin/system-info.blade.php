@extends('layouts.admin')

@section('title', 'Info Sistem')

@section('content')
    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-2xl font-bold text-gray-900 flex items-center">
                    <i class="fas fa-info-circle text-blue-500 mr-3"></i>
                    Info Sistem
                </h1>
                <p class="text-gray-600 mt-2">Informasi dan statistik sistem BMKG</p>
            </div>

            <!-- Statistik Sistem -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="p-3 bg-blue-500 rounded-lg">
                            <i class="fas fa-users text-white text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Total Pengguna</p>
                            <p class="text-2xl font-semibold text-gray-900">{{ $stats['total_users'] }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="p-3 bg-green-500 rounded-lg">
                            <i class="fas fa-user-shield text-white text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Pengawas</p>
                            <p class="text-2xl font-semibold text-gray-900">{{ $stats['total_pengawas'] }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="p-3 bg-yellow-500 rounded-lg">
                            <i class="fas fa-clipboard-check text-white text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Total Inspeksi</p>
                            <p class="text-2xl font-semibold text-gray-900">{{ $stats['total_inspeksi'] }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="p-3 bg-purple-500 rounded-lg">
                            <i class="fas fa-list-alt text-white text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Kategori Aktif</p>
                            <p class="text-2xl font-semibold text-gray-900">{{ $stats['kategori_aktif'] }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Informasi Server -->
                <div class="bg-white rounded-lg shadow">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                            <i class="fas fa-server mr-2 text-gray-600"></i>
                            Informasi Server
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                <span class="text-sm font-medium text-gray-600">Versi Laravel</span>
                                <span class="text-sm text-gray-900">{{ $serverInfo['laravel_version'] }}</span>
                            </div>
                            <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                <span class="text-sm font-medium text-gray-600">Versi PHP</span>
                                <span class="text-sm text-gray-900">{{ $serverInfo['php_version'] }}</span>
                            </div>
                            <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                <span class="text-sm font-medium text-gray-600">Environment</span>
                                <span class="text-sm text-gray-900 capitalize">{{ $serverInfo['environment'] }}</span>
                            </div>
                            <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                <span class="text-sm font-medium text-gray-600">Timezone</span>
                                <span class="text-sm text-gray-900">{{ $serverInfo['timezone'] }}</span>
                            </div>
                            <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                <span class="text-sm font-medium text-gray-600">Database</span>
                                <span
                                    class="text-sm text-gray-900 capitalize">{{ $serverInfo['database_connection'] }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Aktivitas Terbaru -->
                <div class="bg-white rounded-lg md:rounded-xl shadow-md border border-gray-100">
                    <div class="px-4 md:px-6 py-3 md:py-4 border-b border-gray-200">
                        <h3 class="text-base md:text-lg font-semibold text-gray-800 flex items-center">
                            <i class="fas fa-history mr-2 text-blue-600"></i>
                            Aktivitas Terbaru
                        </h3>
                    </div>
                    <div class="p-4 md:p-6">
                        @forelse($aktivitasTerbaru as $aktivitas)
                            <div
                                class="flex items-start space-x-3 p-3 rounded-lg hover:bg-gray-50 transition-colors duration-150 mb-3 border border-gray-100">
                                @if (isset($aktivitas->nip))
                                    <!-- Absensi Activity -->
                                    <div
                                        class="flex-shrink-0 w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mt-0.5">
                                        <i class="fas fa-clipboard-check text-blue-600 text-xs"></i>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900 truncate">
                                            @if ($aktivitas->waktu_pulang)
                                                Absensi Pulang - {{ $aktivitas->nama ?? 'User' }}
                                            @else
                                                Absensi Masuk - {{ $aktivitas->nama ?? 'User' }}
                                            @endif
                                        </p>
                                        <p class="text-xs text-gray-500 mt-1">
                                            <span class="font-medium">NIP:</span> {{ $aktivitas->nip ?? 'N/A' }}
                                        </p>
                                        <p class="text-xs text-gray-500">
                                            @if ($aktivitas->waktu_pulang)
                                                <span class="font-medium">Pulang:</span>
                                                {{ $aktivitas->waktu_pulang ? $aktivitas->waktu_pulang->format('H:i') : 'N/A' }}
                                            @else
                                                <span class="font-medium">Masuk:</span>
                                                {{ $aktivitas->waktu_masuk ? $aktivitas->waktu_masuk->format('H:i') : 'N/A' }}
                                            @endif
                                            •
                                            {{ $aktivitas->created_at ? $aktivitas->created_at->diffForHumans() : 'N/A' }}
                                        </p>
                                    </div>
                                    <span
                                        class="px-2 py-1 text-xs font-medium 
                        @if ($aktivitas->waktu_pulang) bg-purple-100 text-purple-800 
                        @else bg-yellow-100 text-yellow-800 @endif rounded-full whitespace-nowrap">
                                        @if ($aktivitas->waktu_pulang)
                                            Pulang
                                        @else
                                            Masuk
                                        @endif
                                    </span>
                                @else
                                    <!-- Inspeksi Activity -->
                                    <div
                                        class="flex-shrink-0 w-8 h-8 bg-green-100 rounded-full flex items-center justify-center mt-0.5">
                                        <i class="fas fa-chart-bar text-green-600 text-xs"></i>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900">
                                            Inspeksi
                                        </p>
                                        <p class="text-xs text-gray-500 mt-1">
                                            <span class="font-medium">Oleh:</span>
                                            {{ $aktivitas->pengawas->nama ?? ($aktivitas->pengawas->name ?? 'Pengawas') }}
                                        </p>
                                        <p class="text-xs text-gray-500">
                                            <span class="font-medium">Kategori:</span>
                                            {{ $aktivitas->kategori->nama ?? 'Semua Kategori' }}
                                            •
                                            {{ $aktivitas->created_at ? $aktivitas->created_at->diffForHumans() : 'N/A' }}
                                        </p>
                                    </div>
                                    <span
                                        class="px-2 py-1 text-xs font-medium bg-green-100 text-green-800 rounded-full whitespace-nowrap">
                                        Inspeksi
                                    </span>
                                @endif
                            </div>
                        @empty
                            <div class="text-center py-6">
                                <i class="fas fa-inbox text-3xl text-gray-300 mb-3"></i>
                                <p class="text-gray-500 text-sm">Belum ada aktivitas terbaru</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="mt-8 bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-bolt mr-2 text-yellow-500"></i>
                        Akses Cepat
                    </h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <a href="{{ route('admin.users') }}"
                            class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition duration-150">
                            <div class="p-3 bg-blue-500 rounded-lg mr-4">
                                <i class="fas fa-users text-white"></i>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900">Kelola Pengguna</p>
                                <p class="text-sm text-gray-600">Tambah, edit, hapus user</p>
                            </div>
                        </a>

                        <a href="{{ route('admin.laporan') }}"
                            class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition duration-150">
                            <div class="p-3 bg-green-500 rounded-lg mr-4">
                                <i class="fas fa-chart-bar text-white"></i>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900">Lihat Laporan</p>
                                <p class="text-sm text-gray-600">Analisis data inspeksi</p>
                            </div>
                        </a>

                        <a href="{{ route('admin.laporan') }}"
                            class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition duration-150">
                            <div class="p-3 bg-purple-500 rounded-lg mr-4">
                                <i class="fas fa-file-export text-white"></i>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900">Export Data</p>
                                <p class="text-sm text-gray-600">Download laporan Excel</p>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
