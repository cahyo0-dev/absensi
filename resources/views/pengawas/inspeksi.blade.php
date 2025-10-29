@extends('layouts.app')

@section('title', $editMode ? 'Edit Inspeksi' : 'Form Inspeksi')

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
        }

        .btn-spacing:last-child {
            margin-right: 0;
        }

        .edit-badge {
            background: linear-gradient(45deg, #f59e0b, #d97706);
            color: white;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            margin-left: 8px;
        }

        /* Responsive improvements */
        @media (max-width: 640px) {
            .tab-button {
                font-size: 0.75rem;
                padding: 0.5rem 0.25rem;
            }

            .signature-pad {
                height: 150px;
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
                        @if ($editMode)
                            <i class="fas fa-edit mr-2 text-yellow-500"></i>
                            Edit Inspeksi - {{ $inspeksi->tanggal->format('d/m/Y') }}
                            <span class="edit-badge">EDIT MODE</span>
                        @else
                            <i class="fas fa-clipboard-check mr-2 text-blue-500"></i>
                            Form Inspeksi Harian
                        @endif
                    </h1>
                    <p class="text-gray-600 mt-1 text-sm sm:text-base">
                        @if ($editMode)
                            Edit checklist untuk semua kategori sesuai dengan kondisi yang ditemui.
                        @else
                            Isi checklist untuk semua kategori sesuai dengan kondisi yang ditemui.
                        @endif
                    </p>
                </div>
                <div class="mt-3 sm:mt-0 text-sm text-gray-500 bg-gray-50 px-3 py-2 rounded-lg whitespace-nowrap">
                    <i class="fas fa-calendar-alt mr-2"></i>
                    {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
                </div>
            </div>
        </div>

        <!-- Alert Info -->
        @if (!$editMode && $sudahInspeksiHariIni)
            <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded flex items-center">
                <i class="fas fa-exclamation-triangle mr-2"></i>
                <span class="text-sm sm:text-base">
                    <strong>Perhatian:</strong> Inspeksi hari ini sudah dilakukan oleh
                    <strong>{{ $inspektorHariIni->name ?? 'pengawas lain' }}</strong>.
                    Sistem hanya mengizinkan 1 inspeksi per hari.
                </span>
            </div>
        @endif

        @if ($editMode)
            <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded flex items-center">
                <i class="fas fa-info-circle mr-2"></i>
                <span class="text-sm sm:text-base">
                    Anda sedang dalam mode edit. Perubahan akan memperbarui data inspeksi yang sudah ada.
                </span>
            </div>
        @endif

        <div class="bg-white rounded-lg shadow-md p-4 sm:p-6">
            <form id="inspeksiForm" method="POST"
                action="{{ $editMode ? route('pengawas.inspeksi.update', $inspeksi->id) : route('pengawas.storeInspeksi') }}">
                @csrf
                @if ($editMode)
                    @method('PUT')
                    <input type="hidden" name="inspeksi_id" value="{{ $inspeksi->id }}">
                @endif

                <!-- Tab Navigation -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-3 flex items-center">
                        <i class="fas fa-list-alt mr-2 text-blue-500"></i>
                        Pilih Kategori Inspeksi
                    </label>
                    <div class="border-b border-gray-200">
                        <nav class="-mb-px flex space-x-2 sm:space-x-4 overflow-x-auto" role="tablist">
                            @foreach ($kategories as $index => $kategori)
                                <button type="button" role="tab" data-tab="kategori-{{ $kategori->id }}"
                                    class="tab-button whitespace-nowrap py-3 px-2 sm:px-3 border-b-2 font-medium text-xs sm:text-sm transition duration-200 {{ $index === 0 ? 'border-blue-500 text-blue-600 active' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}"
                                    aria-selected="{{ $index === 0 ? 'true' : 'false' }}">
                                    {{ $kategori->nama }}
                                    <span id="status-{{ $kategori->id }}"
                                        class="ml-1 sm:ml-2 px-1.5 sm:px-2 py-0.5 text-xs rounded-full bg-gray-200 text-gray-700">
                                        Belum
                                    </span>
                                </button>
                            @endforeach
                        </nav>
                    </div>
                </div>

                <!-- Tab Content -->
                <div class="tab-content-wrapper mb-6">
                    @foreach ($kategories as $index => $kategori)
                        <div id="kategori-{{ $kategori->id }}" class="tab-content {{ $index === 0 ? 'active' : '' }}"
                            role="tabpanel" aria-labelledby="tab-{{ $kategori->id }}">

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

                            <div id="pertanyaan-section-{{ $kategori->id }}" class="space-y-3 sm:space-y-4">
                                <div class="text-center py-6 sm:py-8 text-gray-500 loading-state">
                                    <i class="fas fa-spinner fa-spin text-blue-500 text-lg sm:text-xl mr-2"></i>
                                    <span class="text-sm sm:text-base text-gray-600">Memuat pertanyaan...</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Progress Indicator -->
                <div class="mb-6 bg-blue-50 p-3 sm:p-4 rounded-lg">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm font-medium text-blue-800">Progress Pengerjaan</span>
                        <span id="progress-text" class="text-sm font-bold text-blue-800">
                            0/{{ count($kategories) }} Kategori
                        </span>
                    </div>
                    <div class="w-full bg-blue-200 rounded-full h-2.5">
                        <div id="progress-bar" class="bg-blue-600 h-2.5 rounded-full transition-all duration-300"
                            style="width: 0%"></div>
                    </div>
                    <div class="mt-2 text-xs text-blue-600">
                        <span id="progress-detail">Semua kategori harus diselesaikan sebelum submit</span>
                    </div>
                </div>

                <!-- Signature Section -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-3 flex items-center">
                        <i class="fas fa-signature mr-2 text-blue-500"></i>
                        Tanda Tangan Pengawas
                    </label>
                    <div class="bg-gray-50 p-3 sm:p-4 rounded-lg">
                        <div
                            class="signature-pad w-full h-32 sm:h-48 mb-3 border-2 border-dashed border-gray-300 rounded-lg">
                        </div>
                        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center space-y-3 sm:space-y-0">
                            <div class="flex gap-2">
                                <button type="button" id="clearSignature"
                                    class="px-3 sm:px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 transition duration-200 flex items-center btn-spacing text-sm">
                                    <i class="fas fa-trash-alt mr-1 sm:mr-2"></i>
                                    Hapus
                                </button>
                                <button type="button" id="saveSignature"
                                    class="px-3 sm:px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 transition duration-200 flex items-center text-sm">
                                    <i class="fas fa-check mr-1 sm:mr-2"></i>
                                    Simpan Tanda Tangan
                                </button>
                            </div>
                            <span id="signatureStatus" class="text-sm text-gray-500">Belum tersimpan</span>
                        </div>
                    </div>
                    <input type="hidden" name="tanda_tangan" id="tandaTangan"
                        value="{{ $editMode ? $inspeksi->tanda_tangan : '' }}">
                    @error('tanda_tangan')
                        <p class="mt-1 text-sm text-red-600 flex items-center">
                            <i class="fas fa-exclamation-circle mr-1"></i>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Confirmation Checkbox -->
                <div class="bg-gray-50 p-3 sm:p-4 rounded-lg mb-6">
                    <div class="flex items-start">
                        <input id="confirm-checkbox" type="checkbox"
                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded mt-1 flex-shrink-0">
                        <label for="confirm-checkbox" class="ml-2 block text-sm text-gray-900 flex items-center">
                            <i class="fas fa-shield-alt mr-2 text-blue-500"></i>
                            Saya menyatakan bahwa data yang diisi adalah benar dan dapat dipertanggungjawabkan
                        </label>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div
                    class="flex flex-col-reverse sm:flex-row sm:justify-between sm:items-center pt-6 border-t space-y-4 sm:space-y-0 space-y-reverse">
                    <a href="{{ route('pengawas.laporan') }}"
                        class="w-full sm:w-auto px-4 sm:px-6 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 transition duration-200 flex items-center justify-center text-sm sm:text-base">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Kembali ke Laporan
                    </a>

                    <button type="submit" id="submitButton"
                        class="w-full sm:w-auto px-4 sm:px-6 py-2 sm:py-3 bg-gray-400 text-white rounded-md transition duration-200 flex items-center justify-center cursor-not-allowed text-sm sm:text-base">
                        @if ($editMode)
                            <i class="fas fa-save mr-2"></i>
                            Update Inspeksi
                        @else
                            <i class="fas fa-paper-plane mr-2"></i>
                            Submit Inspeksi
                        @endif
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // State management
            const state = {
                jawaban: {},
                kategoriStatus: {},
                currentTab: null,
                editMode: {{ $editMode ? 'true' : 'false' }},
                existingAnswers: @json($jawabanFormatted ?? [])
            };

            // Initialize state for each category
            @foreach ($kategories as $kategori)
                state.jawaban[{{ $kategori->id }}] = {};
                state.kategoriStatus[{{ $kategori->id }}] = false;

                // Load existing answers if in edit mode
                if (state.editMode && state.existingAnswers[{{ $kategori->id }}]) {
                    state.jawaban[{{ $kategori->id }}] = {
                        ...state.existingAnswers[{{ $kategori->id }}]
                    };
                }
            @endforeach

            // Canvas untuk Tanda Tangan
            const canvas = document.createElement('canvas');
            const signaturePad = document.querySelector('.signature-pad');

            // Set canvas size based on screen width
            const setCanvasSize = () => {
                const isMobile = window.innerWidth < 640;
                canvas.width = isMobile ? 400 : 800;
                canvas.height = isMobile ? 120 : 200;
                canvas.style.width = '100%';
                canvas.style.height = isMobile ? '120px' : '200px';
                canvas.style.backgroundColor = 'white';
            };

            setCanvasSize();
            signaturePad.appendChild(canvas);

            const ctx = canvas.getContext('2d');
            let drawing = false;

            // Setup canvas
            ctx.strokeStyle = '#000';
            ctx.lineWidth = 2;
            ctx.lineCap = 'round';

            // Load existing signature if in edit mode
            @if ($editMode)
                const existingSignature = document.getElementById('tandaTangan').value;
                if (existingSignature) {
                    const img = new Image();
                    img.onload = function() {
                        ctx.drawImage(img, 0, 0, canvas.width, canvas.height);
                        document.getElementById('signatureStatus').textContent = 'Tersimpan ✓';
                        document.getElementById('signatureStatus').className =
                            'text-sm text-green-600 font-medium';
                    };
                    img.src = existingSignature;
                }
            @endif

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

            // Handle window resize
            window.addEventListener('resize', setCanvasSize);

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
                    <div class="text-center py-6 text-red-500">
                        <i class="fas fa-exclamation-triangle text-2xl sm:text-3xl mb-2"></i>
                        <p class="text-base sm:text-lg font-medium">Terjadi kesalahan</p>
                        <p class="text-xs sm:text-sm">Gagal memuat pertanyaan. Silakan coba lagi.</p>
                    </div>
                `;
                    });
            }

            function renderPertanyaan(kategoriId, pertanyaans) {
                const pertanyaanSection = document.getElementById(`pertanyaan-section-${kategoriId}`);
                let html = '';

                if (pertanyaans.length > 0) {
                    pertanyaans.forEach((pertanyaan, index) => {
                        // Check if we have a saved answer (from edit mode)
                        const savedAnswer = state.jawaban[kategoriId] && state.jawaban[kategoriId][
                            pertanyaan.id
                        ];

                        html += `
                    <div class="question-card bg-white p-3 sm:p-4 border border-gray-200 rounded-lg">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <label class="block text-sm font-medium text-gray-700 mb-2 sm:mb-3">
                                    <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2 py-0.5 rounded mr-2">${index + 1}</span>
                                    ${pertanyaan.pertanyaan}
                                </label>
                                <div class="flex items-center space-x-4 sm:space-x-6">
                                    <label class="inline-flex items-center">
                                        <input type="radio" 
                                               name="jawaban[${kategoriId}][${pertanyaan.id}]" 
                                               value="Ya" 
                                               class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300"
                                               ${savedAnswer === 'Ya' ? 'checked' : ''}
                                               onchange="updateJawaban(${kategoriId}, ${pertanyaan.id}, 'Ya')">
                                        <span class="ml-2 text-gray-700 font-medium flex items-center text-sm sm:text-base">
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
                                        <span class="ml-2 text-gray-700 font-medium flex items-center text-sm sm:text-base">
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

                    // Auto-check category completion after rendering
                    setTimeout(() => {
                        checkCategoryCompletion(kategoriId);
                    }, 100);
                } else {
                    html = `
                <div class="text-center py-6 text-gray-500">
                    <i class="fas fa-clipboard-question text-gray-300 text-4xl sm:text-6xl mb-3"></i>
                    <p class="text-base sm:text-lg font-medium">Tidak ada pertanyaan tersedia</p>
                    <p class="text-xs sm:text-sm">Belum ada pertanyaan yang ditambahkan untuk kategori ini.</p>
                </div>
            `;
                }

                pertanyaanSection.innerHTML = html;
            }

            // Global function to update answers
            window.updateJawaban = function(kategoriId, pertanyaanId, jawaban) {
                if (!state.jawaban[kategoriId]) {
                    state.jawaban[kategoriId] = {};
                }
                state.jawaban[kategoriId][pertanyaanId] = jawaban;
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
                const isCompleted = answeredQuestions.size === totalQuestions && totalQuestions > 0;

                state.kategoriStatus[kategoriId] = isCompleted;

                // Update category status indicator
                const statusElement = document.getElementById(`status-${kategoriId}`);
                if (statusElement) {
                    if (isCompleted) {
                        statusElement.textContent = 'Selesai ✓';
                        statusElement.className =
                            'ml-1 sm:ml-2 px-1.5 sm:px-2 py-0.5 text-xs rounded-full bg-green-100 text-green-800';
                    } else {
                        statusElement.textContent = `${answeredQuestions.size}/${totalQuestions}`;
                        statusElement.className =
                            'ml-1 sm:ml-2 px-1.5 sm:px-2 py-0.5 text-xs rounded-full bg-yellow-100 text-yellow-800';
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

            // Check form validity
            function checkFormValidity() {
                const allCategoriesCompleted = Object.values(state.kategoriStatus).every(status => status);
                const signatureSaved = document.getElementById('tandaTangan').value !== '';
                const confirmChecked = document.getElementById('confirm-checkbox').checked;

                const submitButton = document.getElementById('submitButton');

                if (allCategoriesCompleted && signatureSaved && confirmChecked) {
                    submitButton.disabled = false;
                    submitButton.className =
                        'w-full sm:w-auto px-4 sm:px-6 py-2 sm:py-3 bg-green-500 text-white rounded-md hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-500 transition duration-200 flex items-center justify-center text-sm sm:text-base';
                } else {
                    submitButton.disabled = true;
                    submitButton.className =
                        'w-full sm:w-auto px-4 sm:px-6 py-2 sm:py-3 bg-gray-400 text-white rounded-md transition duration-200 flex items-center justify-center cursor-not-allowed text-sm sm:text-base';
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

                document.querySelector('.bg-white.rounded-lg.shadow-md').insertBefore(alertDiv, document
                    .querySelector('.bg-white.rounded-lg.shadow-md').firstChild);

                setTimeout(() => {
                    alertDiv.remove();
                }, 3000);
            }

            // Add event listeners for form validation
            document.getElementById('confirm-checkbox').addEventListener('change', checkFormValidity);

            // Auto-check confirm checkbox if in edit mode and all conditions are met
            if (state.editMode) {
                setTimeout(() => {
                    document.getElementById('confirm-checkbox').checked = true;
                    checkFormValidity();
                }, 500);
            }

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
                const originalText = submitButton.innerHTML;
                submitButton.innerHTML = `
                <i class="fas fa-spinner fa-spin mr-2"></i>
                ${state.editMode ? 'Memperbarui data...' : 'Menyimpan data...'}
            `;
                submitButton.disabled = true;

                // Re-enable after 3 seconds if still on page (as fallback)
                setTimeout(() => {
                    submitButton.innerHTML = originalText;
                    submitButton.disabled = false;
                    checkFormValidity();
                }, 3000);
            });

            // Initialize progress
            updateProgress();

            // Auto-check form validity after page load
            setTimeout(() => {
                checkFormValidity();
            }, 1000);
        });
    </script>
@endsection
