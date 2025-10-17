<!-- resources/views/absensi/index.blade.php -->
@extends('layouts.app')

@section('title', 'Absensi')

@section('styles')
    <style>
        .signature-pad {
            border: 1px solid #e2e8f0;
            border-radius: 0.375rem;
            background-color: white;
        }
    </style>
@endsection

@section('content')
    <div class="min-h-screen bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-3xl mx-auto">
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-6 text-center">Form Absensi</h2>

                <form id="absensiForm" method="POST" action="{{ route('absensi.store') }}">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">NIP</label>
                            <input type="text" name="nip" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nama</label>
                            <input type="text" name="nama" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Jabatan</label>
                            <input type="text" name="jabatan" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Unit Kerja</label>
                            <input type="text" name="unit_kerja" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Provinsi</label>
                            <select name="provinsi" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Pilih Provinsi</option>
                                <option value="Aceh">Aceh</option>
                                <option value="Sumatera Utara">Sumatera Utara</option>
                                <!-- Tambahkan provinsi lainnya -->
                            </select>
                        </div>
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tanda Tangan</label>
                        <div class="signature-pad w-full h-48 mb-2"></div>
                        <input type="hidden" name="tanda_tangan" id="tandaTangan">
                        <div class="flex gap-2">
                            <button type="button" id="clearSignature"
                                class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600">
                                Hapus
                            </button>
                            <button type="button" id="saveSignature"
                                class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">
                                Simpan Tanda Tangan
                            </button>
                        </div>
                    </div>

                    <button type="submit"
                        class="w-full bg-green-500 text-white py-3 px-4 rounded-md hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-500">
                        Submit Absensi
                    </button>
                </form>
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
            });

            // Save signature
            document.getElementById('saveSignature').addEventListener('click', function() {
                const dataURL = canvas.toDataURL();
                document.getElementById('tandaTangan').value = dataURL;
                alert('Tanda tangan berhasil disimpan!');
            });

            // Form submission
            document.getElementById('absensiForm').addEventListener('submit', function(e) {
                if (!document.getElementById('tandaTangan').value) {
                    e.preventDefault();
                    alert('Harap simpan tanda tangan terlebih dahulu!');
                }
            });
        });
    </script>
@endsection
