<!-- resources/views/pengawas/inspeksi.blade.php -->
@extends('layouts.app')

@section('title', 'Form Inspeksi')

@section('styles')
    <style>
        .signature-pad {
            border: 1px solid #e2e8f0;
            border-radius: 0.375rem;
            background-color: white;
        }

        .question-card {
            transition: all 0.3s ease;
        }

        .question-card:hover {
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }
    </style>
@endsection

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
                    class="flex items-center py-2.5 px-4 rounded transition duration-200 hover:bg-blue-700 text-white">
                    <i class="fas fa-tachometer-alt mr-3"></i>
                    Dashboard
                </a>
                <a href="{{ route('pengawas.inspeksi') }}"
                    class="flex items-center py-2.5 px-4 rounded transition duration-200 hover:bg-blue-700 text-white bg-blue-700">
                    <i class="fas fa-clipboard-check mr-3"></i>
                    Inspeksi
                </a>
                <a href="{{ route('pengawas.laporan') }}"
                    class="flex items-center py-2.5 px-4 rounded transition duration-200 hover:bg-blue-700 text-white">
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
                        <h1 class="text-2xl font-bold text-gray-900">Form Inspeksi Harian</h1>
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
                <div class="max-w-4xl mx-auto">
                    <!-- Alert Info -->
                    @if ($inspeksiHariIni = \App\Models\Inspeksi::where('pengawas_id', Auth::id())->whereDate('created_at', today())->exists())
                        <div
                            class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded mb-6 flex items-center">
                            <i class="fas fa-exclamation-triangle mr-2"></i>
                            <span><strong>Perhatian:</strong> Anda sudah melakukan inspeksi hari ini. Anda hanya dapat
                                melakukan 1 inspeksi per hari.</span>
                        </div>
                    @endif

                    <div class="bg-white rounded-lg shadow-md p-6">
                        <div class="mb-6">
                            <h2 class="text-xl font-bold text-gray-800 mb-2 flex items-center">
                                <i class="fas fa-clipboard-check mr-2 text-blue-500"></i>
                                Form Inspeksi Harian
                            </h2>
                            <p class="text-gray-600 flex items-center">
                                <i class="fas fa-info-circle mr-2 text-gray-400"></i>
                                Pilih kategori inspeksi dan isi checklist sesuai dengan kondisi yang ditemui.
                            </p>
                        </div>

                        <form id="inspeksiForm" method="POST" action="{{ route('pengawas.storeInspeksi') }}">
                            @csrf

                            <div class="mb-8">
                                <label class="block text-sm font-medium text-gray-700 mb-3 flex items-center">
                                    <i class="fas fa-list-alt mr-2 text-blue-500"></i>
                                    Pilih Kategori Inspeksi
                                </label>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <!-- Checklist Kebersihan Lingkungan Kerja -->
                                    <div class="relative">
                                        <input class="sr-only peer" type="radio" name="kategori_id"
                                            value="{{ $kategories->where('nama', 'Kebersihan Lingkungan Kerja')->first()->id ?? '' }}"
                                            id="kategori1">
                                        <label
                                            class="flex flex-col p-4 bg-white border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 peer-checked:border-blue-500 peer-checked:bg-blue-50 transition duration-200"
                                            for="kategori1">
                                            <div class="flex items-center justify-between">
                                                <span class="font-medium text-gray-900">Kebersihan Lingkungan Kerja</span>
                                                <i class="fas fa-check-circle text-blue-600 hidden peer-checked:block"></i>
                                            </div>
                                            <span class="text-sm text-gray-500 mt-1 flex items-center">
                                                <i class="fas fa-broom mr-1 text-gray-400"></i>
                                                Pemeriksaan kebersihan area kerja
                                            </span>
                                        </label>
                                    </div>

                                    <!-- Checklist Pemeliharaan Toilet -->
                                    <div class="relative">
                                        <input class="sr-only peer" type="radio" name="kategori_id"
                                            value="{{ $kategories->where('nama', 'Pemeliharaan Toilet')->first()->id ?? '' }}"
                                            id="kategori2">
                                        <label
                                            class="flex flex-col p-4 bg-white border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 peer-checked:border-blue-500 peer-checked:bg-blue-50 transition duration-200"
                                            for="kategori2">
                                            <div class="flex items-center justify-between">
                                                <span class="font-medium text-gray-900">Pemeliharaan Toilet</span>
                                                <i class="fas fa-check-circle text-blue-600 hidden peer-checked:block"></i>
                                            </div>
                                            <span class="text-sm text-gray-500 mt-1 flex items-center">
                                                <i class="fas fa-toilet mr-1 text-gray-400"></i>
                                                Pemeriksaan kondisi toilet
                                            </span>
                                        </label>
                                    </div>

                                    <!-- Checklist Pemeliharaan Halaman/Parkir -->
                                    <div class="relative">
                                        <input class="sr-only peer" type="radio" name="kategori_id"
                                            value="{{ $kategories->where('nama', 'Pemeliharaan Halaman')->first()->id ?? '' }}"
                                            id="kategori3">
                                        <label
                                            class="flex flex-col p-4 bg-white border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 peer-checked:border-blue-500 peer-checked:bg-blue-50 transition duration-200"
                                            for="kategori3">
                                            <div class="flex items-center justify-between">
                                                <span class="font-medium text-gray-900">Pemeliharaan Halaman/Parkir</span>
                                                <i class="fas fa-check-circle text-blue-600 hidden peer-checked:block"></i>
                                            </div>
                                            <span class="text-sm text-gray-500 mt-1 flex items-center">
                                                <i class="fas fa-tree mr-1 text-gray-400"></i>
                                                Pemeriksaan area halaman dan parkir
                                            </span>
                                        </label>
                                    </div>
                                </div>
                                @error('kategori_id')
                                    <p class="mt-1 text-sm text-red-600 flex items-center">
                                        <i class="fas fa-exclamation-circle mr-1"></i>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <div id="pertanyaan-section" class="mb-8">
                                <!-- Pertanyaan akan dimuat di sini berdasarkan kategori -->
                                <div class="text-center py-8 text-gray-500" id="empty-state">
                                    <i class="fas fa-clipboard-list text-gray-300 text-6xl mb-4"></i>
                                    <p class="text-lg font-medium">Pilih kategori inspeksi terlebih dahulu</p>
                                    <p class="text-sm">Pilih salah satu kategori di atas untuk menampilkan checklist
                                        pertanyaan</p>
                                </div>
                            </div>

                            <div class="mb-8">
                                <label class="block text-sm font-medium text-gray-700 mb-3 flex items-center">
                                    <i class="fas fa-signature mr-2 text-blue-500"></i>
                                    Tanda Tangan Pengawas
                                </label>
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <div
                                        class="signature-pad w-full h-48 mb-3 border-2 border-dashed border-gray-300 rounded-lg">
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <div class="flex gap-2">
                                            <button type="button" id="clearSignature"
                                                class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 transition duration-200 flex items-center">
                                                <i class="fas fa-trash-alt mr-2"></i>
                                                Hapus
                                            </button>
                                            <button type="button" id="saveSignature"
                                                class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 transition duration-200 flex items-center">
                                                <i class="fas fa-check mr-2"></i>
                                                Simpan Tanda Tangan
                                            </button>
                                        </div>
                                        <span id="signatureStatus" class="text-sm text-gray-500">Belum tersimpan</span>
                                    </div>
                                </div>
                                <input type="hidden" name="tanda_tangan" id="tandaTangan">
                                @error('tanda_tangan')
                                    <p class="mt-1 text-sm text-red-600 flex items-center">
                                        <i class="fas fa-exclamation-circle mr-1"></i>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <div class="bg-gray-50 p-4 rounded-lg mb-6">
                                <div class="flex items-center">
                                    <input id="confirm-checkbox" type="checkbox"
                                        class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                    <label for="confirm-checkbox"
                                        class="ml-2 block text-sm text-gray-900 flex items-center">
                                        <i class="fas fa-shield-alt mr-2 text-blue-500"></i>
                                        Saya menyatakan bahwa data yang diisi adalah benar dan dapat dipertanggungjawabkan
                                    </label>
                                </div>
                            </div>

                            <button type="submit" id="submitButton" disabled
                                class="w-full bg-gray-400 text-white py-3 px-4 rounded-md transition duration-200 flex items-center justify-center cursor-not-allowed">
                                <i class="fas fa-paper-plane mr-2"></i>
                                Submit Inspeksi
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Setup Canvas untuk Tanda Tangan
            const canvas = document.createElement('canvas');
            canvas.width = 800;
            canvas.height = 200;
            canvas.style.width = '100%';
            canvas.style.height = '200px';
            canvas.style.backgroundColor = 'white';

            const signaturePad = document.querySelector('.signature-pad');
            signaturePad.appendChild(canvas);

            const ctx = canvas.getContext('2d');
            let drawing = false;

            // Setup canvas
            ctx.strokeStyle = '#000';
            ctx.lineWidth = 2;
            ctx.lineCap = 'round';

            // Drawing functions
            function startDrawing(e) {
                drawing = true;
                draw(e);
            }

            function stopDrawing() {
                drawing = false;
                ctx.beginPath();
            }

            function draw(e) {
                if (!drawing) return;

                const rect = canvas.getBoundingClientRect();
                const x = e.clientX - rect.left;
                const y = e.clientY - rect.top;

                ctx.lineTo(x, y);
                ctx.stroke();
                ctx.beginPath();
                ctx.moveTo(x, y);
            }

            // Event listeners
            canvas.addEventListener('mousedown', startDrawing);
            canvas.addEventListener('mousemove', draw);
            canvas.addEventListener('mouseup', stopDrawing);
            canvas.addEventListener('mouseout', stopDrawing);

            // Touch events for mobile
            canvas.addEventListener('touchstart', (e) => {
                e.preventDefault();
                const touch = e.touches[0];
                const mouseEvent = new MouseEvent('mousedown', {
                    clientX: touch.clientX,
                    clientY: touch.clientY
                });
                canvas.dispatchEvent(mouseEvent);
            });

            canvas.addEventListener('touchmove', (e) => {
                e.preventDefault();
                const touch = e.touches[0];
                const mouseEvent = new MouseEvent('mousemove', {
                    clientX: touch.clientX,
                    clientY: touch.clientY
                });
                canvas.dispatchEvent(mouseEvent);
            });

            canvas.addEventListener('touchend', (e) => {
                e.preventDefault();
                const mouseEvent = new MouseEvent('mouseup');
                canvas.dispatchEvent(mouseEvent);
            });

            // Clear signature
            document.getElementById('clearSignature').addEventListener('click', function() {
                ctx.clearRect(0, 0, canvas.width, canvas.height);
                document.getElementById('tandaTangan').value = '';
                document.getElementById('signatureStatus').textContent = 'Belum tersimpan';
                document.getElementById('signatureStatus').className = 'text-sm text-gray-500';
                checkFormValidity();
            });

            // Save signature
            document.getElementById('saveSignature').addEventListener('click', function() {
                const dataURL = canvas.toDataURL();
                document.getElementById('tandaTangan').value = dataURL;
                document.getElementById('signatureStatus').textContent = 'Tersimpan ✓';
                document.getElementById('signatureStatus').className = 'text-sm text-green-600 font-medium';
                checkFormValidity();
            });
            // Load pertanyaan berdasarkan kategori menggunakan AJAX
            const radioButtons = document.querySelectorAll('input[name="kategori_id"]');
            radioButtons.forEach(radio => {
                radio.addEventListener('change', function() {
                    const kategoriId = this.value;
                    const pertanyaanSection = document.getElementById('pertanyaan-section');
                    const emptyState = document.getElementById('empty-state');

                    if (kategoriId) {
                        emptyState.style.display = 'none';

                        // Show loading
                        pertanyaanSection.innerHTML = `
                <div class="flex justify-center items-center py-8">
                    <div class="loading-spinner mr-3"></div>
                    <span class="text-gray-600">Memuat pertanyaan...</span>
                </div>
            `;

                        console.log('Mengambil pertanyaan untuk kategori:', kategoriId);

                        // AJAX request untuk mengambil pertanyaan dari server
                        fetch(`/pertanyaan/${kategoriId}`)
                            .then(response => {
                                console.log('Response status:', response.status);
                                if (!response.ok) {
                                    throw new Error(`HTTP error! status: ${response.status}`);
                                }
                                return response.json();
                            })
                            .then(data => {
                                console.log('Data diterima:', data);

                                let html = `
                        <div class="mb-4">
                            <h3 class="text-lg font-semibold text-gray-800 mb-2 flex items-center">
                                <i class="fas fa-tasks mr-2 text-blue-500"></i>
                                Checklist Pertanyaan
                            </h3>
                            <p class="text-sm text-gray-600 flex items-center">
                                <i class="fas fa-info-circle mr-2 text-gray-400"></i>
                                Jawab semua pertanyaan berikut dengan memilih Ya atau Tidak
                            </p>
                        </div>
                        <div class="space-y-4">
                    `;

                                if (data.length > 0) {
                                    data.forEach((pertanyaan, index) => {
                                        html += `
                                <div class="question-card bg-white p-4 border border-gray-200 rounded-lg">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1">
                                            <label class="block text-sm font-medium text-gray-700 mb-3">
                                                <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded mr-2">${index + 1}</span>
                                                ${pertanyaan.pertanyaan}
                                            </label>
                                            <div class="flex items-center space-x-6">
                                                <label class="inline-flex items-center">
                                                    <input type="radio" name="jawaban[${pertanyaan.id}]" value="Ya" required 
                                                           class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300">
                                                    <span class="ml-2 text-gray-700 font-medium flex items-center">
                                                        <i class="fas fa-check-circle mr-1 text-green-500"></i>
                                                        Ya
                                                    </span>
                                                </label>
                                                <label class="inline-flex items-center">
                                                    <input type="radio" name="jawaban[${pertanyaan.id}]" value="Tidak" required 
                                                           class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300">
                                                    <span class="ml-2 text-gray-700 font-medium flex items-center">
                                                        <i class="fas fa-times-circle mr-1 text-red-500"></i>
                                                        Tidak
                                                    </span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            `;
                                    });
                                } else {
                                    html = `
                            <div class="text-center py-8 text-gray-500">
                                <i class="fas fa-clipboard-question text-gray-300 text-6xl mb-4"></i>
                                <p class="text-lg font-medium">Tidak ada pertanyaan tersedia</p>
                                <p class="text-sm">Belum ada pertanyaan yang ditambahkan untuk kategori ini.</p>
                            </div>
                        `;
                                }

                                html += `</div>`;
                                pertanyaanSection.innerHTML = html;
                                checkFormValidity();
                            })
                            .catch(error => {
                                console.error('Error detail:', error);
                                pertanyaanSection.innerHTML = `
                        <div class="text-center py-8 text-red-500">
                            <i class="fas fa-exclamation-triangle text-3xl mb-3"></i>
                            <p class="text-lg font-medium">Terjadi kesalahan</p>
                            <p class="text-sm">Gagal memuat pertanyaan. Silakan coba lagi.</p>
                            <p class="text-xs mt-2">Error: ${error.message}</p>
                            <button onclick="location.reload()" class="mt-4 px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                                <i class="fas fa-refresh mr-2"></i>Refresh Halaman
                            </button>
                        </div>
                    `;
                            });
                    } else {
                        emptyState.style.display = 'block';
                        pertanyaanSection.innerHTML = '';
                    }
                });
            });
            // Load pertanyaan berdasarkan kategori menggunakan AJAX
            document.addEventListener('DOMContentLoaded', function() {
                const radioButtons = document.querySelectorAll('input[name="kategori_id"]');

                radioButtons.forEach(radio => {
                    radio.addEventListener('change', function() {
                        const kategoriId = this.value;
                        const pertanyaanSection = document.getElementById(
                            'pertanyaan-section');
                        const emptyState = document.getElementById('empty-state');

                        // Validasi element exists
                        if (!pertanyaanSection || !emptyState) {
                            console.error('Required elements not found');
                            return;
                        }

                        if (kategoriId) {
                            emptyState.style.display = 'none';

                            // Show loading
                            pertanyaanSection.innerHTML = `
                    <div class="flex justify-center items-center py-8">
                        <i class="fas fa-spinner fa-spin text-blue-500 text-xl mr-3"></i>
                        <span class="text-gray-600">Memuat pertanyaan...</span>
                    </div>
                `;

                            console.log('Mengambil pertanyaan untuk kategori ID:',
                                kategoriId);

                            // PERBAIKI URL: hapus /api dari path
                            fetch(`/pertanyaan/${kategoriId}`)
                                .then(response => {
                                    console.log('Response status:', response.status);
                                    if (!response.ok) {
                                        throw new Error(
                                            `HTTP error! status: ${response.status}`
                                            );
                                    }
                                    return response.json();
                                })
                                .then(data => {
                                    console.log('Data pertanyaan diterima:', data);

                                    let html = `
                            <div class="mb-4">
                                <h3 class="text-lg font-semibold text-gray-800 mb-2 flex items-center">
                                    <i class="fas fa-tasks mr-2 text-blue-500"></i>
                                    Checklist Pertanyaan
                                </h3>
                                <p class="text-sm text-gray-600 flex items-center">
                                    <i class="fas fa-info-circle mr-2 text-gray-400"></i>
                                    Jawab semua pertanyaan berikut dengan memilih Ya atau Tidak
                                </p>
                            </div>
                            <div class="space-y-4">
                        `;

                                    if (data && data.length > 0) {
                                        data.forEach((pertanyaan, index) => {
                                            html += `
                                    <div class="question-card bg-white p-4 border border-gray-200 rounded-lg">
                                        <div class="flex items-start justify-between">
                                            <div class="flex-1">
                                                <label class="block text-sm font-medium text-gray-700 mb-3">
                                                    <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded mr-2">${index + 1}</span>
                                                    ${pertanyaan.pertanyaan}
                                                </label>
                                                <div class="flex items-center space-x-6">
                                                    <label class="inline-flex items-center">
                                                        <input type="radio" name="jawaban[${pertanyaan.id}]" value="Ya" required 
                                                               class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300">
                                                        <span class="ml-2 text-gray-700 font-medium flex items-center">
                                                            <i class="fas fa-check-circle mr-1 text-green-500"></i>
                                                            Ya
                                                        </span>
                                                    </label>
                                                    <label class="inline-flex items-center">
                                                        <input type="radio" name="jawaban[${pertanyaan.id}]" value="Tidak" required 
                                                               class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300">
                                                        <span class="ml-2 text-gray-700 font-medium flex items-center">
                                                            <i class="fas fa-times-circle mr-1 text-red-500"></i>
                                                            Tidak
                                                        </span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                `;
                                        });
                                    } else {
                                        html = `
                                <div class="text-center py-8 text-gray-500">
                                    <i class="fas fa-clipboard-question text-gray-300 text-6xl mb-4"></i>
                                    <p class="text-lg font-medium">Tidak ada pertanyaan tersedia</p>
                                    <p class="text-sm">Belum ada pertanyaan yang ditambahkan untuk kategori ini.</p>
                                </div>
                            `;
                                    }

                                    html += `</div>`;
                                    pertanyaanSection.innerHTML = html;
                                    checkFormValidity();
                                })
                                .catch(error => {
                                    console.error('Error fetching pertanyaan:', error);
                                    pertanyaanSection.innerHTML = `
                            <div class="text-center py-8 text-red-500">
                                <i class="fas fa-exclamation-triangle text-3xl mb-3"></i>
                                <p class="text-lg font-medium">Terjadi kesalahan</p>
                                <p class="text-sm">Gagal memuat pertanyaan. Silakan coba lagi.</p>
                                <p class="text-xs mt-2">${error.message}</p>
                                <div class="mt-4 space-x-2">
                                    <button onclick="location.reload()" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition duration-200">
                                        <i class="fas fa-refresh mr-2"></i>Refresh Halaman
                                    </button>
                                    <button onclick="testEndpoint()" class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600 transition duration-200">
                                        <i class="fas fa-test mr-2"></i>Test Connection
                                    </button>
                                </div>
                            </div>
                        `;
                                });
                        } else {
                            emptyState.style.display = 'block';
                            pertanyaanSection.innerHTML = '';
                        }
                    });
                });

                // Fungsi untuk test koneksi
                window.testEndpoint = function() {
                    const kategoriId = document.querySelector('input[name="kategori_id"]:checked')
                        ?.value || 1;
                    fetch(`/pertanyaan/${kategoriId}`)
                        .then(response => {
                            alert(`Status: ${response.status}\nURL: /pertanyaan/${kategoriId}`);
                            return response.json();
                        })
                        .then(data => {
                            console.log('Test data:', data);
                            alert('Koneksi berhasil! Lihat console untuk detail.');
                        })
                        .catch(error => {
                            alert('Error: ' + error.message);
                        });
                };
            });

            // Check form validity
            function checkFormValidity() {
                const kategoriSelected = document.querySelector('input[name="kategori_id"]:checked');
                const signatureSaved = document.getElementById('tandaTangan').value !== '';
                const confirmChecked = document.getElementById('confirm-checkbox').checked;

                // Check if all questions are answered
                let allQuestionsAnswered = true;
                const questionRadios = document.querySelectorAll('input[type="radio"][name^="jawaban"]');
                const answeredQuestions = new Set();

                questionRadios.forEach(radio => {
                    if (radio.checked) {
                        const name = radio.getAttribute('name');
                        answeredQuestions.add(name);
                    }
                });

                // Get total number of unique questions
                const totalQuestions = new Set(Array.from(questionRadios).map(radio => radio.getAttribute('name')))
                    .size;
                allQuestionsAnswered = answeredQuestions.size === totalQuestions;

                const submitButton = document.getElementById('submitButton');

                if (kategoriSelected && signatureSaved && confirmChecked && allQuestionsAnswered) {
                    submitButton.disabled = false;
                    submitButton.className =
                        'w-full bg-green-500 text-white py-3 px-4 rounded-md hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-500 transition duration-200 flex items-center justify-center';
                } else {
                    submitButton.disabled = true;
                    submitButton.className =
                        'w-full bg-gray-400 text-white py-3 px-4 rounded-md transition duration-200 flex items-center justify-center cursor-not-allowed';
                }
            }

            // Add event listeners for form validation
            document.getElementById('confirm-checkbox').addEventListener('change', checkFormValidity);

            // Delegate event listener for dynamically loaded radio buttons
            document.addEventListener('change', function(e) {
                if (e.target.matches('input[type="radio"][name^="jawaban"]')) {
                    checkFormValidity();
                }
            });

            // Form submission
            document.getElementById('inspeksiForm').addEventListener('submit', function(e) {
                if (!document.getElementById('tandaTangan').value) {
                    e.preventDefault();
                    alert('Harap simpan tanda tangan terlebih dahulu!');
                    return;
                }

                if (!document.getElementById('confirm-checkbox').checked) {
                    e.preventDefault();
                    alert('Harap centang konfirmasi bahwa data yang diisi adalah benar!');
                    return;
                }

                // Show loading state
                const submitButton = document.getElementById('submitButton');
                submitButton.innerHTML = `
                    <i class="fas fa-spinner fa-spin mr-2"></i>
                    Menyimpan data...
                `;
                submitButton.disabled = true;
            });
        });
    </script>
@endsection
