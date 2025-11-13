<!-- resources/views/admin/laporan.blade.php -->
@extends('layouts.admin')

@section('title', 'Laporan & Analytics')

@section('content')
    @php
        // FORCE CONVERSION - Pastikan semua variabel adalah collection
        $absensis = $absensis ?? collect();
        $inspeksis = $inspeksis ?? collect();
        $users = $users ?? collect();

        // Convert ke collection jika bukan collection
        if (!($absensis instanceof \Illuminate\Support\Collection)) {
            $absensis = collect($absensis);
        }
        if (!($inspeksis instanceof \Illuminate\Support\Collection)) {
            $inspeksis = collect($inspeksis);
        }
        if (!($users instanceof \Illuminate\Support\Collection)) {
            $users = collect($users);
        }
    @endphp

    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800">Laporan & Analytics</h1>
        <p class="text-gray-600 mt-2">Kelola dan ekspor data sistem secara lengkap</p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm">Total Absensi</p>
                    <p class="text-2xl font-bold mt-1">{{ count($absensis) }}</p>
                </div>
                <div class="p-3 bg-blue-400 rounded-lg">
                    <i class="fas fa-clipboard-check text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm">Total Inspeksi</p>
                    <p class="text-2xl font-bold mt-1">{{ count($inspeksis) }}</p>
                </div>
                <div class="p-3 bg-green-400 rounded-lg">
                    <i class="fas fa-chart-bar text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-100 text-sm">Total Users</p>
                    <p class="text-2xl font-bold mt-1">{{ count($users) }}</p>
                </div>
                <div class="p-3 bg-purple-400 rounded-lg">
                    <i class="fas fa-users text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Analytics Section - DIPINDAHKAN KE LUAR STATS CARDS -->
    <div class="bg-white rounded-xl shadow-md border border-gray-100 mb-8">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                <i class="fas fa-chart-line mr-2 text-purple-600"></i>
                Analytics & Statistik
            </h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="text-center p-4 bg-blue-50 rounded-lg">
                    <div class="text-2xl font-bold text-blue-600 mb-2">{{ count($absensis) }}</div>
                    <div class="text-sm text-blue-800 font-medium">Total Absensi</div>
                    <div class="text-xs text-blue-600 mt-1">Data kehadiran</div>
                </div>
                
                <div class="text-center p-4 bg-green-50 rounded-lg">
                    <div class="text-2xl font-bold text-green-600 mb-2">{{ count($inspeksis) }}</div>
                    <div class="text-sm text-green-800 font-medium">Total Inspeksi</div>
                    <div class="text-xs text-green-600 mt-1">Pemeriksaan dilakukan</div>
                </div>
                
                <div class="text-center p-4 bg-purple-50 rounded-lg">
                    <div class="text-2xl font-bold text-purple-600 mb-2">{{ count($users) }}</div>
                    <div class="text-sm text-purple-800 font-medium">Total Users</div>
                    <div class="text-xs text-purple-600 mt-1">Pengguna terdaftar</div>
                </div>
                
                <div class="text-center p-4 bg-orange-50 rounded-lg">
                    @php
                        $totalData = count($absensis) + count($inspeksis) + count($users);
                    @endphp
                    <div class="text-2xl font-bold text-orange-600 mb-2">{{ $totalData }}</div>
                    <div class="text-sm text-orange-800 font-medium">Total Data</div>
                    <div class="text-xs text-orange-600 mt-1">Semua data sistem</div>
                </div>
            </div>
            
            <!-- Simple Chart Placeholder -->
            <div class="mt-6 p-4 bg-gray-50 rounded-lg">
                <h4 class="font-semibold text-gray-800 mb-4">Distribusi Data</h4>
                <div class="space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Absensi</span>
                        <div class="w-32 bg-gray-200 rounded-full h-2">
                            <div class="bg-blue-600 h-2 rounded-full" 
                                 style="width: {{ count($absensis) > 0 ? (count($absensis) / max($totalData, 1)) * 100 : 0 }}%"></div>
                        </div>
                        <span class="text-sm font-medium text-gray-700">{{ count($absensis) }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Inspeksi</span>
                        <div class="w-32 bg-gray-200 rounded-full h-2">
                            <div class="bg-green-600 h-2 rounded-full" 
                                 style="width: {{ count($inspeksis) > 0 ? (count($inspeksis) / max($totalData, 1)) * 100 : 0 }}%"></div>
                        </div>
                        <span class="text-sm font-medium text-gray-700">{{ count($inspeksis) }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Users</span>
                        <div class="w-32 bg-gray-200 rounded-full h-2">
                            <div class="bg-purple-600 h-2 rounded-full" 
                                 style="width: {{ count($users) > 0 ? (count($users) / max($totalData, 1)) * 100 : 0 }}%"></div>
                        </div>
                        <span class="text-sm font-medium text-gray-700">{{ count($users) }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="bg-white rounded-xl shadow-md border border-gray-100 mb-8">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                <i class="fas fa-filter mr-2 text-blue-600"></i>
                Filter Data
            </h3>
        </div>
        <div class="p-6">
            <form method="GET" action="{{ route('admin.laporan') }}">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-calendar-start mr-1 text-gray-500"></i>
                            Tanggal Mulai
                        </label>
                        <input type="date" name="start_date" value="{{ request('start_date') }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-calendar-end mr-1 text-gray-500"></i>
                            Tanggal Akhir
                        </label>
                        <input type="date" name="end_date" value="{{ request('end_date') }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200">
                    </div>
                    <div class="flex items-end">
                        <button type="submit"
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-lg transition duration-200 flex items-center justify-center">
                            <i class="fas fa-search mr-2"></i>
                            Terapkan Filter
                        </button>
                    </div>
                    <div class="flex items-end">
                        <a href="{{ route('admin.laporan') }}"
                            class="w-full bg-gray-500 hover:bg-gray-600 text-white py-2 px-4 rounded-lg transition duration-200 flex items-center justify-center">
                            <i class="fas fa-redo mr-2"></i>
                            Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Export Section -->
    <div class="bg-white rounded-xl shadow-md border border-gray-100 mb-8">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                <i class="fas fa-file-export mr-2 text-green-600"></i>
                Export Data
            </h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <form method="GET" action="{{ route('admin.export') }}" class="flex flex-col">
                    <input type="hidden" name="type" value="absensi">
                    <input type="hidden" name="start_date" value="{{ request('start_date') }}">
                    <input type="hidden" name="end_date" value="{{ request('end_date') }}">
                    <button type="submit"
                        class="flex-1 bg-green-500 hover:bg-green-600 text-white py-4 px-6 rounded-lg transition duration-200 flex items-center justify-center space-x-3 hover:shadow-lg transform hover:-translate-y-1">
                        <i class="fas fa-clipboard-list text-2xl"></i>
                        <div class="text-left">
                            <div class="font-semibold">Export Absensi</div>
                            <div class="text-green-100 text-sm">{{ count($absensis) }} records</div>
                        </div>
                    </button>
                </form>

                <form method="GET" action="{{ route('admin.export') }}" class="flex flex-col">
                    <input type="hidden" name="type" value="inspeksi">
                    <input type="hidden" name="start_date" value="{{ request('start_date') }}">
                    <input type="hidden" name="end_date" value="{{ request('end_date') }}">
                    <button type="submit"
                        class="flex-1 bg-green-500 hover:bg-green-600 text-white py-4 px-6 rounded-lg transition duration-200 flex items-center justify-center space-x-3 hover:shadow-lg transform hover:-translate-y-1">
                        <i class="fas fa-chart-bar text-2xl"></i>
                        <div class="text-left">
                            <div class="font-semibold">Export Inspeksi</div>
                            <div class="text-green-100 text-sm">{{ count($inspeksis) }} records</div>
                        </div>
                    </button>
                </form>

                <form method="GET" action="{{ route('admin.export') }}" class="flex flex-col">
                    <input type="hidden" name="type" value="users">
                    <input type="hidden" name="start_date" value="{{ request('start_date') }}">
                    <input type="hidden" name="end_date" value="{{ request('end_date') }}">
                    <button type="submit"
                        class="flex-1 bg-green-500 hover:bg-green-600 text-white py-4 px-6 rounded-lg transition duration-200 flex items-center justify-center space-x-3 hover:shadow-lg transform hover:-translate-y-1">
                        <i class="fas fa-users text-2xl"></i>
                        <div class="text-left">
                            <div class="font-semibold">Export Users</div>
                            <div class="text-green-100 text-sm">{{ count($users) }} records</div>
                        </div>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Data Tabs -->
    <div class="bg-white rounded-xl shadow-md border border-gray-100">
        <div class="border-b border-gray-200">
            <nav class="-mb-px flex space-x-8 px-6">
                <button id="tab-absensi"
                    class="tab-button whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm border-blue-500 text-blue-600 flex items-center">
                    <i class="fas fa-clipboard-list mr-2"></i>
                    Absensi ({{ count($absensis) }})
                </button>
                <button id="tab-inspeksi"
                    class="tab-button whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 flex items-center">
                    <i class="fas fa-chart-bar mr-2"></i>
                    Inspeksi ({{ count($inspeksis) }})
                </button>
                <button id="tab-users"
                    class="tab-button whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 flex items-center">
                    <i class="fas fa-users mr-2"></i>
                    Users ({{ count($users) }})
                </button>
            </nav>
        </div>

        <!-- Tab Content -->
        <div class="p-6">
            <!-- Absensi Tab -->
            <div id="content-absensi" class="tab-content">
                @if (count($absensis) > 0)
                    <div class="overflow-x-auto rounded-lg border border-gray-200">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <i class="fas fa-id-card mr-1"></i>NIP
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <i class="fas fa-user mr-1"></i>Nama
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <i class="fas fa-briefcase mr-1"></i>Jabatan
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <i class="fas fa-building mr-1"></i>Unit Kerja
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <i class="fas fa-map-marker-alt mr-1"></i>Provinsi
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <i class="fas fa-calendar mr-1"></i>Tanggal
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($absensis as $absensi)
                                    <tr class="hover:bg-gray-50 transition duration-150">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            {{ $absensi->nip ?? 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $absensi->nama ?? 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $absensi->jabatan ?? 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $absensi->unit_kerja ?? 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                {{ $absensi->provinsi ?? 'N/A' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $absensi->created_at ? $absensi->created_at->format('d/m/Y H:i') : 'N/A' }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-12">
                        <i class="fas fa-clipboard-list text-4xl text-gray-300 mb-4"></i>
                        <p class="text-gray-500 text-lg">Tidak ada data absensi</p>
                        <p class="text-gray-400 text-sm mt-2">Data absensi akan muncul di sini</p>
                    </div>
                @endif
            </div>

            <!-- Inspeksi Tab -->
            <div id="content-inspeksi" class="tab-content hidden">
                @if (count($inspeksis) > 0)
                    <div class="overflow-x-auto rounded-lg border border-gray-200">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <i class="fas fa-user-check mr-1"></i>Pengawas
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <i class="fas fa-tags mr-1"></i>Kategori
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <i class="fas fa-calendar mr-1"></i>Tanggal
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <i class="fas fa-list-ol mr-1"></i>Jumlah Pertanyaan
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <i class="fas fa-check-circle mr-1"></i>Jumlah Jawaban
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($inspeksis as $inspeksi)
                                    @php
                                        // Tentukan nama kategori
                                        $kategoriName = 'Semua Kategori'; // Default untuk kategori NULL
                                        $kategoriId = null;
                                        
                                        // Jika ada kategori_id, cari nama kategorinya
                                        if ($inspeksi->kategori_id) {
                                            try {
                                                $kategori = \App\Models\KategoriInspeksi::find($inspeksi->kategori_id);
                                                if ($kategori) {
                                                    $kategoriName = $kategori->nama;
                                                    $kategoriId = $kategori->id;
                                                }
                                            } catch (Exception $e) {
                                                // Tetap gunakan default
                                            }
                                        }

                                        // Hitung jumlah pertanyaan
                                        $jumlahPertanyaan = 0;
                                        if ($kategoriId) {
                                            // Jika ada kategori spesifik, hitung pertanyaan untuk kategori tersebut
                                            try {
                                                $jumlahPertanyaan = \App\Models\Pertanyaan::where('kategori_id', $kategoriId)->count();
                                            } catch (Exception $e) {
                                                $jumlahPertanyaan = 0;
                                            }
                                        } else {
                                            // Jika NULL (Semua Kategori), hitung semua pertanyaan
                                            try {
                                                $jumlahPertanyaan = \App\Models\Pertanyaan::count();
                                            } catch (Exception $e) {
                                                $jumlahPertanyaan = 0;
                                            }
                                        }

                                        // Hitung jumlah jawaban
                                        $jumlahJawaban = 0;
                                        try {
                                            $jumlahJawaban = \App\Models\Jawaban::where('inspeksi_id', $inspeksi->id)->count();
                                        } catch (Exception $e) {
                                            $jumlahJawaban = 0;
                                        }

                                        // Get pengawas name
                                        $pengawasName = 'N/A';
                                        if ($inspeksi->pengawas) {
                                            $pengawasName = $inspeksi->pengawas->nama ?? $inspeksi->pengawas->name ?? 'N/A';
                                        } elseif ($inspeksi->pengawas_id) {
                                            try {
                                                $pengawas = \App\Models\User::find($inspeksi->pengawas_id);
                                                $pengawasName = $pengawas->nama ?? $pengawas->name ?? 'N/A';
                                            } catch (Exception $e) {
                                                // Tetap gunakan default
                                            }
                                        }
                                    @endphp
                                    <tr class="hover:bg-gray-50 transition duration-150">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            {{ $pengawasName }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                {{ $kategoriName }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $inspeksi->created_at ? $inspeksi->created_at->format('d/m/Y H:i') : 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                {{ $jumlahPertanyaan }} pertanyaan
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                                {{ $jumlahJawaban }} jawaban
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-12">
                        <i class="fas fa-chart-bar text-4xl text-gray-300 mb-4"></i>
                        <p class="text-gray-500 text-lg">Tidak ada data inspeksi</p>
                        <p class="text-gray-400 text-sm mt-2">Data inspeksi akan muncul di sini</p>
                    </div>
                @endif
            </div>

            <!-- Users Tab -->
            <!-- Users Tab -->
        <div id="content-users" class="tab-content hidden">
            @if (count($users) > 0)
                <div class="overflow-x-auto rounded-lg border border-gray-200">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <i class="fas fa-id-card mr-1"></i>NIP
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <i class="fas fa-user mr-1"></i>Nama
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <i class="fas fa-envelope mr-1"></i>Email
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <i class="fas fa-user-tag mr-1"></i>Role
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <i class="fas fa-calendar-plus mr-1"></i>Tanggal Bergabung
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($users as $user)
                                <tr class="hover:bg-gray-50 transition duration-150">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ $user->nip ?? 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $user->nama ?? ($user->name ?? 'N/A') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $user->email ?? 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php
                                            $roleColors = [
                                                'admin' => 'bg-red-100 text-red-800',
                                                'pengawas' => 'bg-green-100 text-green-800',
                                                'user' => 'bg-blue-100 text-blue-800',
                                            ];
                                            $roleColor = $roleColors[$user->role ?? 'user'] ?? 'bg-gray-100 text-gray-800';
                                        @endphp
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $roleColor }}">
                                            <i class="fas fa-{{ $user->role == 'admin' ? 'crown' : ($user->role == 'pengawas' ? 'user-check' : 'user') }} mr-1"></i>
                                            {{ $user->role ?? 'user' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $user->created_at ? $user->created_at->format('d/m/Y') : 'N/A' }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-12">
                    <i class="fas fa-users text-4xl text-gray-300 mb-4"></i>
                    <p class="text-gray-500 text-lg">Tidak ada data users</p>
                    <p class="text-gray-400 text-sm mt-2">Data users akan muncul di sini</p>
                </div>
            @endif
        </div>
@endsection

@section('scripts')
<script>
    // DEBUG: Check if script is loaded
    console.log('🔧 Script loaded successfully!');
    
    // Simple tab implementation
    function activateTab(tabName) {
        console.log('Activating tab:', tabName);
        
        // Hide all content
        const contents = document.querySelectorAll('.tab-content');
        contents.forEach(content => {
            content.style.display = 'none';
        });
        
        // Show selected content
        const activeContent = document.getElementById('content-' + tabName);
        if (activeContent) {
            activeContent.style.display = 'block';
        }
        
        // Update tab styles
        const tabs = document.querySelectorAll('.tab-button');
        tabs.forEach(tab => {
            tab.classList.remove('border-blue-500', 'text-blue-600');
            tab.classList.add('border-transparent', 'text-gray-500');
        });
        
        const activeTab = document.getElementById('tab-' + tabName);
        if (activeTab) {
            activeTab.classList.remove('border-transparent', 'text-gray-500');
            activeTab.classList.add('border-blue-500', 'text-blue-600');
        }
    }
    
    // Initialize tabs when page loads
    document.addEventListener('DOMContentLoaded', function() {
        console.log('DOM loaded, initializing tabs...');
        
        // Add click events
        document.getElementById('tab-absensi').addEventListener('click', () => activateTab('absensi'));
        document.getElementById('tab-inspeksi').addEventListener('click', () => activateTab('inspeksi'));
        document.getElementById('tab-users').addEventListener('click', () => activateTab('users'));
        
        // Activate first tab
        activateTab('absensi');
    });
    
    // Fallback initialization
    setTimeout(() => {
        if (document.readyState === 'complete') {
            console.log('Fallback initialization');
            activateTab('absensi');
        }
    }, 1000);
</script>
@endsection