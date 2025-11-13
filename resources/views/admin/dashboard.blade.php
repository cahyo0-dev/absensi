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
    <div class="bg-white rounded-xl shadow-md border border-gray-100">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                <i class="fas fa-history mr-2 text-blue-600"></i>
                Aktivitas Terbaru
            </h3>
        </div>
        <div class="p-6">
            @forelse($aktivitasTerbaru as $aktivitas)
                <div
                    class="flex items-center space-x-4 p-3 rounded-lg hover:bg-gray-50 transition-colors duration-150 mb-3">
                    @if (isset($aktivitas->nip))
                        <!-- Absensi Activity -->
                        <div class="flex-shrink-0 w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-clipboard-check text-blue-600"></i>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-900">
                                @if ($aktivitas->waktu_pulang)
                                    Absensi Pulang - {{ $aktivitas->nama ?? 'User' }}
                                @else
                                    Absensi Masuk - {{ $aktivitas->nama ?? 'User' }}
                                @endif
                            </p>
                            <p class="text-sm text-gray-500">
                                NIP: {{ $aktivitas->nip ?? 'N/A' }} •
                                @if ($aktivitas->waktu_pulang)
                                    Pulang: {{ $aktivitas->waktu_pulang ? $aktivitas->waktu_pulang->format('H:i') : 'N/A' }}
                                @else
                                    Masuk: {{ $aktivitas->waktu_masuk ? $aktivitas->waktu_masuk->format('H:i') : 'N/A' }}
                                @endif
                                • {{ $aktivitas->created_at ? $aktivitas->created_at->diffForHumans() : 'N/A' }}
                            </p>
                        </div>
                        <span
                            class="px-3 py-1 text-xs font-medium 
                            @if ($aktivitas->waktu_pulang) bg-purple-100 text-purple-800 
                            @else bg-yellow-100 text-yellow-800 @endif rounded-full">
                            @if ($aktivitas->waktu_pulang)
                                Pulang
                            @else
                                Masuk
                            @endif
                        </span>
                    @else
                        <!-- Inspeksi Activity -->
                        <div class="flex-shrink-0 w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-chart-bar text-green-600"></i>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-900">
                                Inspeksi oleh
                                {{ $aktivitas->pengawas->nama ?? ($aktivitas->pengawas->name ?? 'Pengawas') }}
                            </p>
                            <p class="text-sm text-gray-500">
                                Kategori: {{ $aktivitas->kategori->nama ?? 'Tidak ada kategori' }} •
                                {{ $aktivitas->created_at ? $aktivitas->created_at->diffForHumans() : 'N/A' }}
                            </p>
                        </div>
                        <span class="px-3 py-1 text-xs font-medium bg-green-100 text-green-800 rounded-full">
                            Inspeksi
                        </span>
                    @endif
                </div>
            @empty
                <div class="text-center py-8">
                    <i class="fas fa-inbox text-4xl text-gray-300 mb-4"></i>
                    <p class="text-gray-500">Belum ada aktivitas terbaru</p>
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
                                NIP
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Nama
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Waktu Masuk
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Waktu Pulang
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
