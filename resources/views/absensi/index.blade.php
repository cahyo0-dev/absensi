@extends('layouts.without-sidebar')

@section('title', 'Absensi')

@section('styles')
    <style>
        .signature-pad {
            border: 1px solid #e2e8f0;
            border-radius: 0.375rem;
            background-color: white;
        }

        .form-input {
            transition: all 0.3s ease;
        }

        .form-input:focus {
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }
    </style>
@endsection

@section('content')
    <div class="min-h-screen bg-gray-50 py-8 px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto">

            <!-- Alert Messages -->
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6 flex items-center">
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

            <!-- Header Card -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-8">
                <div class="text-center">
                    <h2 class="text-2xl font-bold text-gray-800 mb-2 flex items-center justify-center">
                        <i class="fas fa-fingerprint text-blue-500 mr-3"></i>
                        Form Absensi
                    </h2>
                    <p class="text-gray-600">Silakan isi data absensi dengan lengkap dan benar</p>

                    <!-- Navigation Buttons -->
                    <div class="mt-4 flex flex-wrap justify-center gap-3">
                        <a href="{{ route('sop.index') }}"
                            class="inline-flex items-center px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition duration-200 text-sm font-medium">
                            <i class="fas fa-book mr-2"></i>
                            Lihat SOP
                        </a>
                        @auth
                            @if (Auth::user()->isPengawas())
                                <a href="{{ route('pengawas.inspeksi') }}"
                                    class="inline-flex items-center px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition duration-200 text-sm font-medium">
                                    <i class="fas fa-clipboard-check mr-2"></i>
                                    Form Inspeksi
                                </a>
                            @endif
                            @if (Auth::user()->isAdmin())
                                <a href="{{ route('admin.dashboard') }}"
                                    class="inline-flex items-center px-4 py-2 bg-purple-500 text-white rounded-lg hover:bg-purple-600 transition duration-200 text-sm font-medium">
                                    <i class="fas fa-tachometer-alt mr-2"></i>
                                    Dashboard Admin
                                </a>
                            @endif
                        @else
                            <a href="{{ route('login') }}"
                                class="inline-flex items-center px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition duration-200 text-sm font-medium">
                                <i class="fas fa-sign-in-alt mr-2"></i>
                                Login
                            </a>
                            <a href="{{ route('register') }}"
                                class="inline-flex items-center px-4 py-2 bg-indigo-500 text-white rounded-lg hover:bg-indigo-600 transition duration-200 text-sm font-medium">
                                <i class="fas fa-user-plus mr-2"></i>
                                Daftar
                            </a>
                        @endauth
                    </div>
                </div>
            </div>

            <!-- Form Card -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <form id="absensiForm" method="POST" action="{{ route('absensi.store') }}">
                    @csrf

                    <!-- Grid Input Fields -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <!-- NIP -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                                <i class="fas fa-id-card mr-2 text-blue-500"></i>
                                NIP
                            </label>
                            <input type="text" name="nip" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 form-input"
                                placeholder="Masukkan NIP" value="{{ old('nip') }}">
                            @error('nip')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Nama -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                                <i class="fas fa-user mr-2 text-blue-500"></i>
                                Nama
                            </label>
                            <input type="text" name="nama" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 form-input"
                                placeholder="Masukkan Nama Lengkap" value="{{ old('nama') }}">
                            @error('nama')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Jabatan -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                                <i class="fas fa-briefcase mr-2 text-blue-500"></i>
                                Jabatan
                            </label>
                            <input type="text" name="jabatan" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 form-input"
                                placeholder="Masukkan Jabatan" value="{{ old('jabatan') }}">
                            @error('jabatan')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Unit Kerja -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                                <i class="fas fa-building mr-2 text-blue-500"></i>
                                Unit Kerja
                            </label>
                            <input type="text" name="unit_kerja" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 form-input"
                                placeholder="Masukkan Unit Kerja" value="{{ old('unit_kerja') }}">
                            @error('unit_kerja')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Provinsi -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                                <i class="fas fa-map-marker-alt mr-2 text-blue-500"></i>
                                Provinsi
                            </label>
                            <select name="provinsi" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 form-input">
                                <option value="">Pilih Provinsi</option>
                                <option value="Aceh" {{ old('provinsi') == 'Aceh' ? 'selected' : '' }}>Aceh</option>
                                <option value="Sumatera Utara" {{ old('provinsi') == 'Sumatera Utara' ? 'selected' : '' }}>
                                    Sumatera Utara</option>
                                <option value="Sumatera Barat" {{ old('provinsi') == 'Sumatera Barat' ? 'selected' : '' }}>
                                    Sumatera Barat</option>
                                <option value="Riau" {{ old('provinsi') == 'Riau' ? 'selected' : '' }}>Riau</option>
                                <option value="Jambi" {{ old('provinsi') == 'Jambi' ? 'selected' : '' }}>Jambi</option>
                                <option value="Sumatera Selatan"
                                    {{ old('provinsi') == 'Sumatera Selatan' ? 'selected' : '' }}>Sumatera Selatan</option>
                                <option value="Bengkulu" {{ old('provinsi') == 'Bengkulu' ? 'selected' : '' }}>Bengkulu
                                </option>
                                <option value="Lampung" {{ old('provinsi') == 'Lampung' ? 'selected' : '' }}>Lampung
                                </option>
                                <option value="Kepulauan Bangka Belitung"
                                    {{ old('provinsi') == 'Kepulauan Bangka Belitung' ? 'selected' : '' }}>Kepulauan Bangka
                                    Belitung</option>
                                <option value="Kepulauan Riau" {{ old('provinsi') == 'Kepulauan Riau' ? 'selected' : '' }}>
                                    Kepulauan Riau</option>
                                <option value="DKI Jakarta" {{ old('provinsi') == 'DKI Jakarta' ? 'selected' : '' }}>DKI
                                    Jakarta</option>
                                <option value="Jawa Barat" {{ old('provinsi') == 'Jawa Barat' ? 'selected' : '' }}>Jawa
                                    Barat</option>
                                <option value="Jawa Tengah" {{ old('provinsi') == 'Jawa Tengah' ? 'selected' : '' }}>Jawa
                                    Tengah</option>
                                <option value="DI Yogyakarta" {{ old('provinsi') == 'DI Yogyakarta' ? 'selected' : '' }}>DI
                                    Yogyakarta</option>
                                <option value="Jawa Timur" {{ old('provinsi') == 'Jawa Timur' ? 'selected' : '' }}>Jawa
                                    Timur</option>
                                <option value="Banten" {{ old('provinsi') == 'Banten' ? 'selected' : '' }}>Banten</option>
                                <option value="Bali" {{ old('provinsi') == 'Bali' ? 'selected' : '' }}>Bali</option>
                                <option value="Nusa Tenggara Barat"
                                    {{ old('provinsi') == 'Nusa Tenggara Barat' ? 'selected' : '' }}>Nusa Tenggara Barat
                                </option>
                                <option value="Nusa Tenggara Timur"
                                    {{ old('provinsi') == 'Nusa Tenggara Timur' ? 'selected' : '' }}>Nusa Tenggara Timur
                                </option>
                                <option value="Kalimantan Barat"
                                    {{ old('provinsi') == 'Kalimantan Barat' ? 'selected' : '' }}>Kalimantan Barat</option>
                                <option value="Kalimantan Tengah"
                                    {{ old('provinsi') == 'Kalimantan Tengah' ? 'selected' : '' }}>Kalimantan Tengah
                                </option>
                                <option value="Kalimantan Selatan"
                                    {{ old('provinsi') == 'Kalimantan Selatan' ? 'selected' : '' }}>Kalimantan Selatan
                                </option>
                                <option value="Kalimantan Timur"
                                    {{ old('provinsi') == 'Kalimantan Timur' ? 'selected' : '' }}>Kalimantan Timur</option>
                                <option value="Kalimantan Utara"
                                    {{ old('provinsi') == 'Kalimantan Utara' ? 'selected' : '' }}>Kalimantan Utara</option>
                                <option value="Sulawesi Utara" {{ old('provinsi') == 'Sulawesi Utara' ? 'selected' : '' }}>
                                    Sulawesi Utara</option>
                                <option value="Sulawesi Tengah"
                                    {{ old('provinsi') == 'Sulawesi Tengah' ? 'selected' : '' }}>Sulawesi Tengah</option>
                                <option value="Sulawesi Selatan"
                                    {{ old('provinsi') == 'Sulawesi Selatan' ? 'selected' : '' }}>Sulawesi Selatan</option>
                                <option value="Sulawesi Tenggara"
                                    {{ old('provinsi') == 'Sulawesi Tenggara' ? 'selected' : '' }}>Sulawesi Tenggara
                                </option>
                                <option value="Gorontalo" {{ old('provinsi') == 'Gorontalo' ? 'selected' : '' }}>Gorontalo
                                </option>
                                <option value="Sulawesi Barat" {{ old('provinsi') == 'Sulawesi Barat' ? 'selected' : '' }}>
                                    Sulawesi Barat</option>
                                <option value="Maluku" {{ old('provinsi') == 'Maluku' ? 'selected' : '' }}>Maluku</option>
                                <option value="Maluku Utara" {{ old('provinsi') == 'Maluku Utara' ? 'selected' : '' }}>
                                    Maluku Utara</option>
                                <option value="Papua Barat" {{ old('provinsi') == 'Papua Barat' ? 'selected' : '' }}>Papua
                                    Barat</option>
                                <option value="Papua" {{ old('provinsi') == 'Papua' ? 'selected' : '' }}>Papua</option>
                                <option value="Papua Tengah" {{ old('provinsi') == 'Papua Tengah' ? 'selected' : '' }}>
                                    Papua Tengah</option>
                                <option value="Papua Pegunungan"
                                    {{ old('provinsi') == 'Papua Pegunungan' ? 'selected' : '' }}>Papua Pegunungan</option>
                                <option value="Papua Selatan" {{ old('provinsi') == 'Papua Selatan' ? 'selected' : '' }}>
                                    Papua Selatan</option>
                            </select>
                            @error('provinsi')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Tanda Tangan Section -->
                    <div class="mb-8">
                        <label class="block text-sm font-medium text-gray-700 mb-4 flex items-center">
                            <i class="fas fa-signature mr-2 text-blue-500"></i>
                            Tanda Tangan
                        </label>

                        <div class="bg-gray-50 p-6 rounded-lg border-2 border-dashed border-gray-300">
                            <div class="signature-pad w-full h-48 mb-4 bg-white rounded-lg shadow-sm"></div>

                            <div class="flex flex-col sm:flex-row justify-between items-center space-y-3 sm:space-y-0">
                                <div class="flex gap-3">
                                    <button type="button" id="clearSignature"
                                        class="px-5 py-2.5 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition duration-200 flex items-center shadow-sm">
                                        <i class="fas fa-trash-alt mr-2"></i>
                                        Hapus
                                    </button>
                                    <button type="button" id="saveSignature"
                                        class="px-5 py-2.5 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition duration-200 flex items-center shadow-sm">
                                        <i class="fas fa-check mr-2"></i>
                                        Simpan Tanda Tangan
                                    </button>
                                </div>

                                <span id="signatureStatus" class="text-sm text-gray-500 font-medium">
                                    Tanda tangan belum disimpan
                                </span>
                            </div>
                        </div>

                        <input type="hidden" name="tanda_tangan" id="tandaTangan" value="{{ old('tanda_tangan') }}">
                        @error('tanda_tangan')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <button type="submit"
                        class="w-full bg-green-500 text-white py-4 px-6 rounded-lg hover:bg-green-600 focus:outline-none focus:ring-4 focus:ring-green-200 transition duration-200 font-semibold text-lg shadow-lg">
                        <i class="fas fa-paper-plane mr-2"></i>
                        Submit Absensi
                    </button>
                </form>
            </div>

            <!-- Quick Links -->
            <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-4">
                <a href="{{ route('sop.index') }}"
                    class="bg-white p-4 rounded-lg shadow-md hover:shadow-lg transition duration-200 border-l-4 border-blue-500">
                    <div class="flex items-center">
                        <i class="fas fa-book text-blue-500 text-xl mr-3"></i>
                        <div>
                            <h3 class="font-semibold text-gray-800">SOP</h3>
                            <p class="text-sm text-gray-600">Lihat panduan lengkap</p>
                        </div>
                    </div>
                </a>

                @auth
                    @if (Auth::user()->isPengawas())
                        <a href="{{ route('pengawas.inspeksi') }}"
                            class="bg-white p-4 rounded-lg shadow-md hover:shadow-lg transition duration-200 border-l-4 border-green-500">
                            <div class="flex items-center">
                                <i class="fas fa-clipboard-check text-green-500 text-xl mr-3"></i>
                                <div>
                                    <h3 class="font-semibold text-gray-800">Inspeksi</h3>
                                    <p class="text-sm text-gray-600">Form inspeksi harian</p>
                                </div>
                            </div>
                        </a>
                    @endif

                    @if (Auth::user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}"
                            class="bg-white p-4 rounded-lg shadow-md hover:shadow-lg transition duration-200 border-l-4 border-purple-500">
                            <div class="flex items-center">
                                <i class="fas fa-tachometer-alt text-purple-500 text-xl mr-3"></i>
                                <div>
                                    <h3 class="font-semibold text-gray-800">Dashboard</h3>
                                    <p class="text-sm text-gray-600">Panel administrasi</p>
                                </div>
                            </div>
                        </a>
                    @endif
                @else
                    <a href="{{ route('login') }}"
                        class="bg-white p-4 rounded-lg shadow-md hover:shadow-lg transition duration-200 border-l-4 border-indigo-500">
                        <div class="flex items-center">
                            <i class="fas fa-sign-in-alt text-indigo-500 text-xl mr-3"></i>
                            <div>
                                <h3 class="font-semibold text-gray-800">Login</h3>
                                <p class="text-sm text-gray-600">Akses sistem lengkap</p>
                            </div>
                        </div>
                    </a>
                    <a href="{{ route('register') }}"
                        class="bg-white p-4 rounded-lg shadow-md hover:shadow-lg transition duration-200 border-l-4 border-indigo-400">
                        <div class="flex items-center">
                            <i class="fas fa-user-plus text-indigo-400 text-xl mr-3"></i>
                            <div>
                                <h3 class="font-semibold text-gray-800">Daftar</h3>
                                <p class="text-sm text-gray-600">Buat akun baru</p>
                            </div>
                        </div>
                    </a>
                @endauth
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const canvas = document.createElement('canvas');
            canvas.width = 800;
            canvas.height = 200;
            canvas.style.width = '100%';
            canvas.style.height = '200px';
            canvas.style.border = '1px solid #e2e8f0';
            canvas.style.borderRadius = '0.375rem';
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
                document.getElementById('signatureStatus').textContent = 'Tanda tangan belum disimpan';
                document.getElementById('signatureStatus').className = 'text-sm text-gray-500 font-medium';
            });

            // Save signature
            document.getElementById('saveSignature').addEventListener('click', function() {
                const dataURL = canvas.toDataURL();
                document.getElementById('tandaTangan').value = dataURL;
                document.getElementById('signatureStatus').textContent = 'Tanda tangan tersimpan ✓';
                document.getElementById('signatureStatus').className = 'text-sm text-green-600 font-medium';
            });

            // Form submission
            document.getElementById('absensiForm').addEventListener('submit', function(e) {
                if (!document.getElementById('tandaTangan').value) {
                    e.preventDefault();
                    alert('Harap simpan tanda tangan terlebih dahulu!');
                }
            });

            // Load existing signature if any (from old input)
            const existingSignature = document.getElementById('tandaTangan').value;
            if (existingSignature) {
                const img = new Image();
                img.onload = function() {
                    ctx.drawImage(img, 0, 0, canvas.width, canvas.height);
                    document.getElementById('signatureStatus').textContent = 'Tanda tangan tersimpan ✓';
                    document.getElementById('signatureStatus').className = 'text-sm text-green-600 font-medium';
                };
                img.src = existingSignature;
            }
        });
    </script>
@endsection
