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

        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
        }

        .tab-button {
            transition: all 0.3s ease;
        }

        .tab-button.active {
            border-color: #3b82f6;
            color: #3b82f6;
        }

        .btn-spacing {
            margin-right: 0.75rem;
            /* 12px */
        }

        .btn-spacing:last-child {
            margin-right: 0;
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
                <div class="max-w-6xl mx-auto">
                    <!-- Alert Info -->
                    @if ($inspeksiHariIni)
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
                                Form Inspeksi Harian - Semua Kategori
                            </h2>
                            <p class="text-gray-600 flex items-center">
                                <i class="fas fa-info-circle mr-2 text-gray-400"></i>
                                Isi checklist untuk semua kategori sesuai dengan kondisi yang ditemui.
                            </p>
                        </div>

                        <form id="inspeksiForm" method="POST" action="{{ route('pengawas.storeInspeksi') }}">
                            @csrf

                            <!-- Tab Navigation -->
                            <div class="mb-8">
                                <label class="block text-sm font-medium text-gray-700 mb-3 flex items-center">
                                    <i class="fas fa-list-alt mr-2 text-blue-500"></i>
                                    Pilih Kategori Inspeksi
                                </label>
                                <div class="border-b border-gray-200">
                                    <nav class="-mb-px flex space-x-8" role="tablist">
                                        @foreach ($kategories as $index => $kategori)
                                            <button type="button" role="tab" data-tab="kategori-{{ $kategori->id }}"
                                                class="tab-button whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition duration-200 {{ $index === 0 ? 'border-blue-500 text-blue-600 active' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}"
                                                aria-selected="{{ $index === 0 ? 'true' : 'false' }}">
                                                {{ $kategori->nama }}
                                                <span id="status-{{ $kategori->id }}"
                                                    class="ml-2 px-2 py-1 text-xs rounded-full bg-gray-200 text-gray-700">
                                                    Belum
                                                </span>
                                            </button>
                                        @endforeach
                                    </nav>
                                </div>
                            </div>

                            <!-- Tab Content -->
                            <div class="tab-content-wrapper mb-8">
                                @foreach ($kategories as $index => $kategori)
                                    <div id="kategori-{{ $kategori->id }}"
                                        class="tab-content {{ $index === 0 ? 'active' : '' }}" role="tabpanel"
                                        aria-labelledby="tab-{{ $kategori->id }}">

                                        <div class="mb-4">
                                            <h3 class="text-lg font-semibold text-gray-800 mb-2 flex items-center">
                                                <i class="fas fa-tasks mr-2 text-blue-500"></i>
                                                {{ $kategori->nama }}
                                            </h3>
                                            <p class="text-sm text-gray-600 flex items-center">
                                                <i class="fas fa-info-circle mr-2 text-gray-400"></i>
                                                {{ $kategori->deskripsi }}
                                            </p>
                                        </div>

                                        <div id="pertanyaan-section-{{ $kategori->id }}" class="space-y-4">
                                            <div class="text-center py-8 text-gray-500 loading-state">
                                                <i class="fas fa-spinner fa-spin text-blue-500 text-xl mr-3"></i>
                                                <span class="text-gray-600">Memuat pertanyaan...</span>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Progress Indicator -->
                            <div class="mb-8 bg-blue-50 p-4 rounded-lg">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-sm font-medium text-blue-800">Progress Pengerjaan</span>
                                    <span id="progress-text" class="text-sm font-bold text-blue-800">0/3 Kategori</span>
                                </div>
                                <div class="w-full bg-blue-200 rounded-full h-2.5">
                                    <div id="progress-bar"
                                        class="bg-blue-600 h-2.5 rounded-full transition-all duration-300"
                                        style="width: 0%"></div>
                                </div>
                                <div class="mt-2 text-xs text-blue-600">
                                    <span id="progress-detail">Semua kategori harus diselesaikan sebelum submit</span>
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
                                                class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 transition duration-200 flex items-center btn-spacing">
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
                                Submit Inspeksi (Semua Kategori)
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
            // State management
            const state = {
                jawaban: {}, // { kategoriId: { pertanyaanId: jawaban } }
                kategoriStatus: {}, // { kategoriId: boolean (completed or not) }
                currentTab: null
            };

            // Initialize state for each category
            @foreach ($kategories as $kategori)
                state.jawaban[{{ $kategori->id }}] = {};
                state.kategoriStatus[{{ $kategori->id }}] = false;
            @endforeach

            // Canvas untuk Tanda Tangan
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

            // Event listeners for canvas
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

            // Tab functionality
            const tabButtons = document.querySelectorAll('.tab-button');
            const tabContents = document.querySelectorAll('.tab-content');

            tabButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const tabId = this.getAttribute('data-tab');

                    // Remove active class from all tabs
                    tabButtons.forEach(btn => {
                        btn.classList.remove('active', 'border-blue-500', 'text-blue-600');
                        btn.classList.add('border-transparent', 'text-gray-500');
                        btn.setAttribute('aria-selected', 'false');
                    });

                    tabContents.forEach(content => {
                        content.classList.remove('active');
                    });

                    // Add active class to current tab
                    this.classList.add('active', 'border-blue-500', 'text-blue-600');
                    this.classList.remove('border-transparent', 'text-gray-500');
                    this.setAttribute('aria-selected', 'true');

                    document.getElementById(tabId).classList.add('active');
                    state.currentTab = tabId;

                    // Load questions for this tab if not already loaded
                    const kategoriId = tabId.replace('kategori-', '');
                    loadPertanyaan(kategoriId);
                });
            });

            // Load first tab by default
            if (tabButtons.length > 0) {
                tabButtons[0].click();
            }

            // Load pertanyaan berdasarkan kategori
            function loadPertanyaan(kategoriId) {
                const pertanyaanSection = document.getElementById(`pertanyaan-section-${kategoriId}`);
                const loadingState = pertanyaanSection.querySelector('.loading-state');

                // Check if already loaded
                if (pertanyaanSection.getAttribute('data-loaded') === 'true') {
                    return;
                }

                fetch(`/pertanyaan/${kategoriId}`)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                        renderPertanyaan(kategoriId, data);
                        pertanyaanSection.setAttribute('data-loaded', 'true');
                        loadingState.style.display = 'none';
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        loadingState.innerHTML = `
                        <div class="text-center py-8 text-red-500">
                            <i class="fas fa-exclamation-triangle text-3xl mb-3"></i>
                            <p class="text-lg font-medium">Terjadi kesalahan</p>
                            <p class="text-sm">Gagal memuat pertanyaan. Silakan coba lagi.</p>
                        </div>
                    `;
                    });
            }

            function renderPertanyaan(kategoriId, pertanyaans) {
                const pertanyaanSection = document.getElementById(`pertanyaan-section-${kategoriId}`);
                let html = '';

                if (pertanyaans.length > 0) {
                    pertanyaans.forEach((pertanyaan, index) => {
                        // Check if we have a saved answer
                        const savedAnswer = state.jawaban[kategoriId] && state.jawaban[kategoriId][
                            pertanyaan.id
                        ];

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
                                            <input type="radio" 
                                                   name="jawaban[${kategoriId}][${pertanyaan.id}]" 
                                                   value="Ya" 
                                                   class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300"
                                                   ${savedAnswer === 'Ya' ? 'checked' : ''}
                                                   onchange="updateJawaban(${kategoriId}, ${pertanyaan.id}, 'Ya')">
                                            <span class="ml-2 text-gray-700 font-medium flex items-center">
                                                <i class="fas fa-check-circle mr-1 text-green-500"></i>
                                                Ya
                                            </span>
                                        </label>
                                        <label class="inline-flex items-center">
                                            <input type="radio" 
                                                   name="jawaban[${kategoriId}][${pertanyaan.id}]" 
                                                   value="Tidak" 
                                                   class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300"
                                                   ${savedAnswer === 'Tidak' ? 'checked' : ''}
                                                   onchange="updateJawaban(${kategoriId}, ${pertanyaan.id}, 'Tidak')">
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

                pertanyaanSection.innerHTML = html;
                checkCategoryCompletion(kategoriId);
            }

            // Global function to update answers
            window.updateJawaban = function(kategoriId, pertanyaanId, jawaban) {
                if (!state.jawaban[kategoriId]) {
                    state.jawaban[kategoriId] = {};
                }
                state.jawaban[kategoriId][pertanyaanId] = jawaban;

                // Update UI to show changes are made
                // const statusElement = document.getElementById(`status-simpan-${kategoriId}`);
                // if (statusElement) {
                //     statusElement.textContent = 'Perubahan belum disimpan';
                //     statusElement.className = 'text-sm text-yellow-600 ml-2';
                // }

                checkCategoryCompletion(kategoriId);
            };

            // Check if a category is completed
            function checkCategoryCompletion(kategoriId) {
                const pertanyaanSection = document.getElementById(`pertanyaan-section-${kategoriId}`);
                const questions = pertanyaanSection.querySelectorAll('input[type="radio"]');
                const answeredQuestions = new Set();

                questions.forEach(question => {
                    if (question.checked) {
                        const name = question.getAttribute('name');
                        answeredQuestions.add(name);
                    }
                });

                const totalQuestions = new Set(Array.from(questions).map(q => q.getAttribute('name'))).size;
                const isCompleted = answeredQuestions.size === totalQuestions;

                state.kategoriStatus[kategoriId] = isCompleted;

                // Update category status indicator
                const statusElement = document.getElementById(`status-${kategoriId}`);
                if (statusElement) {
                    if (isCompleted) {
                        statusElement.textContent = 'Selesai ✓';
                        statusElement.className = 'ml-2 px-2 py-1 text-xs rounded-full bg-green-100 text-green-800';
                    } else {
                        statusElement.textContent = `${answeredQuestions.size}/${totalQuestions}`;
                        statusElement.className =
                            'ml-2 px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800';
                    }
                }

                updateProgress();
                checkFormValidity();
            }

            // Update progress bar
            function updateProgress() {
                const totalCategories = Object.keys(state.kategoriStatus).length;
                const completedCategories = Object.values(state.kategoriStatus).filter(status => status).length;
                const progressPercentage = (completedCategories / totalCategories) * 100;

                document.getElementById('progress-bar').style.width = `${progressPercentage}%`;
                document.getElementById('progress-text').textContent =
                    `${completedCategories}/${totalCategories} Kategori`;

                if (completedCategories === totalCategories) {
                    document.getElementById('progress-detail').textContent = 'Semua kategori telah diselesaikan!';
                    document.getElementById('progress-detail').className =
                        'mt-2 text-xs text-green-600 font-medium';
                } else {
                    document.getElementById('progress-detail').textContent =
                        'Semua kategori harus diselesaikan sebelum submit';
                    document.getElementById('progress-detail').className = 'mt-2 text-xs text-blue-600';
                }
            }

            // // Save temporary data per category
            // document.querySelectorAll('.btn-simpan-kategori').forEach(button => {
            //     button.addEventListener('click', function() {
            //         const kategoriId = this.getAttribute('data-kategori');
            //         const statusElement = document.getElementById(`status-simpan-${kategoriId}`);

            //         // Simulate saving (in real implementation, you might want to save to localStorage or make an AJAX call)
            //         statusElement.textContent = 'Tersimpan ✓';
            //         statusElement.className = 'text-sm text-green-600 ml-2';

            //         // Show success message
            //         showTemporaryMessage('Data kategori berhasil disimpan sementara', 'success');
            //     });
            // });

            // Check form validity
            function checkFormValidity() {
                const allCategoriesCompleted = Object.values(state.kategoriStatus).every(status => status);
                const signatureSaved = document.getElementById('tandaTangan').value !== '';
                const confirmChecked = document.getElementById('confirm-checkbox').checked;

                const submitButton = document.getElementById('submitButton');

                if (allCategoriesCompleted && signatureSaved && confirmChecked) {
                    submitButton.disabled = false;
                    submitButton.className =
                        'w-full bg-green-500 text-white py-3 px-4 rounded-md hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-500 transition duration-200 flex items-center justify-center';
                } else {
                    submitButton.disabled = true;
                    submitButton.className =
                        'w-full bg-gray-400 text-white py-3 px-4 rounded-md transition duration-200 flex items-center justify-center cursor-not-allowed';
                }
            }

            // Utility function to show temporary messages
            function showTemporaryMessage(message, type = 'info') {
                const colors = {
                    success: 'green',
                    error: 'red',
                    info: 'blue'
                };

                const alertDiv = document.createElement('div');
                alertDiv.className =
                    `bg-${colors[type]}-100 border border-${colors[type]}-400 text-${colors[type]}-700 px-4 py-3 rounded mb-4 flex items-center`;
                alertDiv.innerHTML = `
                <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : 'info-circle'} mr-2"></i>
                ${message}
            `;

                document.querySelector('.bg-white.rounded-lg.shadow-md.p-6').insertBefore(alertDiv, document
                    .querySelector('.bg-white.rounded-lg.shadow-md.p-6').firstChild);

                setTimeout(() => {
                    alertDiv.remove();
                }, 3000);
            }

            // Add event listeners for form validation
            document.getElementById('confirm-checkbox').addEventListener('change', checkFormValidity);

            // Form submission
            document.getElementById('inspeksiForm').addEventListener('submit', function(e) {
                if (!document.getElementById('tandaTangan').value) {
                    e.preventDefault();
                    showTemporaryMessage('Harap simpan tanda tangan terlebih dahulu!', 'error');
                    return;
                }

                if (!document.getElementById('confirm-checkbox').checked) {
                    e.preventDefault();
                    showTemporaryMessage('Harap centang konfirmasi bahwa data yang diisi adalah benar!',
                        'error');
                    return;
                }

                // Check if all categories are completed
                const allCompleted = Object.values(state.kategoriStatus).every(status => status);
                if (!allCompleted) {
                    e.preventDefault();
                    showTemporaryMessage('Harap lengkapi semua kategori sebelum submit!', 'error');
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

            // Initialize progress
            updateProgress();
        });
    </script>
@endsection
