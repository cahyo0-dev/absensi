@extends('layouts.app')

@section('title', 'Laporan Inspeksi')

@section('styles')
    <style>
        .export-btn {
            transition: all 0.3s ease;
        }

        .export-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }

        .stat-card {
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-2px);
        }

        .table-row:hover {
            background-color: #f9fafb;
        }

        @media (max-width: 640px) {
            .export-preset-grid {
                grid-template-columns: 1fr !important;
            }

            .stat-grid {
                grid-template-columns: 1fr !important;
            }

            .table-actions {
                flex-direction: column;
                gap: 0.5rem;
            }
        }
    </style>
@endsection

@section('content')
    <div class="space-y-6">
        <!-- Header Info -->
        <div class="bg-white shadow rounded-lg p-4 sm:p-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                <div class="flex-1 min-w-0">
                    <h1 class="text-xl sm:text-2xl font-bold text-gray-900">
                        <i class="fas fa-file-alt mr-2 text-blue-500"></i>
                        Laporan Inspeksi
                    </h1>
                    <p class="text-gray-600 mt-1 text-sm sm:text-base">
                        Kelola dan ekspor data inspeksi yang telah dilakukan
                    </p>
                </div>
                <div class="mt-3 sm:mt-0 text-sm text-gray-500 bg-gray-50 px-3 py-2 rounded-lg whitespace-nowrap">
                    <i class="fas fa-calendar-alt mr-2"></i>
                    {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
                </div>
            </div>
        </div>

        <!-- Alert Messages -->
        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded flex items-center">
                <i class="fas fa-check-circle mr-2"></i>
                <span class="text-sm sm:text-base">{{ session('success') }}</span>
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded flex items-center">
                <i class="fas fa-exclamation-circle mr-2"></i>
                <span class="text-sm sm:text-base">{{ session('error') }}</span>
            </div>
        @endif

        <!-- Export Section -->
        <div class="bg-white rounded-lg shadow p-4 sm:p-6">
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-4">
                <div class="flex-1 min-w-0">
                    <h2 class="text-lg font-semibold text-gray-900">Export Data</h2>
                    <p class="text-gray-600 text-sm">Download laporan dalam format Excel</p>
                </div>
            </div>

            <!-- Export Preset Cepat -->
            <div class="mb-6">
                <h3 class="text-md font-medium text-gray-900 mb-3 flex items-center">
                    <i class="fas fa-bolt mr-2 text-yellow-500"></i>
                    Export Cepat
                </h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-3 export-preset-grid">
                    <a href="{{ route('pengawas.inspeksi.export.preset', 'week') }}"
                        class="export-btn inline-flex items-center justify-center px-3 sm:px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 transition duration-150 text-sm"
                        onclick="showExportLoading(this)">
                        <i class="fas fa-calendar-week mr-2"></i>
                        1 Minggu
                    </a>
                    <a href="{{ route('pengawas.inspeksi.export.preset', 'month') }}"
                        class="export-btn inline-flex items-center justify-center px-3 sm:px-4 py-2 bg-green-500 text-white rounded-md hover:bg-green-600 transition duration-150 text-sm"
                        onclick="showExportLoading(this)">
                        <i class="fas fa-calendar-alt mr-2"></i>
                        1 Bulan
                    </a>
                    <a href="{{ route('pengawas.inspeksi.export.preset', 'year') }}"
                        class="export-btn inline-flex items-center justify-center px-3 sm:px-4 py-2 bg-yellow-500 text-white rounded-md hover:bg-yellow-600 transition duration-150 text-sm"
                        onclick="showExportLoading(this)">
                        <i class="fas fa-calendar mr-2"></i>
                        1 Tahun
                    </a>
                    <a href="{{ route('pengawas.inspeksi.export.all') }}"
                        class="export-btn inline-flex items-center justify-center px-3 sm:px-4 py-2 bg-purple-500 text-white rounded-md hover:bg-purple-600 transition duration-150 text-sm"
                        onclick="showExportLoading(this)">
                        <i class="fas fa-database mr-2"></i>
                        Semua Data
                    </a>
                </div>
            </div>

            <!-- Export Rentang Waktu Kustom -->
            <div class="border-t pt-4">
                <h3 class="text-md font-medium text-gray-900 mb-3 flex items-center">
                    <i class="fas fa-calendar-day mr-2 text-red-500"></i>
                    Export Rentang Waktu Kustom
                </h3>
                <form id="exportRangeForm" class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
                    @csrf
                    <div>
                        <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1">
                            <i class="fas fa-calendar-plus mr-1"></i>Tanggal Mulai
                        </label>
                        <input type="date" id="start_date" name="start_date"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm"
                            required>
                    </div>
                    <div>
                        <label for="end_date" class="block text-sm font-medium text-gray-700 mb-1">
                            <i class="fas fa-calendar-minus mr-1"></i>Tanggal Akhir
                        </label>
                        <input type="date" id="end_date" name="end_date"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm"
                            required>
                    </div>
                    <div>
                        <button type="button" onclick="exportCustomRange()"
                            class="w-full export-btn inline-flex items-center justify-center px-4 py-2 bg-red-500 text-white rounded-md hover:bg-red-600 transition duration-150 text-sm">
                            <i class="fas fa-file-excel mr-2"></i>
                            Export Rentang
                        </button>
                    </div>
                </form>
                <div id="exportError" class="text-red-500 text-sm mt-2 hidden"></div>
            </div>
        </div>

        <!-- Statistik Ringkas -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 stat-grid">
            <div class="stat-card bg-white rounded-lg shadow p-4 sm:p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-blue-500 rounded-lg">
                        <i class="fas fa-clipboard-list text-white text-lg sm:text-xl"></i>
                    </div>
                    <div class="ml-3 sm:ml-4">
                        <p class="text-sm font-medium text-gray-600">Total Inspeksi</p>
                        <p class="text-xl sm:text-2xl font-semibold text-gray-900">{{ $inspeksis->total() }}</p>
                    </div>
                </div>
            </div>

            <div class="stat-card bg-white rounded-lg shadow p-4 sm:p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-green-500 rounded-lg">
                        <i class="fas fa-check-circle text-white text-lg sm:text-xl"></i>
                    </div>
                    <div class="ml-3 sm:ml-4">
                        <p class="text-sm font-medium text-gray-600">Inspeksi Bulan Ini</p>
                        <p class="text-xl sm:text-2xl font-semibold text-gray-900">{{ $totalBulanIni }}</p>
                    </div>
                </div>
            </div>

            <div class="stat-card bg-white rounded-lg shadow p-4 sm:p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-yellow-500 rounded-lg">
                        <i class="fas fa-tasks text-white text-lg sm:text-xl"></i>
                    </div>
                    <div class="ml-3 sm:ml-4">
                        <p class="text-sm font-medium text-gray-600">Kategori Berbeda</p>
                        <p class="text-xl sm:text-2xl font-semibold text-gray-900">{{ $kategoriBerbeda }}</p>
                    </div>
                </div>
            </div>

            <div class="stat-card bg-white rounded-lg shadow p-4 sm:p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-purple-500 rounded-lg">
                        <i class="fas fa-chart-line text-white text-lg sm:text-xl"></i>
                    </div>
                    <div class="ml-3 sm:ml-4">
                        <p class="text-sm font-medium text-gray-600">Rata-rata per Hari</p>
                        <p class="text-xl sm:text-2xl font-semibold text-gray-900">{{ $rataRataPerHari }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabel Laporan -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-4 sm:px-6 py-4 border-b border-gray-200 bg-gray-50">
                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center space-y-2 sm:space-y-0">
                    <h2 class="text-lg font-semibold text-gray-800">Daftar Inspeksi yang Telah Dilakukan</h2>
                    <div class="flex items-center space-x-2 text-sm text-gray-600">
                        <i class="fas fa-info-circle"></i>
                        <span>Total: {{ $inspeksis->total() }} inspeksi</span>
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto">
                @if ($inspeksis->count() > 0)
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th
                                    class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <i class="fas fa-calendar mr-1"></i>
                                    Tanggal
                                </th>
                                <th
                                    class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <i class="fas fa-list mr-1"></i>
                                    Kategori
                                </th>
                                <th
                                    class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <i class="fas fa-question-circle mr-1"></i>
                                    Jumlah Pertanyaan
                                </th>
                                <th
                                    class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <i class="fas fa-signature mr-1"></i>
                                    Tanda Tangan
                                </th>
                                <th
                                    class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <i class="fas fa-cog mr-1"></i>
                                    Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($inspeksis as $inspeksi)
                                <tr class="table-row transition duration-150">
                                    <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $inspeksi->tanggal->timezone('Asia/Kuala_Lumpur')->format('d/m/Y') }}
                                        </div>
                                        <div class="text-xs text-gray-500">
                                            {{ $inspeksi->created_at->timezone('Asia/Kuala_Lumpur')->format('H:i') }}
                                        </div>
                                    </td>
                                    <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                                        @if ($inspeksi->kategori_id)
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                {{ $inspeksi->kategori->nama ?? 'Kategori Tidak Ditemukan' }}
                                            </span>
                                        @else
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                <i class="fas fa-layer-group mr-1"></i>
                                                Semua Kategori
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <div class="flex items-center">
                                            <i class="fas fa-list-ol mr-2 text-gray-400"></i>
                                            {{ $inspeksi->jawaban->count() }} pertanyaan
                                        </div>
                                    </td>
                                    <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                                        @if ($inspeksi->tanda_tangan)
                                            <img src="{{ $inspeksi->tanda_tangan }}" alt="Tanda Tangan"
                                                class="h-8 sm:h-10 w-24 sm:w-32 object-contain border rounded cursor-pointer"
                                                onclick="showSignature('{{ $inspeksi->tanda_tangan }}')">
                                        @else
                                            <span class="text-gray-400 text-xs sm:text-sm">Tidak ada</span>
                                        @endif
                                    </td>
                                    <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm font-medium table-actions">
                                        <button onclick="showDetail({{ $inspeksi->id }})"
                                            class="text-blue-600 hover:text-blue-900 mr-2 sm:mr-3 flex items-center transition duration-150 text-xs sm:text-sm">
                                            <i class="fas fa-eye mr-1"></i>
                                            Detail
                                        </button>
                                        <button onclick="exportInspeksi({{ $inspeksi->id }}, this)"
                                            class="text-green-600 hover:text-green-900 flex items-center transition duration-150 text-xs sm:text-sm">
                                            <i class="fas fa-download mr-1"></i>
                                            Export
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="text-center py-8 sm:py-12">
                        <i class="fas fa-clipboard-list text-gray-300 text-4xl sm:text-6xl mb-4"></i>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada laporan inspeksi</h3>
                        <p class="text-gray-500 mb-4 text-sm sm:text-base">Anda belum melakukan inspeksi apapun.</p>
                        <a href="{{ route('pengawas.inspeksi') }}"
                            class="inline-flex items-center px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 transition duration-150 text-sm sm:text-base">
                            <i class="fas fa-plus mr-2"></i>
                            Lakukan Inspeksi Pertama
                        </a>
                    </div>
                @endif
            </div>

            <!-- Pagination -->
            @if ($inspeksis->hasPages())
                <div class="px-4 sm:px-6 py-4 border-t border-gray-200 bg-gray-50">
                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center space-y-2 sm:space-y-0">
                        <div class="text-sm text-gray-700">
                            Menampilkan {{ $inspeksis->firstItem() }} - {{ $inspeksis->lastItem() }} dari
                            {{ $inspeksis->total() }} hasil
                        </div>
                        <div class="flex space-x-2">
                            <!-- Previous Page Link -->
                            @if ($inspeksis->onFirstPage())
                                <span class="px-3 py-1 bg-gray-200 text-gray-500 rounded-md cursor-not-allowed text-sm">
                                    <i class="fas fa-chevron-left mr-1"></i> Sebelumnya
                                </span>
                            @else
                                <a href="{{ $inspeksis->previousPageUrl() }}"
                                    class="px-3 py-1 bg-white border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 transition duration-150 text-sm">
                                    <i class="fas fa-chevron-left mr-1"></i> Sebelumnya
                                </a>
                            @endif

                            <!-- Next Page Link -->
                            @if ($inspeksis->hasMorePages())
                                <a href="{{ $inspeksis->nextPageUrl() }}"
                                    class="px-3 py-1 bg-white border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 transition duration-150 text-sm">
                                    Selanjutnya <i class="fas fa-chevron-right ml-1"></i>
                                </a>
                            @else
                                <span class="px-3 py-1 bg-gray-200 text-gray-500 rounded-md cursor-not-allowed text-sm">
                                    Selanjutnya <i class="fas fa-chevron-right ml-1"></i>
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Modal Detail -->
    <div id="detailModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div
            class="relative top-4 sm:top-20 mx-auto p-4 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white max-h-[90vh] sm:max-h-[80vh] overflow-y-auto">
            <div class="mt-3">
                <div class="flex justify-between items-center mb-4 pb-2 border-b">
                    <h3 class="text-lg font-medium text-gray-900">
                        <i class="fas fa-info-circle mr-2 text-blue-500"></i>
                        Detail Inspeksi
                    </h3>
                    <button onclick="closeDetail()" class="text-gray-400 hover:text-gray-600 transition duration-150">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                <div id="modalContent">
                    <!-- Content akan diisi oleh JavaScript -->
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Tanda Tangan -->
    <div id="signatureModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-4 sm:top-20 mx-auto p-4 border w-11/12 md:w-1/2 lg:w-1/3 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-medium text-gray-900">
                        <i class="fas fa-signature mr-2 text-blue-500"></i>
                        Tanda Tangan
                    </h3>
                    <button onclick="closeSignature()" class="text-gray-400 hover:text-gray-600 transition duration-150">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                <div id="signatureContent" class="flex justify-center">
                    <!-- Content akan diisi oleh JavaScript -->
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Konfirmasi Hapus -->
    <div id="deleteConfirmModal"
        class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-4 sm:top-20 mx-auto p-4 border w-11/12 md:w-1/2 lg:w-1/3 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-medium text-gray-900">
                        <i class="fas fa-exclamation-triangle mr-2 text-yellow-500"></i>
                        Konfirmasi Hapus
                    </h3>
                    <button onclick="closeDeleteConfirm()"
                        class="text-gray-400 hover:text-gray-600 transition duration-150">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                <div class="text-center py-4">
                    <i class="fas fa-trash-alt text-3xl sm:text-4xl text-red-500 mb-4"></i>
                    <p class="text-gray-700 mb-4 text-sm sm:text-base">Apakah Anda yakin ingin menghapus hasil inspeksi
                        ini?</p>
                    <p class="text-xs sm:text-sm text-gray-500 mb-6">Tindakan ini tidak dapat dibatalkan.</p>
                    <div class="flex justify-center space-x-3">
                        <button onclick="closeDeleteConfirm()"
                            class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600 transition duration-150 text-sm">
                            Batal
                        </button>
                        <button id="confirmDeleteBtn"
                            class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600 transition duration-150 text-sm">
                            Ya, Hapus
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        let currentInspeksiId = null;

        function showDetail(inspeksiId) {
            console.log('Show detail untuk inspeksi ID:', inspeksiId);

            // Show loading
            document.getElementById('modalContent').innerHTML = `
            <div class="flex justify-center items-center py-8">
                <i class="fas fa-spinner fa-spin text-blue-500 text-2xl mr-3"></i>
                <span class="text-gray-600">Memuat detail inspeksi...</span>
            </div>
        `;
            document.getElementById('detailModal').classList.remove('hidden');

            // PERBAIKAN: Gunakan route yang benar dengan base URL
            const url = `/pengawas/inspeksi-detail/${inspeksiId}`;
            console.log('Fetch URL:', url);

            fetch(url, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => {
                    console.log('Response status:', response.status);
                    if (!response.ok) {
                        // Jika response bukan 2xx, throw error dengan status
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Data received:', data);

                    // Cek jika ada error dari server
                    if (data.error) {
                        throw new Error(data.error);
                    }

                    const tanggal = data.created_at ? new Date(data.created_at) : new Date();
                    const kualaLumpurDate = new Date(tanggal.toLocaleString("en-US", {
                        timeZone: "Asia/Kuala_Lumpur"
                    }));

                    let html = `
                <div class="mb-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <p class="text-sm text-gray-500"><i class="fas fa-calendar mr-2"></i>Tanggal</p>
                        <p class="font-medium">${kualaLumpurDate.toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' })}</p>
                        <p class="text-sm text-gray-500">${kualaLumpurDate.toLocaleTimeString('id-ID')}</p>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <p class="text-sm text-gray-500"><i class="fas fa-list mr-2"></i>Kategori</p>
                        <p class="font-medium">${data.kategori ? data.kategori.nama : 'Semua Kategori'}</p>
                        <p class="text-sm text-gray-500">${data.kategori && data.kategori.deskripsi ? data.kategori.deskripsi : 'Tidak ada deskripsi'}</p>
                    </div>
                </div>

                <div class="border-t pt-4 mb-4">
                    <h4 class="font-semibold mb-3 text-gray-800 flex items-center">
                        <i class="fas fa-tasks mr-2 text-blue-500"></i>
                        Hasil Jawaban (${data.jawabans ? data.jawabans.length : 0} pertanyaan)
                    </h4>
                    <div class="space-y-3 max-h-60 overflow-y-auto">
            `;

                    if (data.jawabans && data.jawabans.length > 0) {
                        data.jawabans.forEach(jawaban => {
                            const isYa = jawaban.jawaban === 'Ya';
                            const isTidak = jawaban.jawaban === 'Tidak';
                            let bgColor = 'bg-gray-100';
                            let textColor = 'text-gray-800';
                            let borderColor = 'border-gray-200';
                            let icon = 'fa-question';

                            if (isYa) {
                                bgColor = 'bg-green-100';
                                textColor = 'text-green-800';
                                borderColor = 'border-green-200';
                                icon = 'fa-check';
                            } else if (isTidak) {
                                bgColor = 'bg-red-100';
                                textColor = 'text-red-800';
                                borderColor = 'border-red-200';
                                icon = 'fa-times';
                            }

                            html += `
                        <div class="flex justify-between items-center p-3 ${bgColor} rounded-lg border ${borderColor}">
                            <span class="text-sm flex-1 ${textColor}">${jawaban.pertanyaan ? jawaban.pertanyaan.pertanyaan : 'Pertanyaan tidak ditemukan'}</span>
                            <span class="px-3 py-1 rounded-full text-xs font-medium ${bgColor} ${textColor} border ${borderColor} ml-3 whitespace-nowrap">
                                <i class="fas ${icon} mr-1"></i>
                                ${jawaban.jawaban}
                            </span>
                        </div>
                    `;
                        });
                    } else {
                        html += `
                    <div class="text-center py-4 text-gray-500">
                        <i class="fas fa-exclamation-circle text-xl mb-2"></i>
                        <p>Tidak ada data jawaban</p>
                    </div>
                `;
                    }

                    html += `
                    </div>
                </div>
                <div class="border-t pt-4 mt-4">
                    <h4 class="font-semibold mb-2 text-gray-800 flex items-center">
                        <i class="fas fa-signature mr-2 text-blue-500"></i>
                        Tanda Tangan Pengawas
                    </h4>
                    ${data.tanda_tangan ? 
                        `<img src="${data.tanda_tangan}" alt="Tanda Tangan" class="h-20 sm:h-24 border rounded-lg mx-auto cursor-pointer" onclick="showSignature('${data.tanda_tangan}')">` : 
                        '<p class="text-gray-500 text-center py-4">Tidak ada tanda tangan</p>'
                    }
                </div>

                <!-- Tombol Aksi -->
                <div class="border-t pt-6 mt-6 flex flex-col sm:flex-row justify-end space-y-3 sm:space-y-0 sm:space-x-3">
                    <button onclick="editJawaban(${data.id})" class="flex items-center justify-center px-4 py-2 bg-yellow-500 text-white rounded-md hover:bg-yellow-600 transition duration-150 text-sm">
                        <i class="fas fa-edit mr-2"></i>
                        Edit Jawaban
                    </button>
                    <button onclick="confirmDelete(${data.id})" class="flex items-center justify-center px-4 py-2 bg-red-500 text-white rounded-md hover:bg-red-600 transition duration-150 text-sm">
                        <i class="fas fa-trash mr-2"></i>
                        Hapus Inspeksi
                    </button>
                </div>
            `;

                    document.getElementById('modalContent').innerHTML = html;
                })
                .catch(error => {
                    console.error('Error:', error);
                    document.getElementById('modalContent').innerHTML = `
                <div class="text-center py-8 text-red-500">
                    <i class="fas fa-exclamation-triangle text-3xl mb-3"></i>
                    <p>Terjadi kesalahan saat memuat data.</p>
                    <p class="text-sm text-gray-500 mt-2">${error.message}</p>
                    <p class="text-xs text-gray-400 mt-1">Pastikan Anda terhubung ke internet dan coba lagi.</p>
                    <button onclick="closeDetail()" class="mt-4 px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                        Tutup
                    </button>
                </div>
            `;
                });
        }

        function closeDetail() {
            document.getElementById('detailModal').classList.add('hidden');
        }

        function showSignature(signatureData) {
            document.getElementById('signatureContent').innerHTML = `
            <img src="${signatureData}" alt="Tanda Tangan" class="max-w-full max-h-64 sm:max-h-96 border rounded-lg">
        `;
            document.getElementById('signatureModal').classList.remove('hidden');
        }

        function closeSignature() {
            document.getElementById('signatureModal').classList.add('hidden');
        }

        function editJawaban(inspeksiId) {
            window.location.href = `/pengawas/inspeksi/${inspeksiId}/edit`;
        }

        function confirmDelete(inspeksiId) {
            currentInspeksiId = inspeksiId;
            document.getElementById('deleteConfirmModal').classList.remove('hidden');
            document.getElementById('confirmDeleteBtn').onclick = function() {
                deleteInspeksi(currentInspeksiId);
            };
        }

        function closeDeleteConfirm() {
            document.getElementById('deleteConfirmModal').classList.add('hidden');
            currentInspeksiId = null;
        }

        function deleteInspeksi(inspeksiId) {
            const deleteBtn = document.getElementById('confirmDeleteBtn');
            const originalText = deleteBtn.innerHTML;
            deleteBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Menghapus...';
            deleteBtn.disabled = true;

            fetch(`/pengawas/inspeksi/${inspeksiId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        showTempMessage(data.message, 'success');
                        closeDeleteConfirm();
                        closeDetail();
                        setTimeout(() => {
                            window.location.reload();
                        }, 1500);
                    } else {
                        throw new Error(data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showTempMessage('Terjadi kesalahan saat menghapus inspeksi.', 'error');
                    deleteBtn.innerHTML = originalText;
                    deleteBtn.disabled = false;
                });
        }

        function exportInspeksi(inspeksiId, button) {
            const originalText = button.innerHTML;
            button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Memproses...';
            button.disabled = true;

            const link = document.createElement('a');
            link.href = `/pengawas/inspeksi/${inspeksiId}/export`;
            link.target = '_blank';
            link.download = `inspeksi-${inspeksiId}.xlsx`;

            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);

            setTimeout(() => {
                button.innerHTML = originalText;
                button.disabled = false;
            }, 2000);
        }

        function showExportLoading(button) {
            const originalText = button.innerHTML;
            button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Memproses...';
            button.disabled = true;

            // Reset button setelah 3 detik (fallback)
            setTimeout(() => {
                button.innerHTML = originalText;
                button.disabled = false;
            }, 3000);
        }

        // Untuk export custom range
        function exportCustomRange() {
            const startDate = document.getElementById('start_date').value;
            const endDate = document.getElementById('end_date').value;
            const errorDiv = document.getElementById('exportError');
            const form = document.getElementById('exportRangeForm');

            if (!startDate || !endDate) {
                errorDiv.textContent = 'Harap pilih tanggal mulai dan tanggal akhir.';
                errorDiv.classList.remove('hidden');
                return;
            }

            if (new Date(startDate) > new Date(endDate)) {
                errorDiv.textContent = 'Tanggal mulai tidak boleh lebih besar dari tanggal akhir.';
                errorDiv.classList.remove('hidden');
                return;
            }

            errorDiv.classList.add('hidden');

            // Submit form secara programmatic
            const formData = new FormData(form);

            fetch('{{ route('pengawas.inspeksi.export.range') }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => {
                    if (response.ok) {
                        return response.blob();
                    }
                    throw new Error('Network response was not ok');
                })
                .then(blob => {
                    // Create download link
                    const url = window.URL.createObjectURL(blob);
                    const a = document.createElement('a');
                    a.style.display = 'none';
                    a.href = url;
                    a.download = `inspeksi_${startDate}_hingga_${endDate}.xlsx`;
                    document.body.appendChild(a);
                    a.click();
                    window.URL.revokeObjectURL(url);
                    document.body.removeChild(a);
                })
                .catch(error => {
                    console.error('Error:', error);
                    errorDiv.textContent = 'Terjadi kesalahan saat mengekspor data.';
                    errorDiv.classList.remove('hidden');
                });
        }

        function showTempMessage(message, type) {
            const messageDiv = document.createElement('div');
            messageDiv.className = `fixed top-4 right-4 p-3 sm:p-4 rounded-md shadow-lg z-50 ${
            type === 'success' ? 'bg-green-500 text-white' : 'bg-red-500 text-white'
        } text-sm sm:text-base`;
            messageDiv.innerHTML = `
            <div class="flex items-center">
                <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'} mr-2"></i>
                <span>${message}</span>
            </div>
        `;

            document.body.appendChild(messageDiv);

            setTimeout(() => {
                if (document.body.contains(messageDiv)) {
                    document.body.removeChild(messageDiv);
                }
            }, 3000);
        }

        // Close modals when clicking outside
        document.addEventListener('click', function(event) {
            const detailModal = document.getElementById('detailModal');
            const signatureModal = document.getElementById('signatureModal');
            const deleteModal = document.getElementById('deleteConfirmModal');

            if (event.target === detailModal) closeDetail();
            if (event.target === signatureModal) closeSignature();
            if (event.target === deleteModal) closeDeleteConfirm();
        });

        // Handle escape key to close modals
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeDetail();
                closeSignature();
                closeDeleteConfirm();
            }
        });
    </script>
@endsection
