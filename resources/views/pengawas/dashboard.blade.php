<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Pengawas</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body class="bg-gray-100">
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <div class="w-64 bg-blue-800 text-white">
            <div class="p-4">
                <h1 class="text-2xl font-bold">Sistem Inspeksi</h1>
                <p class="text-blue-200 text-sm">Dashboard Pengawas</p>
            </div>
            <nav class="mt-6">
                <a href="{{ route('pengawas.dashboard') }}" class="block py-3 px-4 bg-blue-900 text-white">
                    <i class="fas fa-tachometer-alt mr-3"></i>Dashboard
                </a>
                <a href="{{ route('pengawas.inspeksi') }}" class="block py-3 px-4 hover:bg-blue-700 text-white">
                    <i class="fas fa-clipboard-check mr-3"></i>Inspeksi
                </a>
                <a href="{{ route('pengawas.laporan') }}" class="block py-3 px-4 hover:bg-blue-700 text-white">
                    <i class="fas fa-chart-bar mr-3"></i>Laporan
                </a>
                <form method="POST" action="{{ route('logout') }}" class="mt-4">
                    @csrf
                    <button type="submit" class="w-full text-left block py-3 px-4 hover:bg-blue-700 text-white">
                        <i class="fas fa-sign-out-alt mr-3"></i>Logout
                    </button>
                </form>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="flex-1">
            <!-- Header -->
            <header class="bg-white shadow">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
                    <div class="flex justify-between items-center">
                        <h1 class="text-2xl font-bold text-gray-900">Dashboard Pengawas</h1>
                        <div class="text-right">
                            <p class="text-gray-600">Halo, {{ Auth::user()->name }}</p>
                            <p class="text-sm text-gray-500">{{ now()->format('d F Y') }}</p>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Stats Grid -->
            <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <!-- Inspeksi Hari Ini -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-blue-100 text-blue-500">
                                <i class="fas fa-clipboard-check text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-semibold text-gray-700">Status Hari Ini</h3>
                                <p
                                    class="text-2xl font-bold @if ($inspeksiHariIni) text-green-600 @else text-red-600 @endif">
                                    @if ($inspeksiHariIni)
                                        Sudah Inspeksi
                                    @else
                                        Belum Inspeksi
                                    @endif
                                </p>
                                @if ($inspeksiHariIni && $inspektorHariIni)
                                    <p class="text-sm text-gray-500 mt-1">
                                        Oleh: {{ $inspektorHariIni->name }}
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Total Inspeksi -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-green-100 text-green-500">
                                <i class="fas fa-chart-bar text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-semibold text-gray-700">Total Inspeksi</h3>
                                <p class="text-2xl font-bold text-green-600">{{ $totalInspeksi }}</p>
                                <p class="text-sm text-gray-500 mt-1">Semua waktu</p>
                            </div>
                        </div>
                    </div>

                    <!-- Inspeksi Bulan Ini -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-purple-100 text-purple-500">
                                <i class="fas fa-calendar-alt text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-semibold text-gray-700">Bulan Ini</h3>
                                <p class="text-2xl font-bold text-purple-600">{{ $inspeksiBulanIni }}</p>
                                <p class="text-sm text-gray-500 mt-1">{{ now()->format('F Y') }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Total Pengawas -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-orange-100 text-orange-500">
                                <i class="fas fa-users text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-semibold text-gray-700">Total Pengawas</h3>
                                <p class="text-2xl font-bold text-orange-600">{{ $totalPengawas }}</p>
                                <p class="text-sm text-gray-500 mt-1">Aktif</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Lakukan Inspeksi -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <h3 class="text-xl font-bold text-gray-900 mb-4">Quick Actions</h3>
                        <div class="space-y-4">
                            <a href="{{ route('pengawas.inspeksi') }}"
                                class="block w-full bg-blue-600 hover:bg-blue-700 text-white py-3 px-4 rounded-lg text-center transition duration-200">
                                <i class="fas fa-plus-circle mr-2"></i>Lakukan Inspeksi
                            </a>
                            <p class="text-sm text-gray-600 text-center">
                                Inspeksi harian untuk hari ini<br>
                                <span class="text-xs text-gray-500">(Hanya 1 inspeksi per hari untuk semua
                                    pengawas)</span>
                            </p>
                        </div>
                    </div>

                    <!-- Lihat Laporan -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <h3 class="text-xl font-bold text-gray-900 mb-4">Laporan</h3>
                        <div class="space-y-4">
                            <a href="{{ route('pengawas.laporan') }}"
                                class="block w-full bg-green-600 hover:bg-green-700 text-white py-3 px-4 rounded-lg text-center transition duration-200">
                                <i class="fas fa-chart-bar mr-2"></i>Lihat Semua Laporan
                            </a>
                            <p class="text-sm text-gray-600 text-center">
                                Akses semua data inspeksi yang telah dilakukan
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Info Sistem -->
                <div class="mt-8 bg-yellow-50 border border-yellow-200 rounded-lg p-6">
                    <div class="flex items-start">
                        <i class="fas fa-info-circle text-yellow-500 mt-1 mr-3"></i>
                        <div>
                            <h4 class="font-semibold text-yellow-800">Informasi Sistem</h4>
                            <p class="text-yellow-700 mt-1">
                                • Sistem hanya mengizinkan <strong>1 inspeksi per hari</strong> untuk semua pengawas<br>
                                • Total pengawas aktif: <strong>{{ $totalPengawas }} orang</strong><br>
                                • Hari ini:
                                @if ($inspeksiHariIni)
                                    <span class="text-green-600">Inspeksi sudah dilakukan</span>
                                @else
                                    <span class="text-red-600">Belum ada inspeksi</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>

</html>
