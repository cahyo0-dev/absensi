@extends('layouts.app')

@section('title', 'Edit Pertanyaan - ' . $kategori->nama)

@section('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.14.0/Sortable.min.css">
    <style>
        .pertanyaan-list {
            min-height: 200px;
        }

        .pertanyaan-item {
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 0.5rem;
            padding: 1rem;
            margin-bottom: 0.75rem;
            cursor: move;
            transition: all 0.3s ease;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
        }

        .pertanyaan-item:hover {
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            border-color: #3b82f6;
        }

        .pertanyaan-item.sortable-ghost {
            opacity: 0.4;
            background: #e2e8f0;
        }

        .pertanyaan-item.sortable-chosen {
            background: #dbeafe;
        }

        .pertanyaan-number {
            background: #3b82f6;
            color: white;
            width: 2rem;
            height: 2rem;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 0.875rem;
            margin-right: 1rem;
            flex-shrink: 0;
        }

        .pertanyaan-content {
            flex: 1;
        }

        .pertanyaan-actions {
            display: flex;
            gap: 0.5rem;
            flex-shrink: 0;
        }

        .empty-state {
            text-align: center;
            padding: 3rem 2rem;
            color: #6b7280;
        }

        .empty-state i {
            font-size: 4rem;
            margin-bottom: 1rem;
            color: #d1d5db;
        }

        .drag-handle {
            cursor: grab;
            color: #6b7280;
            padding: 0.5rem;
            margin-right: 0.5rem;
        }

        .drag-handle:active {
            cursor: grabbing;
        }
    </style>
@endsection

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                <div class="flex-1 min-w-0">
                    <h1 class="text-2xl font-bold text-gray-900 flex items-center">
                        <i class="fas fa-edit mr-3 text-yellow-500"></i>
                        Edit Pertanyaan - {{ $kategori->nama }}
                    </h1>
                    <p class="text-gray-600 mt-2">
                        Kelola daftar pertanyaan untuk kategori inspeksi ini. Anda dapat menambah, mengedit, menghapus, dan
                        mengurutkan pertanyaan.
                    </p>
                </div>
                <div class="mt-4 sm:mt-0">
                    <a href="{{ route('pengawas.inspeksi') }}"
                        class="inline-flex items-center px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 transition duration-200">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Kembali ke Inspeksi
                    </a>
                </div>
            </div>
        </div>

        <!-- Form Tambah Pertanyaan Baru -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-plus-circle mr-2 text-green-500"></i>
                Tambah Pertanyaan Baru
            </h2>
            <form id="formTambahPertanyaan" class="space-y-4">
                @csrf
                <input type="hidden" name="kategori_id" value="{{ $kategori->id }}">

                <div>
                    <label for="pertanyaan" class="block text-sm font-medium text-gray-700 mb-2">
                        Pertanyaan Inspeksi
                    </label>
                    <textarea id="pertanyaan" name="pertanyaan" rows="3"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                        placeholder="Masukkan pertanyaan inspeksi baru..." required></textarea>
                    <p class="mt-1 text-sm text-gray-500">
                        Maksimal 500 karakter
                    </p>
                </div>

                <div class="flex justify-end">
                    <button type="submit"
                        class="inline-flex items-center px-4 py-2 bg-green-500 text-white rounded-md hover:bg-green-600 transition duration-200">
                        <i class="fas fa-save mr-2"></i>
                        Simpan Pertanyaan
                    </button>
                </div>
            </form>
        </div>

        <!-- Daftar Pertanyaan -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-gray-800 flex items-center">
                    <i class="fas fa-list-ol mr-2 text-blue-500"></i>
                    Daftar Pertanyaan
                    <span class="ml-2 bg-blue-100 text-blue-800 text-sm font-medium px-2 py-1 rounded">
                        {{ $pertanyaans->count() }} Pertanyaan
                    </span>
                </h2>

                <div class="flex items-center text-sm text-gray-500">
                    <i class="fas fa-info-circle mr-2"></i>
                    Drag and drop untuk mengubah urutan
                </div>
            </div>

            <div id="pertanyaanList" class="pertanyaan-list">
                @if ($pertanyaans->count() > 0)
                    @foreach ($pertanyaans as $pertanyaan)
                        <div class="pertanyaan-item flex items-start" data-id="{{ $pertanyaan->id }}">
                            <div class="drag-handle flex items-center h-12">
                                <i class="fas fa-grip-vertical text-gray-400"></i>
                            </div>
                            <div class="pertanyaan-number">{{ $loop->iteration }}</div>
                            <div class="pertanyaan-content">
                                <div class="pertanyaan-text text-gray-800">{{ $pertanyaan->pertanyaan }}</div>
                                <div class="mt-2 text-xs text-gray-500">
                                    ID: {{ $pertanyaan->id }} • Urutan: {{ $pertanyaan->urutan }}
                                </div>
                            </div>
                            <div class="pertanyaan-actions">
                                <button type="button"
                                    class="edit-btn inline-flex items-center px-3 py-1 bg-yellow-500 text-white rounded-md hover:bg-yellow-600 transition duration-200 text-sm"
                                    data-id="{{ $pertanyaan->id }}" data-pertanyaan="{{ $pertanyaan->pertanyaan }}">
                                    <i class="fas fa-edit mr-1"></i>
                                    Edit
                                </button>
                                <button type="button"
                                    class="delete-btn inline-flex items-center px-3 py-1 bg-red-500 text-white rounded-md hover:bg-red-600 transition duration-200 text-sm"
                                    data-id="{{ $pertanyaan->id }}">
                                    <i class="fas fa-trash mr-1"></i>
                                    Hapus
                                </button>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="empty-state">
                        <i class="fas fa-clipboard-question"></i>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada pertanyaan</h3>
                        <p class="text-gray-500">Mulai dengan menambahkan pertanyaan pertama menggunakan form di atas.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Modal Edit Pertanyaan -->
    <div id="editModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center hidden z-50">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-md mx-4">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-edit mr-2 text-yellow-500"></i>
                    Edit Pertanyaan
                </h3>

                <form id="formEditPertanyaan">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="edit_id" name="id">

                    <div class="mb-4">
                        <label for="edit_pertanyaan" class="block text-sm font-medium text-gray-700 mb-2">
                            Pertanyaan
                        </label>
                        <textarea id="edit_pertanyaan" name="pertanyaan" rows="3"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                            required></textarea>
                    </div>

                    <div class="flex justify-end space-x-3">
                        <button type="button" id="cancelEdit"
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition duration-200">
                            Batal
                        </button>
                        <button type="submit"
                            class="inline-flex items-center px-4 py-2 bg-yellow-500 text-white rounded-md hover:bg-yellow-600 transition duration-200">
                            <i class="fas fa-save mr-2"></i>
                            Update Pertanyaan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.14.0/Sortable.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Sortable
            const pertanyaanList = document.getElementById('pertanyaanList');
            if (pertanyaanList) {
                new Sortable(pertanyaanList, {
                    animation: 150,
                    ghostClass: 'sortable-ghost',
                    chosenClass: 'sortable-chosen',
                    handle: '.drag-handle',
                    onEnd: function(evt) {
                        updatePertanyaanOrder();
                    }
                });
            }

            // Form Tambah Pertanyaan
            const formTambah = document.getElementById('formTambahPertanyaan');
            formTambah.addEventListener('submit', function(e) {
                e.preventDefault();

                const formData = new FormData(this);
                const pertanyaanInput = document.getElementById('pertanyaan');

                fetch('{{ route('pertanyaan.store', $kategori->id) }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            showNotification('Pertanyaan berhasil ditambahkan', 'success');
                            pertanyaanInput.value = '';
                            location.reload(); // Reload untuk menampilkan pertanyaan baru
                        } else {
                            showNotification(data.message || 'Gagal menambahkan pertanyaan', 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showNotification('Terjadi kesalahan saat menambahkan pertanyaan', 'error');
                    });
            });

            // Modal Edit
            const editModal = document.getElementById('editModal');
            const editButtons = document.querySelectorAll('.edit-btn');
            const cancelEdit = document.getElementById('cancelEdit');
            const formEdit = document.getElementById('formEditPertanyaan');

            editButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const id = this.getAttribute('data-id');
                    const pertanyaan = this.getAttribute('data-pertanyaan');

                    document.getElementById('edit_id').value = id;
                    document.getElementById('edit_pertanyaan').value = pertanyaan;

                    editModal.classList.remove('hidden');
                });
            });

            cancelEdit.addEventListener('click', function() {
                editModal.classList.add('hidden');
            });

            // Form Edit Pertanyaan
            formEdit.addEventListener('submit', function(e) {
                e.preventDefault();

                const id = document.getElementById('edit_id').value;
                const formData = new FormData(this);

                fetch(`/pertanyaan/${id}`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'X-HTTP-Method-Override': 'PUT',
                            'Accept': 'application/json'
                        },
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            showNotification('Pertanyaan berhasil diperbarui', 'success');
                            editModal.classList.add('hidden');
                            location.reload();
                        } else {
                            showNotification(data.message || 'Gagal memperbarui pertanyaan', 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showNotification('Terjadi kesalahan saat memperbarui pertanyaan', 'error');
                    });
            });

            // Hapus Pertanyaan
            const deleteButtons = document.querySelectorAll('.delete-btn');
            deleteButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const id = this.getAttribute('data-id');

                    if (confirm('Apakah Anda yakin ingin menghapus pertanyaan ini?')) {
                        fetch(`/pertanyaan/${id}`, {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Accept': 'application/json'
                                }
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    showNotification('Pertanyaan berhasil dihapus', 'success');
                                    location.reload();
                                } else {
                                    showNotification(data.message ||
                                        'Gagal menghapus pertanyaan', 'error');
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                showNotification('Terjadi kesalahan saat menghapus pertanyaan',
                                    'error');
                            });
                    }
                });
            });

            // Update urutan pertanyaan
            function updatePertanyaanOrder() {
                const pertanyaanItems = pertanyaanList.querySelectorAll('.pertanyaan-item');
                const pertanyaanIds = Array.from(pertanyaanItems).map(item => item.getAttribute('data-id'));

                fetch('{{ route('pertanyaan.reorder', $kategori->id) }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            pertanyaans: pertanyaanIds
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Update nomor urutan yang ditampilkan
                            pertanyaanItems.forEach((item, index) => {
                                const numberElement = item.querySelector('.pertanyaan-number');
                                if (numberElement) {
                                    numberElement.textContent = index + 1;
                                }
                            });
                            showNotification('Urutan pertanyaan berhasil diperbarui', 'success');
                        } else {
                            showNotification('Gagal memperbarui urutan pertanyaan', 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showNotification('Terjadi kesalahan saat memperbarui urutan', 'error');
                    });
            }

            // Notification function
            function showNotification(message, type) {
                const colors = {
                    success: 'green',
                    error: 'red',
                    warning: 'yellow',
                    info: 'blue'
                };

                const icons = {
                    success: 'fa-check-circle',
                    error: 'fa-exclamation-circle',
                    warning: 'fa-exclamation-triangle',
                    info: 'fa-info-circle'
                };

                const notification = document.createElement('div');
                notification.className =
                    `fixed top-4 right-4 bg-${colors[type]}-100 border border-${colors[type]}-400 text-${colors[type]}-700 px-6 py-4 rounded shadow-lg z-50 max-w-sm`;
                notification.innerHTML = `
                    <div class="flex items-center">
                        <i class="fas ${icons[type]} mr-3"></i>
                        <span class="font-medium">${message}</span>
                    </div>
                `;

                document.body.appendChild(notification);

                setTimeout(() => {
                    notification.remove();
                }, 4000);
            }
        });
    </script>
@endsection
