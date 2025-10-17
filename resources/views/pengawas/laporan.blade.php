<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Inspeksi - Pengawas</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body class="bg-gray-100">
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <div
            class="fixed inset-y-0 left-0 w-64 bg-blue-800 transform transition-transform duration-200 ease-in-out -translate-x-full md:translate-x-0">
            <div class="flex items-center justify-center h-16 bg-blue-900">
                <span class="text-white text-lg font-semibold">Dashboard Pengawas</span>
            </div>
            <nav class="mt-5">
                <a href="{{ route('pengawas.dashboard') }}"
                    class="flex items-center py-2.5 px-4 rounded transition duration-200 hover:bg-blue-700 text-white">
                    <i class="fas fa-tachometer-alt mr-3"></i>
                    Dashboard
                </a>
                <a href="{{ route('pengawas.inspeksi') }}"
                    class="flex items-center py-2.5 px-4 rounded transition duration-200 hover:bg-blue-700 text-white">
                    <i class="fas fa-clipboard-check mr-3"></i>
                    Inspeksi
                </a>
                <a href="{{ route('pengawas.laporan') }}"
                    class="flex items-center py-2.5 px-4 rounded transition duration-200 hover:bg-blue-700 text-white bg-blue-700">
                    <i class="fas fa-file-alt mr-3"></i>
                    Laporan
                </a>
                <form method="POST" action="{{ route('logout') }}"
                    class="flex items-center py-2.5 px-4 rounded transition duration-200 hover:bg-blue-700 text-white">
                    @csrf
                    <i class="fas fa-sign-out-alt mr-3"></i>
                    <button type="submit">Logout</button>
                </form>
            </nav>
        </div>

        <!-- Main content -->
        <div class="flex-1 md:ml-64">
            <!-- Header -->
            <div class="bg-white shadow">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between items-center py-4">
                        <h1 class="text-2xl font-bold text-gray-900">Laporan Inspeksi</h1>
                        <div class="flex items-center space-x-4">
                            <span class="text-sm text-gray-500">
                                <i class="fas fa-user mr-1"></i>
                                {{ Auth::user()->name }}
                            </span>
                            <span class="text-sm text-gray-500">
                                <i class="fas fa-calendar mr-1"></i>
                                {{ date('d/m/Y') }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="p-6">
                @if (session('success'))
                    <div
                        class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6 flex items-center">
                        <i class="fas fa-check-circle mr-2"></i>
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6 flex items-center">
                        <i class="fas fa-exclamation-circle mr-2"></i>
                        {{ session('error') }}
                    </div>
                @endif

                <!-- Statistik Ringkas -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex items-center">
                            <div class="p-3 bg-blue-500 rounded-lg">
                                <i class="fas fa-clipboard-list text-white text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600">Total Inspeksi</p>
                                <p class="text-2xl font-semibold text-gray-900">{{ $inspeksis->count() }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex items-center">
                            <div class="p-3 bg-green-500 rounded-lg">
                                <i class="fas fa-check-circle text-white text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600">Inspeksi Bulan Ini</p>
                                <p class="text-2xl font-semibold text-gray-900">
                                    {{ $inspeksis->where('created_at', '>=', now()->startOfMonth())->count() }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex items-center">
                            <div class="p-3 bg-yellow-500 rounded-lg">
                                <i class="fas fa-tasks text-white text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600">Kategori Berbeda</p>
                                <p class="text-2xl font-semibold text-gray-900">
                                    {{ $inspeksis->pluck('kategori_id')->unique()->count() }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex items-center">
                            <div class="p-3 bg-purple-500 rounded-lg">
                                <i class="fas fa-chart-line text-white text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600">Rata-rata per Hari</p>
                                <p class="text-2xl font-semibold text-gray-900">
                                    @php
                                        $days = $inspeksis
                                            ->groupBy(function ($date) {
                                                return \Carbon\Carbon::parse($date->created_at)->format('Y-m-d');
                                            })
                                            ->count();
                                        $avg = $days > 0 ? round($inspeksis->count() / $days, 1) : 0;
                                    @endphp
                                    {{ $avg }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tabel Laporan -->
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                        <div class="flex justify-between items-center">
                            <h2 class="text-lg font-semibold text-gray-800">Daftar Inspeksi yang Telah Dilakukan</h2>
                            <div class="flex items-center space-x-2 text-sm text-gray-600">
                                <i class="fas fa-info-circle"></i>
                                <span>Total: {{ $inspeksis->count() }} inspeksi</span>
                            </div>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        @if ($inspeksis->count() > 0)
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            <i class="fas fa-calendar mr-1"></i>
                                            Tanggal
                                        </th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            <i class="fas fa-list mr-1"></i>
                                            Kategori
                                        </th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            <i class="fas fa-question-circle mr-1"></i>
                                            Jumlah Pertanyaan
                                        </th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            <i class="fas fa-signature mr-1"></i>
                                            Tanda Tangan
                                        </th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            <i class="fas fa-cog mr-1"></i>
                                            Aksi
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($inspeksis as $inspeksi)
                                        <tr class="hover:bg-gray-50 transition duration-150">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ $inspeksi->created_at->format('d/m/Y') }}
                                                </div>
                                                <div class="text-sm text-gray-500">
                                                    {{ $inspeksi->created_at->format('H:i') }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                    {{ $inspeksi->kategori->nama }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                <div class="flex items-center">
                                                    <i class="fas fa-list-ol mr-2 text-gray-400"></i>
                                                    {{ $inspeksi->jawabans->count() }} pertanyaan
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if ($inspeksi->tanda_tangan)
                                                    <img src="{{ $inspeksi->tanda_tangan }}" alt="Tanda Tangan"
                                                        class="h-10 w-32 object-contain border rounded cursor-pointer"
                                                        onclick="showSignature('{{ $inspeksi->tanda_tangan }}')">
                                                @else
                                                    <span class="text-gray-400 text-sm">Tidak ada</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <button onclick="showDetail({{ $inspeksi->id }})"
                                                    class="text-blue-600 hover:text-blue-900 mr-3 flex items-center transition duration-150">
                                                    <i class="fas fa-eye mr-1"></i>
                                                    Detail
                                                </button>
                                                <button onclick="exportInspeksi({{ $inspeksi->id }})"
                                                    class="text-green-600 hover:text-green-900 flex items-center transition duration-150">
                                                    <i class="fas fa-download mr-1"></i>
                                                    Export
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <div class="text-center py-12">
                                <i class="fas fa-clipboard-list text-gray-300 text-6xl mb-4"></i>
                                <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada laporan inspeksi</h3>
                                <p class="text-gray-500 mb-4">Anda belum melakukan inspeksi apapun.</p>
                                <a href="{{ route('pengawas.inspeksi') }}"
                                    class="inline-flex items-center px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 transition duration-150">
                                    <i class="fas fa-plus mr-2"></i>
                                    Lakukan Inspeksi Pertama
                                </a>
                            </div>
                        @endif
                    </div>

                    <!-- Pagination (jika diperlukan) -->
                    <!-- Pagination -->
                    @if ($inspeksis->hasPages())
                        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                            <div class="flex justify-between items-center">
                                <div class="text-sm text-gray-700">
                                    Menampilkan {{ $inspeksis->firstItem() }} - {{ $inspeksis->lastItem() }} dari
                                    {{ $inspeksis->total() }} hasil
                                </div>
                                <div class="flex space-x-2">
                                    <!-- Previous Page Link -->
                                    @if ($inspeksis->onFirstPage())
                                        <span
                                            class="px-3 py-1 bg-gray-200 text-gray-500 rounded-md cursor-not-allowed">
                                            <i class="fas fa-chevron-left mr-1"></i> Sebelumnya
                                        </span>
                                    @else
                                        <a href="{{ $inspeksis->previousPageUrl() }}"
                                            class="px-3 py-1 bg-white border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 transition duration-150">
                                            <i class="fas fa-chevron-left mr-1"></i> Sebelumnya
                                        </a>
                                    @endif

                                    <!-- Next Page Link -->
                                    @if ($inspeksis->hasMorePages())
                                        <a href="{{ $inspeksis->nextPageUrl() }}"
                                            class="px-3 py-1 bg-white border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 transition duration-150">
                                            Selanjutnya <i class="fas fa-chevron-right ml-1"></i>
                                        </a>
                                    @else
                                        <span
                                            class="px-3 py-1 bg-gray-200 text-gray-500 rounded-md cursor-not-allowed">
                                            Selanjutnya <i class="fas fa-chevron-right ml-1"></i>
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Detail -->
    <div id="detailModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div
            class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white max-h-[80vh] overflow-y-auto">
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
    <div id="signatureModal"
        class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-1/2 lg:w-1/3 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-medium text-gray-900">
                        <i class="fas fa-signature mr-2 text-blue-500"></i>
                        Tanda Tangan
                    </h3>
                    <button onclick="closeSignature()"
                        class="text-gray-400 hover:text-gray-600 transition duration-150">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                <div id="signatureContent" class="flex justify-center">
                    <!-- Content akan diisi oleh JavaScript -->
                </div>
            </div>
        </div>
    </div>

    <script>
        function showDetail(inspeksiId) {
            // Show loading
            document.getElementById('modalContent').innerHTML = `
                <div class="flex justify-center items-center py-8">
                    <i class="fas fa-spinner fa-spin text-blue-500 text-2xl mr-3"></i>
                    <span class="text-gray-600">Memuat detail inspeksi...</span>
                </div>
            `;
            document.getElementById('detailModal').classList.remove('hidden');

            fetch(`/api/inspeksi/${inspeksiId}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    let html = `
                        <div class="mb-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <p class="text-sm text-gray-500"><i class="fas fa-calendar mr-2"></i>Tanggal</p>
                                <p class="font-medium">${new Date(data.created_at).toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' })}</p>
                                <p class="text-sm text-gray-500">${new Date(data.created_at).toLocaleTimeString('id-ID')}</p>
                            </div>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <p class="text-sm text-gray-500"><i class="fas fa-list mr-2"></i>Kategori</p>
                                <p class="font-medium">${data.kategori.nama}</p>
                                <p class="text-sm text-gray-500">${data.kategori.deskripsi || 'Tidak ada deskripsi'}</p>
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
                            html += `
                                <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg border">
                                    <span class="text-sm flex-1">${jawaban.pertanyaan.pertanyaan}</span>
                                    <span class="px-3 py-1 rounded-full text-xs font-medium ${isYa ? 'bg-green-100 text-green-800 border border-green-200' : 'bg-red-100 text-red-800 border border-red-200'} ml-3 whitespace-nowrap">
                                        <i class="fas ${isYa ? 'fa-check' : 'fa-times'} mr-1"></i>
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
                                `<img src="${data.tanda_tangan}" alt="Tanda Tangan" class="h-24 border rounded-lg mx-auto cursor-pointer" onclick="showSignature('${data.tanda_tangan}')">` : 
                                '<p class="text-gray-500 text-center py-4">Tidak ada tanda tangan</p>'
                            }
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
                        </div>
                    `;
                });
        }

        function closeDetail() {
            document.getElementById('detailModal').classList.add('hidden');
        }

        function showSignature(signatureData) {
            document.getElementById('signatureContent').innerHTML = `
                <img src="${signatureData}" alt="Tanda Tangan" class="max-w-full max-h-96 border rounded-lg">
            `;
            document.getElementById('signatureModal').classList.remove('hidden');
        }

        function closeSignature() {
            document.getElementById('signatureModal').classList.add('hidden');
        }

        function exportInspeksi(inspeksiId) {
            // Simulate export functionality
            alert(`Fitur export untuk inspeksi #${inspeksiId} akan segera tersedia!`);
        }

        // Close modals when clicking outside
        document.addEventListener('click', function(event) {
            const detailModal = document.getElementById('detailModal');
            const signatureModal = document.getElementById('signatureModal');

            if (event.target === detailModal) {
                closeDetail();
            }
            if (event.target === signatureModal) {
                closeSignature();
            }
        });
    </script>
</body>

</html>
