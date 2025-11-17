@extends('layouts.app')

@section('title', 'Dashboard Pengawas')

@section('content')
    <div class="space-y-4 sm:space-y-6">
        <!-- Welcome Header -->
        <div class="bg-white shadow rounded-lg p-4 sm:p-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-xl sm:text-2xl font-bold text-gray-900">Dashboard Pengawas</h1>
                    <p class="text-gray-600 mt-1 text-sm">Selamat datang, <span
                            class="font-semibold">{{ Auth::user()->name }}</span></p>
                </div>
                <div class="mt-3 sm:mt-0 text-xs sm:text-sm text-gray-500 bg-gray-50 px-3 py-2 rounded-lg">
                    <i class="fas fa-calendar-alt mr-2"></i>
                    {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
                </div>
            </div>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-2 gap-3 sm:gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <!-- Status Hari Ini -->
            <div class="bg-white shadow rounded-lg p-3 sm:p-4 hover:shadow-md transition-shadow duration-200">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div
                            class="w-8 h-8 sm:w-10 sm:h-10 {{ $sudahInspeksiHariIni ? 'bg-green-100' : 'bg-red-100' }} rounded-lg flex items-center justify-center">
                            <i
                                class="fas {{ $sudahInspeksiHariIni ? 'fa-check-circle text-green-600' : 'fa-times-circle text-red-600' }} text-sm sm:text-base"></i>
                        </div>
                    </div>
                    <div class="ml-2 sm:ml-3">
                        <h3 class="text-xs sm:text-sm font-medium text-gray-900">Status Hari Ini</h3>
                        <p
                            class="mt-1 text-sm sm:text-lg font-semibold {{ $sudahInspeksiHariIni ? 'text-green-600' : 'text-red-600' }}">
                            {{ $sudahInspeksiHariIni ? 'Sudah' : 'Belum' }}
                        </p>
                        <p class="text-xs text-gray-500 hidden sm:block">
                            @if ($sudahInspeksiHariIni && $inspeksiHariIni)
                                Oleh: {{ $inspeksiHariIni->pengawas->name ?? 'Pengawas' }}
                            @else
                                Menunggu inspeksi
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            <!-- Total Inspeksi -->
            <div class="bg-white shadow rounded-lg p-3 sm:p-4 hover:shadow-md transition-shadow duration-200">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 sm:w-10 sm:h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-clipboard-list text-blue-600 text-sm sm:text-base"></i>
                        </div>
                    </div>
                    <div class="ml-2 sm:ml-3">
                        <h3 class="text-xs sm:text-sm font-medium text-gray-900">Total Inspeksi</h3>
                        <p class="mt-1 text-sm sm:text-2xl font-bold text-gray-900">{{ $totalInspeksi }}</p>
                        <p class="text-xs text-gray-500 hidden sm:block">Semua waktu</p>
                    </div>
                </div>
            </div>

            <!-- Bulan Ini -->
            <div class="bg-white shadow rounded-lg p-3 sm:p-4 hover:shadow-md transition-shadow duration-200">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 sm:w-10 sm:h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-calendar-alt text-purple-600 text-sm sm:text-base"></i>
                        </div>
                    </div>
                    <div class="ml-2 sm:ml-3">
                        <h3 class="text-xs sm:text-sm font-medium text-gray-900">Bulan Ini</h3>
                        <p class="mt-1 text-sm sm:text-2xl font-bold text-gray-900">{{ $totalBulanIni }}</p>
                        <p class="text-xs text-gray-500 hidden sm:block">
                            {{ \Carbon\Carbon::now()->translatedFormat('F Y') }}</p>
                    </div>
                </div>
            </div>

            <!-- Total Pengawas -->
            <div class="bg-white shadow rounded-lg p-3 sm:p-4 hover:shadow-md transition-shadow duration-200">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 sm:w-10 sm:h-10 bg-orange-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-users text-orange-600 text-sm sm:text-base"></i>
                        </div>
                    </div>
                    <div class="ml-2 sm:ml-3">
                        <h3 class="text-xs sm:text-sm font-medium text-gray-900">Total Pengawas</h3>
                        <p class="mt-1 text-sm sm:text-2xl font-bold text-gray-900">{{ $totalPengawas }}</p>
                        <p class="text-xs text-gray-500 hidden sm:block">Aktif</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions & Laporan -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6">
            <!-- Quick Actions -->
            <div class="bg-white shadow rounded-lg p-4 sm:p-6 hover:shadow-md transition-shadow duration-200">
                <h2 class="text-base sm:text-lg font-semibold text-gray-900 mb-3 sm:mb-4 flex items-center">
                    <i class="fas fa-bolt text-yellow-500 mr-2 text-sm"></i>
                    Quick Actions
                </h2>
                <div class="space-y-3">
                    @if (!$sudahInspeksiHariIni)
                        <a href="{{ route('pengawas.inspeksi') }}" class="block">
                            <div
                                class="flex items-start p-3 hover:bg-gray-50 rounded-lg transition duration-200 cursor-pointer">
                                <div class="flex-shrink-0 mt-0.5">
                                    <div
                                        class="w-5 h-5 sm:w-6 sm:h-6 border-2 border-gray-300 rounded flex items-center justify-center">
                                        <!-- Empty checkbox -->
                                    </div>
                                </div>
                                <div class="ml-2 sm:ml-3">
                                    <p class="text-sm font-medium text-gray-900">Lakukan Inspeksi</p>
                                    <p class="text-xs text-gray-500 mt-1">Mulai inspeksi baru</p>
                                </div>
                            </div>
                        </a>
                    @else
                        <div class="flex items-start p-3 bg-green-50 rounded-lg transition duration-200">
                            <div class="flex-shrink-0 mt-0.5">
                                <div class="w-5 h-5 sm:w-6 sm:h-6 bg-green-500 rounded flex items-center justify-center">
                                    <i class="fas fa-check text-white text-xs"></i>
                                </div>
                            </div>
                            <div class="ml-2 sm:ml-3">
                                <p class="text-sm font-medium text-gray-900">Inspeksi harian selesai</p>
                                <p class="text-xs text-gray-500 mt-1">(1 inspeksi per hari)</p>
                                @if ($inspeksiHariIni)
                                    <p class="text-xs text-green-600 mt-1">
                                        Oleh: {{ $inspeksiHariIni->pengawas->name ?? 'Pengawas' }}
                                        ({{ \Carbon\Carbon::parse($inspeksiHariIni->created_at)->timezone('Asia/Kuala_Lumpur')->format('H:i') }})
                                    </p>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Laporan -->
            <div class="bg-white shadow rounded-lg p-4 sm:p-6 hover:shadow-md transition-shadow duration-200">
                <h2 class="text-base sm:text-lg font-semibold text-gray-900 mb-3 sm:mb-4 flex items-center">
                    <i class="fas fa-chart-bar text-blue-500 mr-2 text-sm"></i>
                    Laporan
                </h2>
                <div class="space-y-3">
                    <a href="{{ route('pengawas.laporan') }}" class="block">
                        <div
                            class="flex items-start p-3 hover:bg-gray-50 rounded-lg transition duration-200 cursor-pointer">
                            <div class="flex-shrink-0 mt-0.5">
                                <div
                                    class="w-5 h-5 sm:w-6 sm:h-6 border-2 border-gray-300 rounded flex items-center justify-center">
                                    <!-- Empty checkbox -->
                                </div>
                            </div>
                            <div class="ml-2 sm:ml-3">
                                <p class="text-sm font-medium text-gray-900">Lihat Semua Laporan</p>
                                <p class="text-xs text-gray-500 mt-1">Akses laporan lengkap</p>
                            </div>
                        </div>
                    </a>
                    <div class="flex items-start p-3 bg-green-50 rounded-lg transition duration-200">
                        <div class="flex-shrink-0 mt-0.5">
                            <div class="w-5 h-5 sm:w-6 sm:h-6 bg-green-500 rounded flex items-center justify-center">
                                <i class="fas fa-check text-white text-xs"></i>
                            </div>
                        </div>
                        <div class="ml-2 sm:ml-3">
                            <p class="text-sm font-medium text-gray-900">Akses semua data inspeksi</p>
                            <p class="text-xs text-gray-500 mt-1">Data historis tersedia</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activity - IMPROVED FOR MOBILE -->
        @if ($inspeksiTerbaru->count() > 0)
            <div class="bg-white shadow rounded-lg p-4 sm:p-6 hover:shadow-md transition-shadow duration-200">
                <h2 class="text-base sm:text-lg font-semibold text-gray-900 mb-3 sm:mb-4 flex items-center">
                    <i class="fas fa-history text-gray-500 mr-2 text-sm"></i>
                    Aktivitas Terbaru
                </h2>
                <div class="space-y-2">
                    @foreach ($inspeksiTerbaru as $inspeksi)
                        <div
                            class="flex items-center justify-between p-2 sm:p-3 hover:bg-gray-50 rounded-lg transition duration-200 border border-gray-100">
                            <div class="flex items-center flex-1 min-w-0">
                                <div class="flex-shrink-0">
                                    <div
                                        class="w-6 h-6 sm:w-8 sm:h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                        <i class="fas fa-clipboard-check text-blue-600 text-xs"></i>
                                    </div>
                                </div>
                                <div class="ml-2 sm:ml-3 flex-1 min-w-0">
                                    <p class="text-xs sm:text-sm font-medium text-gray-900 truncate">
                                        Inspeksi {{ $inspeksi->kategori->nama ?? 'Semua Kategori' }}
                                    </p>
                                    <p class="text-xs text-gray-500 truncate">
                                        {{ $inspeksi->pengawas->name ?? 'Pengawas' }} •
                                        {{ \Carbon\Carbon::parse($inspeksi->tanggal)->translatedFormat('d M Y') }}
                                    </p>
                                </div>
                            </div>
                            <span
                                class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 ml-2 flex-shrink-0">
                                {{ $inspeksi->jawaban->count() }}
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
@endsection
