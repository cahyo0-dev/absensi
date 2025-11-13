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
                <div class="bg-white rounded-lg shadow">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                            <i class="fas fa-history mr-2 text-gray-600"></i>
                            Aktivitas Terbaru
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            @forelse($recentActivities as $activity)
                                <div class="flex items-start py-2">
                                    <div class="flex-shrink-0">
                                        <div class="p-2 bg-blue-100 rounded-lg">
                                            <i class="fas fa-clipboard-check text-blue-500 text-sm"></i>
                                        </div>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-gray-900">{{ $activity['message'] }}</p>
                                        <p class="text-xs text-gray-500">{{ $activity['time'] }}</p>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-4 text-gray-500">
                                    <i class="fas fa-inbox text-2xl mb-2"></i>
                                    <p>Tidak ada aktivitas terbaru</p>
                                </div>
                            @endforelse
                        </div>
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

                        <a href="{{ route('admin.export') }}"
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
