<!-- resources/views/pengawas/laporan.blade.php -->
@extends('layouts.app')

@section('title', 'Laporan Inspeksi')

@section('content')
    <div class="min-h-screen bg-gray-100">
        <!-- Sidebar -->
        <div
            class="fixed inset-y-0 left-0 w-64 bg-blue-800 transform transition-transform duration-200 ease-in-out -translate-x-full md:translate-x-0">
            <div class="flex items-center justify-center h-16 bg-blue-900">
                <span class="text-white text-lg font-semibold">Dashboard Pengawas</span>
            </div>
            <nav class="mt-5">
                <a href="{{ route('pengawas.dashboard') }}"
                    class="block py-2.5 px-4 rounded transition duration-200 hover:bg-blue-700 text-white">
                    Dashboard
                </a>
                <a href="{{ route('pengawas.inspeksi') }}"
                    class="block py-2.5 px-4 rounded transition duration-200 hover:bg-blue-700 text-white">
                    Inspeksi
                </a>
                <a href="{{ route('pengawas.laporan') }}"
                    class="block py-2.5 px-4 rounded transition duration-200 hover:bg-blue-700 text-white bg-blue-700">
                    Laporan
                </a>
                <form method="POST" action="{{ route('logout') }}"
                    class="block py-2.5 px-4 rounded transition duration-200 hover:bg-blue-700 text-white">
                    @csrf
                    <button type="submit">Logout</button>
                </form>
            </nav>
        </div>

        <!-- Main content -->
        <div class="md:ml-64">
            <!-- Header -->
            <div class="bg-white shadow">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between items-center py-4">
                        <h1 class="text-2xl font-bold text-gray-900">Laporan Inspeksi</h1>
                        <div class="text-sm text-gray-500">
                            {{ Auth::user()->name }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="p-6">
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-800">Daftar Inspeksi yang Telah Dilakukan</h2>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Tanggal
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Kategori
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Jumlah Pertanyaan
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Tanda Tangan
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Aksi
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($inspeksis as $inspeksi)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $inspeksi->created_at->format('d/m/Y H:i') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $inspeksi->kategori->nama }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $inspeksi->jawabans->count() }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <img src="{{ $inspeksi->tanda_tangan }}" alt="Tanda Tangan"
                                                class="h-10 w-32 object-contain border">
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <button onclick="showDetail({{ $inspeksi->id }})"
                                                class="text-blue-600 hover:text-blue-900 mr-3">
                                                Detail
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">
                                            Belum ada laporan inspeksi.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Detail -->
    <div id="detailModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Detail Inspeksi</h3>
                    <button onclick="closeDetail()" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                            </path>
                        </svg>
                    </button>
                </div>
                <div id="modalContent">
                    <!-- Content akan diisi oleh JavaScript -->
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        function showDetail(inspeksiId) {
            fetch(`/api/inspeksi/${inspeksiId}`)
                .then(response => response.json())
                .then(data => {
                    let html = `
                    <div class="mb-4">
                        <p><strong>Tanggal:</strong> ${new Date(data.created_at).toLocaleString()}</p>
                        <p><strong>Kategori:</strong> ${data.kategori.nama}</p>
                    </div>
                    <div class="border-t pt-4">
                        <h4 class="font-semibold mb-2">Hasil Jawaban:</h4>
                        <div class="space-y-2">
                `;

                    data.jawabans.forEach(jawaban => {
                        html += `
                        <div class="flex justify-between items-center p-2 bg-gray-50 rounded">
                            <span>${jawaban.pertanyaan.pertanyaan}</span>
                            <span class="px-2 py-1 rounded text-xs font-medium ${jawaban.jawaban === 'Ya' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'}">
                                ${jawaban.jawaban}
                            </span>
                        </div>
                    `;
                    });

                    html += `
                        </div>
                    </div>
                    <div class="border-t pt-4 mt-4">
                        <h4 class="font-semibold mb-2">Tanda Tangan:</h4>
                        <img src="${data.tanda_tangan}" alt="Tanda Tangan" class="h-20 border">
                    </div>
                `;

                    document.getElementById('modalContent').innerHTML = html;
                    document.getElementById('detailModal').classList.remove('hidden');
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        }

        function closeDetail() {
            document.getElementById('detailModal').classList.add('hidden');
        }
    </script>
@endsection
