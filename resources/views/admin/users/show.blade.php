@extends('layouts.admin')

@section('title', 'Detail User')

@section('content')
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <a href="{{ route('admin.users') }}" class="text-blue-600 hover:text-blue-800 mr-4">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <div>
                    <h1 class="text-3xl font-bold text-gray-800 flex items-center">
                        <i class="fas fa-user mr-3 text-blue-600"></i>
                        Detail User
                    </h1>
                    <p class="text-gray-600 mt-1">Informasi lengkap pengguna</p>
                </div>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('admin.users.edit', $userDetail->id) }}"
                    class="px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition duration-200">
                    <i class="fas fa-edit mr-2"></i>
                    Edit
                </a>
                @if ($userDetail->id !== Auth::id())
                    <form action="{{ route('admin.users.destroy', $userDetail->id) }}" method="POST" class="inline"
                        id="delete-form">
                        @csrf
                        @method('DELETE')
                        <button type="button"
                            class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition duration-200"
                            onclick="confirmDelete()">
                            <i class="fas fa-trash mr-2"></i>
                            Hapus
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>

    <!-- User Info -->
    <div class="bg-white rounded-xl shadow-md border border-gray-100 overflow-hidden">
        <!-- Profile Header -->
        <div class="bg-gradient-to-r from-blue-500 to-blue-600 px-6 py-8">
            <div class="flex items-center">
                <div class="h-20 w-20 bg-white rounded-full flex items-center justify-center shadow-lg">
                    <span class="text-blue-600 font-bold text-2xl">
                        {{ strtoupper(substr($userDetail->name, 0, 1)) }}
                    </span>
                </div>
                <div class="ml-6">
                    <h2 class="text-2xl font-bold text-white">{{ $userDetail->name }}</h2>
                    <p class="text-blue-100">{{ $userDetail->email }}</p>
                    <span class="inline-block mt-2 px-3 py-1 bg-white text-blue-600 rounded-full text-sm font-semibold">
                        {{ ucfirst($userDetail->role) }}
                    </span>
                </div>
            </div>
        </div>

        <!-- User Details -->
        <div class="px-6 py-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Personal Information -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-id-card mr-2 text-blue-500"></i>
                        Informasi Pribadi
                    </h3>
                    <div class="space-y-3">
                        <div>
                            <label class="text-sm font-medium text-gray-600">NIP</label>
                            <p class="text-gray-900">{{ $userDetail->nip ?? 'Tidak ada' }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-600">Nama Lengkap</label>
                            <p class="text-gray-900">{{ $userDetail->name }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-600">Email</label>
                            <p class="text-gray-900">{{ $userDetail->email }}</p>
                        </div>
                    </div>
                </div>

                <!-- Work Information -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-briefcase mr-2 text-green-500"></i>
                        Informasi Pekerjaan
                    </h3>
                    <div class="space-y-3">
                        <div>
                            <label class="text-sm font-medium text-gray-600">Jabatan</label>
                            <p class="text-gray-900">{{ $userDetail->jabatan ?? 'Tidak ada' }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-600">Unit Kerja</label>
                            <p class="text-gray-900">{{ $userDetail->unit_kerja ?? 'Tidak ada' }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-600">Provinsi</label>
                            <p class="text-gray-900">{{ $userDetail->provinsi ?? 'Tidak ada' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Account Information -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-user-cog mr-2 text-purple-500"></i>
                        Informasi Akun
                    </h3>
                    <div class="space-y-3">
                        <div>
                            <label class="text-sm font-medium text-gray-600">Role</label>
                            <span
                                class="inline-block px-3 py-1 rounded-full text-sm font-semibold 
                                {{ $userDetail->role === 'admin' ? 'bg-red-100 text-red-800' : 'bg-blue-100 text-blue-800' }}">
                                {{ ucfirst($userDetail->role) }}
                            </span>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-600">Tanggal Dibuat</label>
                            <p class="text-gray-900">{{ $userDetail->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-600">Terakhir Diupdate</label>
                            <p class="text-gray-900">{{ $userDetail->updated_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Statistics -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-chart-bar mr-2 text-orange-500"></i>
                        Statistik
                    </h3>
                    <div class="space-y-3">
                        <div>
                            <label class="text-sm font-medium text-gray-600">Total Inspeksi</label>
                            <p class="text-2xl font-bold text-gray-900">{{ $inspeksiCount }}</p>
                        </div>
                        @if ($userDetail->role === 'pengawas')
                            <div class="bg-blue-50 p-4 rounded-lg">
                                <p class="text-sm text-blue-700">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    User ini adalah pengawas dengan akses form inspeksi dan laporan.
                                </p>
                            </div>
                        @else
                            <div class="bg-green-50 p-4 rounded-lg">
                                <p class="text-sm text-green-700">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    User ini adalah admin dengan akses penuh ke sistem.
                                </p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        function confirmDelete() {
            if (confirm('Apakah Anda yakin ingin menghapus user {{ $userDetail->name }}?')) {
                document.getElementById('delete-form').submit();
            }
        }

        // Success message handling
        @if (session('success'))
            showNotification('{{ session('success') }}', 'success');
        @endif

        @if (session('error'))
            showNotification('{{ session('error') }}', 'error');
        @endif

        function showNotification(message, type) {
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 p-4 rounded-lg shadow-lg text-white z-50 ${
                type === 'success' ? 'bg-green-500' : 'bg-red-500'
            }`;
            notification.innerHTML = `
                <div class="flex items-center">
                    <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'} mr-2"></i>
                    <span>${message}</span>
                </div>
            `;
            document.body.appendChild(notification);

            setTimeout(() => {
                notification.remove();
            }, 5000);
        }
    </script>
@endsection
