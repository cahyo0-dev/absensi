@extends('layouts.admin')

@section('title', 'Dashboard Admin')

@section('content')
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800">Dashboard</h1>
        <p class="text-gray-600 mt-2">Welcome back, {{ Auth::user()->nama ?? (Auth::user()->name ?? 'Admin') }}! Here's
            what's
            happening today.</p>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <!-- Total Users Card -->
        <div class="bg-white rounded-xl shadow-md border border-gray-100 p-6 hover:shadow-lg transition-shadow duration-200">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600 mr-4">
                    <i class="fas fa-users text-xl"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Users</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $totalUsers }}</p>
                </div>
            </div>
        </div>

        <!-- Total Absensi Card -->
        <div
            class="bg-white rounded-xl shadow-md border border-gray-100 p-6 hover:shadow-lg transition-shadow duration-200">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-600 mr-4">
                    <i class="fas fa-clipboard-check text-xl"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Absensi</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $totalAbsensi }}</p>
                </div>
            </div>
        </div>

        <!-- Absensi Masuk Hari Ini -->
        <div
            class="bg-white rounded-xl shadow-md border border-gray-100 p-6 hover:shadow-lg transition-shadow duration-200">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100 text-yellow-600 mr-4">
                    <i class="fas fa-sign-in-alt text-xl"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-600">Masuk Hari Ini</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $absensiMasukHariIni ?? 0 }}</p>
                </div>
            </div>
        </div>

        <!-- Absensi Pulang Hari Ini -->
        <div
            class="bg-white rounded-xl shadow-md border border-gray-100 p-6 hover:shadow-lg transition-shadow duration-200">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-purple-100 text-purple-600 mr-4">
                    <i class="fas fa-sign-out-alt text-xl"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-600">Pulang Hari Ini</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $absensiPulangHariIni ?? 0 }}</p>
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
                        <div class="flex-shrink-0 w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mt-0.5">
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
                                • {{ $aktivitas->created_at ? $aktivitas->created_at->diffForHumans() : 'N/A' }}
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
                                • {{ $aktivitas->created_at ? $aktivitas->created_at->diffForHumans() : 'N/A' }}
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

    <!-- Tabel Absensi Hari Ini -->
    <div class="bg-white rounded-xl shadow-md border border-gray-100 mt-6">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                <i class="fas fa-calendar-day mr-2 text-green-600"></i>
                Absensi Hari Ini
            </h3>
        </div>
        <div class="p-6">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <i class="fas fa-id-card mr-1"></i>NIP
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <i class="fas fa-user mr-1"></i>Nama
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <i class="fas fa-clock mr-1"></i>Waktu Masuk
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <i class="fas fa-clock mr-1"></i>Waktu Pulang
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($absensiHariIni as $absensi)
                            <tr class="hover:bg-gray-50 transition-colors duration-150">
                                <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ $absensi->nip }}
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">
                                    {{ $absensi->nama }}
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">
                                    {{ $absensi->waktu_masuk ? $absensi->waktu_masuk->format('H:i') : '-' }}
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">
                                    {{ $absensi->waktu_pulang ? $absensi->waktu_pulang->format('H:i') : '-' }}
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    @if ($absensi->waktu_pulang)
                                        <span
                                            class="px-2 py-1 text-xs font-medium bg-green-100 text-green-800 rounded-full">
                                            Selesai
                                        </span>
                                    @else
                                        <span
                                            class="px-2 py-1 text-xs font-medium bg-yellow-100 text-yellow-800 rounded-full">
                                            Masuk
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-8 text-center text-gray-500">
                                    <i class="fas fa-inbox text-2xl mb-2 block"></i>
                                    Belum ada absensi hari ini
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
