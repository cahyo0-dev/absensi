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

    <div class="mb-6">
        <h1 class="text-2xl md:text-3xl font-bold text-gray-800">Laporan & Analytics</h1>
        <p class="text-gray-600 mt-1 text-sm md:text-base">Kelola dan ekspor data sistem secara lengkap</p>
    </div>

    <!-- Stats Cards - Mobile Optimized -->
    <div class="grid grid-cols-2 md:grid-cols-3 gap-3 md:gap-6 mb-6">
        <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg md:rounded-xl shadow p-3 md:p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-xs md:text-sm">Total Absensi</p>
                    <p class="text-lg md:text-2xl font-bold mt-1">{{ count($absensis) }}</p>
                </div>
                <div class="p-2 md:p-3 bg-blue-400 rounded-lg">
                    <i class="fas fa-clipboard-check text-sm md:text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-lg md:rounded-xl shadow p-3 md:p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-xs md:text-sm">Total Inspeksi</p>
                    <p class="text-lg md:text-2xl font-bold mt-1">{{ count($inspeksis) }}</p>
                </div>
                <div class="p-2 md:p-3 bg-green-400 rounded-lg">
                    <i class="fas fa-chart-bar text-sm md:text-xl"></i>
                </div>
            </div>
        </div>

        <div
            class="col-span-2 md:col-span-1 bg-gradient-to-r from-purple-500 to-purple-600 rounded-lg md:rounded-xl shadow p-3 md:p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-100 text-xs md:text-sm">Total Users</p>
                    <p class="text-lg md:text-2xl font-bold mt-1">{{ count($users) }}</p>
                </div>
                <div class="p-2 md:p-3 bg-purple-400 rounded-lg">
                    <i class="fas fa-users text-sm md:text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Analytics Section - Mobile Optimized -->
    <div class="bg-white rounded-lg md:rounded-xl shadow-md border border-gray-100 mb-6">
        <div class="px-4 md:px-6 py-3 md:py-4 border-b border-gray-200">
            <h3 class="text-base md:text-lg font-semibold text-gray-800 flex items-center">
                <i class="fas fa-chart-line mr-2 text-purple-600"></i>
                Analytics & Statistik
            </h3>
        </div>
        <div class="p-4 md:p-6">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3 md:gap-6">
                <div class="text-center p-3 md:p-4 bg-blue-50 rounded-lg">
                    <div class="text-lg md:text-2xl font-bold text-blue-600 mb-1 md:mb-2">{{ count($absensis) }}</div>
                    <div class="text-xs md:text-sm text-blue-800 font-medium">Total Absensi</div>
                    <div class="text-xs text-blue-600 mt-1">Data kehadiran</div>
                </div>

                <div class="text-center p-3 md:p-4 bg-green-50 rounded-lg">
                    <div class="text-lg md:text-2xl font-bold text-green-600 mb-1 md:mb-2">{{ count($inspeksis) }}</div>
                    <div class="text-xs md:text-sm text-green-800 font-medium">Total Inspeksi</div>
                    <div class="text-xs text-green-600 mt-1">Pemeriksaan dilakukan</div>
                </div>

                <div class="text-center p-3 md:p-4 bg-purple-50 rounded-lg">
                    <div class="text-lg md:text-2xl font-bold text-purple-600 mb-1 md:mb-2">{{ count($users) }}</div>
                    <div class="text-xs md:text-sm text-purple-800 font-medium">Total Users</div>
                    <div class="text-xs text-purple-600 mt-1">Pengguna terdaftar</div>
                </div>

                <div class="text-center p-3 md:p-4 bg-orange-50 rounded-lg">
                    @php
                        $totalData = count($absensis) + count($inspeksis) + count($users);
                    @endphp
                    <div class="text-lg md:text-2xl font-bold text-orange-600 mb-1 md:mb-2">{{ $totalData }}</div>
                    <div class="text-xs md:text-sm text-orange-800 font-medium">Total Data</div>
                    <div class="text-xs text-orange-600 mt-1">Semua data sistem</div>
                </div>
            </div>

            <!-- Simple Chart Placeholder - Mobile Optimized -->
            <div class="mt-4 md:mt-6 p-3 md:p-4 bg-gray-50 rounded-lg">
                <h4 class="font-semibold text-gray-800 mb-3 text-sm md:text-base">Distribusi Data</h4>
                <div class="space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="text-xs md:text-sm text-gray-600">Absensi</span>
                        <div class="w-20 md:w-32 bg-gray-200 rounded-full h-2">
                            <div class="bg-blue-600 h-2 rounded-full"
                                style="width: {{ count($absensis) > 0 ? (count($absensis) / max($totalData, 1)) * 100 : 0 }}%">
                            </div>
                        </div>
                        <span class="text-xs md:text-sm font-medium text-gray-700">{{ count($absensis) }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-xs md:text-sm text-gray-600">Inspeksi</span>
                        <div class="w-20 md:w-32 bg-gray-200 rounded-full h-2">
                            <div class="bg-green-600 h-2 rounded-full"
                                style="width: {{ count($inspeksis) > 0 ? (count($inspeksis) / max($totalData, 1)) * 100 : 0 }}%">
                            </div>
                        </div>
                        <span class="text-xs md:text-sm font-medium text-gray-700">{{ count($inspeksis) }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-xs md:text-sm text-gray-600">Users</span>
                        <div class="w-20 md:w-32 bg-gray-200 rounded-full h-2">
                            <div class="bg-purple-600 h-2 rounded-full"
                                style="width: {{ count($users) > 0 ? (count($users) / max($totalData, 1)) * 100 : 0 }}%">
                            </div>
                        </div>
                        <span class="text-xs md:text-sm font-medium text-gray-700">{{ count($users) }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Section - Mobile Optimized -->
    <div class="bg-white rounded-lg md:rounded-xl shadow-md border border-gray-100 mb-6">
        <div class="px-4 md:px-6 py-3 md:py-4 border-b border-gray-200">
            <h3 class="text-base md:text-lg font-semibold text-gray-800 flex items-center">
                <i class="fas fa-filter mr-2 text-blue-600"></i>
                Filter Data
            </h3>
        </div>
        <div class="p-4 md:p-6">
            <form method="GET" action="{{ route('admin.laporan') }}">
                <div class="grid grid-cols-1 gap-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-calendar-start mr-1 text-gray-500"></i>
                                Tanggal Mulai
                            </label>
                            <input type="date" name="start_date" value="{{ request('start_date') }}"
                                class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-calendar-end mr-1 text-gray-500"></i>
                                Tanggal Akhir
                            </label>
                            <input type="date" name="end_date" value="{{ request('end_date') }}"
                                class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-lg transition duration-200 flex items-center justify-center text-sm">
                            <i class="fas fa-search mr-2"></i>
                            Terapkan
                        </button>
                        <a href="{{ route('admin.laporan') }}"
                            class="bg-gray-500 hover:bg-gray-600 text-white py-2 px-4 rounded-lg transition duration-200 flex items-center justify-center text-sm">
                            <i class="fas fa-redo mr-2"></i>
                            Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Export Section - Mobile Optimized -->
    <div class="bg-white rounded-lg md:rounded-xl shadow-md border border-gray-100 mb-6">
        <div class="px-4 md:px-6 py-3 md:py-4 border-b border-gray-200">
            <h3 class="text-base md:text-lg font-semibold text-gray-800 flex items-center">
                <i class="fas fa-file-export mr-2 text-green-600"></i>
                Export Data
            </h3>
        </div>
        <div class="p-4 md:p-6">
            <div class="grid grid-cols-1 gap-3">
                <form method="GET" action="{{ route('admin.export') }}" class="flex flex-col">
                    <input type="hidden" name="type" value="absensi">
                    <input type="hidden" name="start_date" value="{{ request('start_date') }}">
                    <input type="hidden" name="end_date" value="{{ request('end_date') }}">
                    <button type="submit"
                        class="bg-green-500 hover:bg-green-600 text-white py-3 px-4 rounded-lg transition duration-200 flex items-center space-x-3">
                        <i class="fas fa-clipboard-list text-lg"></i>
                        <div class="text-left flex-1">
                            <div class="font-semibold text-sm md:text-base">Export Absensi</div>
                            <div class="text-green-100 text-xs">{{ count($absensis) }} records</div>
                        </div>
                        <i class="fas fa-download ml-2"></i>
                    </button>
                </form>

                <form method="GET" action="{{ route('admin.export') }}" class="flex flex-col">
                    <input type="hidden" name="type" value="inspeksi">
                    <input type="hidden" name="start_date" value="{{ request('start_date') }}">
                    <input type="hidden" name="end_date" value="{{ request('end_date') }}">
                    <button type="submit"
                        class="bg-green-500 hover:bg-green-600 text-white py-3 px-4 rounded-lg transition duration-200 flex items-center space-x-3">
                        <i class="fas fa-chart-bar text-lg"></i>
                        <div class="text-left flex-1">
                            <div class="font-semibold text-sm md:text-base">Export Inspeksi</div>
                            <div class="text-green-100 text-xs">{{ count($inspeksis) }} records</div>
                        </div>
                        <i class="fas fa-download ml-2"></i>
                    </button>
                </form>

                <form method="GET" action="{{ route('admin.export') }}" class="flex flex-col">
                    <input type="hidden" name="type" value="users">
                    <input type="hidden" name="start_date" value="{{ request('start_date') }}">
                    <input type="hidden" name="end_date" value="{{ request('end_date') }}">
                    <button type="submit"
                        class="bg-green-500 hover:bg-green-600 text-white py-3 px-4 rounded-lg transition duration-200 flex items-center space-x-3">
                        <i class="fas fa-users text-lg"></i>
                        <div class="text-left flex-1">
                            <div class="font-semibold text-sm md:text-base">Export Users</div>
                            <div class="text-green-100 text-xs">{{ count($users) }} records</div>
                        </div>
                        <i class="fas fa-download ml-2"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Data Tabs - Mobile Optimized -->
    <div class="bg-white rounded-lg md:rounded-xl shadow-md border border-gray-100">
        <div class="border-b border-gray-200 overflow-x-auto">
            <nav class="flex space-x-4 md:space-x-8 px-4 min-w-max">
                <button id="tab-absensi"
                    class="tab-button whitespace-nowrap py-3 px-2 border-b-2 font-medium text-xs md:text-sm border-blue-500 text-blue-600 flex items-center">
                    <i class="fas fa-clipboard-list mr-2 text-xs"></i>
                    Absensi ({{ count($absensis) }})
                </button>
                <button id="tab-inspeksi"
                    class="tab-button whitespace-nowrap py-3 px-2 border-b-2 font-medium text-xs md:text-sm border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 flex items-center">
                    <i class="fas fa-chart-bar mr-2 text-xs"></i>
                    Inspeksi ({{ count($inspeksis) }})
                </button>
                <button id="tab-users"
                    class="tab-button whitespace-nowrap py-3 px-2 border-b-2 font-medium text-xs md:text-sm border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 flex items-center">
                    <i class="fas fa-users mr-2 text-xs"></i>
                    Users ({{ count($users) }})
                </button>
            </nav>
        </div>

        <!-- Tab Content -->
        <div class="p-4 md:p-6">
            <!-- Absensi Tab -->
            <div id="content-absensi" class="tab-content">
                @if (count($absensis) > 0)
                    <div class="overflow-x-auto rounded-lg border border-gray-200">
                        <table class="min-w-full divide-y divide-gray-200 text-sm">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        NIP
                                    </th>
                                    <th
                                        class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        NAMA
                                    </th>
                                    <th
                                        class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        MASUK
                                    </th>
                                    <th
                                        class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        PULANG
                                    </th>
                                    <th
                                        class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        STATUS
                                    </th>
                                    <th
                                        class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        AKSI
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($absensis as $absensi)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-3 py-2 whitespace-nowrap font-medium text-gray-900">
                                            <div class="text-xs">{{ $absensi->nip }}</div>
                                        </td>
                                        <td class="px-3 py-2 whitespace-nowrap text-gray-900">
                                            <div class="text-xs">{{ $absensi->nama }}</div>
                                        </td>
                                        <td class="px-3 py-2 whitespace-nowrap text-gray-900">
                                            <div class="text-xs">
                                                {{ $absensi->waktu_masuk ? $absensi->waktu_masuk->format('d/m H:i') : '-' }}
                                            </div>
                                        </td>
                                        <td class="px-3 py-2 whitespace-nowrap text-gray-900">
                                            <div class="text-xs">
                                                {{ $absensi->waktu_pulang ? $absensi->waktu_pulang->format('d/m H:i') : '-' }}
                                            </div>
                                        </td>
                                        <td class="px-3 py-2 whitespace-nowrap">
                                            @if ($absensi->waktu_pulang)
                                                <span
                                                    class="px-2 py-1 text-xs font-medium bg-green-100 text-green-800 rounded-full">Selesai</span>
                                            @else
                                                <span
                                                    class="px-2 py-1 text-xs font-medium bg-yellow-100 text-yellow-800 rounded-full">Masuk</span>
                                            @endif
                                        </td>
                                        <td class="px-3 py-2 whitespace-nowrap">
                                            <div class="flex space-x-2">
                                                <!-- Tombol Edit -->
                                                <button type="button"
                                                    class="edit-btn bg-blue-500 hover:bg-blue-600 text-white p-1 rounded text-xs transition duration-200 flex items-center"
                                                    data-id="{{ $absensi->id }}" data-nip="{{ $absensi->nip }}"
                                                    data-nama="{{ $absensi->nama }}"
                                                    data-jabatan="{{ $absensi->jabatan }}"
                                                    data-unit-kerja="{{ $absensi->unit_kerja }}"
                                                    data-provinsi="{{ $absensi->provinsi }}"
                                                    data-waktu-masuk="{{ $absensi->waktu_masuk ? $absensi->waktu_masuk->format('Y-m-d\TH:i') : '' }}"
                                                    data-waktu-pulang="{{ $absensi->waktu_pulang ? $absensi->waktu_pulang->format('Y-m-d\TH:i') : '' }}"
                                                    title="Edit">
                                                    <i class="fas fa-edit text-xs"></i>
                                                </button>

                                                <!-- Tombol Hapus -->
                                                <form action="{{ route('admin.absensi.destroy', $absensi->id) }}"
                                                    method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="bg-red-500 hover:bg-red-600 text-white p-1 rounded text-xs transition duration-200 flex items-center"
                                                        onclick="return confirm('Yakin ingin menghapus data absensi ini?')"
                                                        title="Hapus">
                                                        <i class="fas fa-trash text-xs"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-8">
                        <i class="fas fa-clipboard-list text-3xl text-gray-300 mb-3"></i>
                        <p class="text-gray-500 text-sm">Tidak ada data absensi</p>
                    </div>
                @endif
            </div>

            <!-- Inspeksi Tab -->
            <div id="content-inspeksi" class="tab-content hidden">
                @if (count($inspeksis) > 0)
                    <div class="overflow-x-auto rounded-lg border border-gray-200">
                        <table class="min-w-full divide-y divide-gray-200 text-sm">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Pengawas
                                    </th>
                                    <th
                                        class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Kategori
                                    </th>
                                    <th
                                        class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Tanggal
                                    </th>
                                    <th
                                        class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Pertanyaan
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($inspeksis as $inspeksi)
                                    @php
                                        // Tentukan nama kategori
                                        $kategoriName = 'Semua Kategori';
                                        $kategoriId = null;

                                        if ($inspeksi->kategori_id) {
                                            try {
                                                $kategori = \App\Models\KategoriInspeksi::find($inspeksi->kategori_id);
                                                if ($kategori) {
                                                    $kategoriName = $kategori->nama;
                                                    $kategoriId = $kategori->id;
                                                }
                                            } catch (Exception $e) {
                                            }
                                        }

                                        // Hitung jumlah pertanyaan
                                        $jumlahPertanyaan = 0;
                                        if ($kategoriId) {
                                            try {
                                                $jumlahPertanyaan = \App\Models\Pertanyaan::where(
                                                    'kategori_id',
                                                    $kategoriId,
                                                )->count();
                                            } catch (Exception $e) {
                                                $jumlahPertanyaan = 0;
                                            }
                                        } else {
                                            try {
                                                $jumlahPertanyaan = \App\Models\Pertanyaan::count();
                                            } catch (Exception $e) {
                                                $jumlahPertanyaan = 0;
                                            }
                                        }

                                        // Hitung jumlah jawaban
                                        $jumlahJawaban = 0;
                                        try {
                                            $jumlahJawaban = \App\Models\Jawaban::where(
                                                'inspeksi_id',
                                                $inspeksi->id,
                                            )->count();
                                        } catch (Exception $e) {
                                            $jumlahJawaban = 0;
                                        }

                                        // Get pengawas name
                                        $pengawasName = 'N/A';
                                        if ($inspeksi->pengawas) {
                                            $pengawasName =
                                                $inspeksi->pengawas->nama ?? ($inspeksi->pengawas->name ?? 'N/A');
                                        } elseif ($inspeksi->pengawas_id) {
                                            try {
                                                $pengawas = \App\Models\User::find($inspeksi->pengawas_id);
                                                $pengawasName = $pengawas->nama ?? ($pengawas->name ?? 'N/A');
                                            } catch (Exception $e) {
                                            }
                                        }
                                    @endphp
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-3 py-2 whitespace-nowrap font-medium text-gray-900">
                                            <div class="text-xs">{{ $pengawasName }}</div>
                                        </td>
                                        <td class="px-3 py-2 whitespace-nowrap text-gray-900">
                                            <span
                                                class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">
                                                {{ $kategoriName }}
                                            </span>
                                        </td>
                                        <td class="px-3 py-2 whitespace-nowrap text-gray-500">
                                            <div class="text-xs">
                                                {{ $inspeksi->created_at ? $inspeksi->created_at->format('d/m H:i') : 'N/A' }}
                                            </div>
                                        </td>
                                        <td class="px-3 py-2 whitespace-nowrap text-gray-900">
                                            <div class="flex flex-col space-y-1">
                                                <span
                                                    class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                                    {{ $jumlahPertanyaan }} tanya
                                                </span>
                                                <span
                                                    class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-purple-100 text-purple-800">
                                                    {{ $jumlahJawaban }} jawab
                                                </span>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-8">
                        <i class="fas fa-chart-bar text-3xl text-gray-300 mb-3"></i>
                        <p class="text-gray-500 text-sm">Tidak ada data inspeksi</p>
                    </div>
                @endif
            </div>

            <!-- Users Tab -->
            <div id="content-users" class="tab-content hidden">
                @if (count($users) > 0)
                    <div class="overflow-x-auto rounded-lg border border-gray-200">
                        <table class="min-w-full divide-y divide-gray-200 text-sm">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        NIP
                                    </th>
                                    <th
                                        class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        User Info
                                    </th>
                                    <th
                                        class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Role
                                    </th>
                                    <th
                                        class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Bergabung
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($users as $user)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-3 py-2 whitespace-nowrap font-medium text-gray-900">
                                            <div class="text-xs">{{ $user->nip ?? 'N/A' }}</div>
                                        </td>
                                        <td class="px-3 py-2 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-8 w-8">
                                                    <div
                                                        class="h-8 w-8 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white font-semibold text-xs">
                                                        {{ substr($user->nama ?? ($user->name ?? 'U'), 0, 1) }}
                                                    </div>
                                                </div>
                                                <div class="ml-3">
                                                    <div class="text-xs font-medium text-gray-900">
                                                        {{ $user->nama ?? ($user->name ?? 'N/A') }}
                                                    </div>
                                                    <div class="text-xs text-gray-500 truncate max-w-[120px]">
                                                        {{ $user->email ?? 'N/A' }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-3 py-2 whitespace-nowrap">
                                            @php
                                                $roleColors = [
                                                    'admin' => 'bg-red-100 text-red-800',
                                                    'pengawas' => 'bg-green-100 text-green-800',
                                                    'user' => 'bg-blue-100 text-blue-800',
                                                ];
                                                $roleColor =
                                                    $roleColors[$user->role ?? 'user'] ?? 'bg-gray-100 text-gray-800';
                                            @endphp
                                            <span class="px-2 py-1 text-xs font-medium rounded-full {{ $roleColor }}">
                                                <i
                                                    class="fas fa-{{ $user->role == 'admin' ? 'crown' : ($user->role == 'pengawas' ? 'user-check' : 'user') }} mr-1"></i>
                                                {{ $user->role ?? 'user' }}
                                            </span>
                                        </td>
                                        <td class="px-3 py-2 whitespace-nowrap text-gray-500">
                                            <div class="text-xs">
                                                {{ $user->created_at ? $user->created_at->format('d/m/Y') : 'N/A' }}</div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-8">
                        <i class="fas fa-users text-3xl text-gray-300 mb-3"></i>
                        <p class="text-gray-500 text-sm">Tidak ada data users</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
    <!-- Modal Edit Absensi -->
    <!-- Simple Modal tanpa Bootstrap -->
    <div id="simpleEditModal"
        style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center;">
        <div style="background: white; padding: 20px; border-radius: 8px; width: 90%; max-width: 500px;">
            <div style="display: flex; justify-content: between; align-items: center; margin-bottom: 15px;">
                <h3 style="margin: 0;">Edit Absensi</h3>
                <button onclick="hideSimpleModal()"
                    style="background: none; border: none; font-size: 20px; cursor: pointer;">×</button>
            </div>

            <form id="simpleEditForm" method="POST">
                @csrf
                @method('PUT')
                <div style="display: grid; gap: 10px;">
                    <div>
                        <label>NIP</label>
                        <input type="text" name="nip" id="simple_nip"
                            style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;" required>
                    </div>
                    <div>
                        <label>Nama</label>
                        <input type="text" name="nama" id="simple_nama"
                            style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;" required>
                    </div>
                    <div>
                        <label>Waktu Masuk</label>
                        <input type="datetime-local" name="waktu_masuk" id="simple_waktu_masuk"
                            style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;" required>
                    </div>
                    <div>
                        <label>Waktu Pulang</label>
                        <input type="datetime-local" name="waktu_pulang" id="simple_waktu_pulang"
                            style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                    </div>
                </div>

                <div style="display: flex; gap: 10px; margin-top: 15px;">
                    <button type="button" onclick="hideSimpleModal()"
                        style="padding: 8px 16px; background: #6c757d; color: white; border: none; border-radius: 4px; cursor: pointer;">Batal</button>
                    <button type="submit"
                        style="padding: 8px 16px; background: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer;">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function showSimpleModal() {
            document.getElementById('simpleEditModal').style.display = 'flex';
        }

        function hideSimpleModal() {
            document.getElementById('simpleEditModal').style.display = 'none';
        }

        // Update tombol edit untuk menggunakan modal sederhana
        document.addEventListener('DOMContentLoaded', function() {
            const editButtons = document.querySelectorAll('.edit-btn');

            editButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const id = this.getAttribute('data-id');
                    const nip = this.getAttribute('data-nip');
                    const nama = this.getAttribute('data-nama');
                    const waktuMasuk = this.getAttribute('data-waktu-masuk');
                    const waktuPulang = this.getAttribute('data-waktu-pulang');

                    // Isi form
                    document.getElementById('simple_nip').value = nip || '';
                    document.getElementById('simple_nama').value = nama || '';
                    document.getElementById('simple_waktu_masuk').value = waktuMasuk || '';
                    document.getElementById('simple_waktu_pulang').value = waktuPulang || '';

                    // Set action form
                    document.getElementById('simpleEditForm').action = `/admin/absensi/${id}`;

                    // Tampilkan modal
                    showSimpleModal();
                });
            });
        });
    </script>
@endsection

@section('scripts')
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Simple tab implementation
        function activateTab(tabName) {
            const contents = document.querySelectorAll('.tab-content');
            contents.forEach(content => {
                content.style.display = 'none';
            });

            const activeContent = document.getElementById('content-' + tabName);
            if (activeContent) {
                activeContent.style.display = 'block';
            }

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

        // Initialize when page loads
        document.addEventListener('DOMContentLoaded', function() {
            // Tab events
            document.getElementById('tab-absensi').addEventListener('click', () => activateTab('absensi'));
            document.getElementById('tab-inspeksi').addEventListener('click', () => activateTab('inspeksi'));
            document.getElementById('tab-users').addEventListener('click', () => activateTab('users'));

            // Activate first tab
            activateTab('absensi');

            // Edit button functionality - FIXED VERSION
            const editButtons = document.querySelectorAll('.edit-btn');

            editButtons.forEach(button => {
                button.addEventListener('click', function() {
                    console.log('Edit button clicked!');

                    const id = this.getAttribute('data-id');
                    const nip = this.getAttribute('data-nip');
                    const nama = this.getAttribute('data-nama');
                    const jabatan = this.getAttribute('data-jabatan');
                    const unitKerja = this.getAttribute('data-unit-kerja');
                    const provinsi = this.getAttribute('data-provinsi');
                    const waktuMasuk = this.getAttribute('data-waktu-masuk');
                    const waktuPulang = this.getAttribute('data-waktu-pulang');

                    console.log('Data:', {
                        id,
                        nip,
                        nama,
                        jabatan,
                        unitKerja,
                        provinsi,
                        waktuMasuk,
                        waktuPulang
                    });

                    // Fill form with data
                    document.getElementById('edit_nip').value = nip || '';
                    document.getElementById('edit_nama').value = nama || '';
                    document.getElementById('edit_jabatan').value = jabatan || '';
                    document.getElementById('edit_unit_kerja').value = unitKerja || '';
                    document.getElementById('edit_provinsi').value = provinsi || '';
                    document.getElementById('edit_waktu_masuk').value = waktuMasuk || '';
                    document.getElementById('edit_waktu_pulang').value = waktuPulang || '';

                    // Set form action
                    document.getElementById('editAbsensiForm').action = `/admin/absensi/${id}`;

                    // Show modal using Bootstrap
                    const modalElement = document.getElementById('editAbsensiModal');
                    const modal = new bootstrap.Modal(modalElement);
                    modal.show();
                });
            });
        });

        // Fallback if Bootstrap doesn't load
        function showEditModal() {
            const modal = document.getElementById('editAbsensiModal');
            modal.style.display = 'block';
            modal.classList.add('show');
        }

        function hideEditModal() {
            const modal = document.getElementById('editAbsensiModal');
            modal.style.display = 'none';
            modal.classList.remove('show');
        }
    </script>

    <!-- Simple CSS for modal if Bootstrap fails -->
    <style>
        .modal-backdrop {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1040;
        }

        .modal.show {
            display: block !important;
        }
    </style>
@endsection
