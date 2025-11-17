<!-- resources/views/admin/users.blade.php -->
@extends('layouts.admin')

@section('title', 'Manajemen Users')

@section('content')
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800">Manajemen Users</h1>
        <p class="text-gray-600 mt-2">Kelola semua pengguna sistem dengan mudah</p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm">Total User</p>
                    <p class="text-2xl font-bold mt-1">{{ $users->count() }}</p>
                </div>
                <div class="p-3 bg-blue-400 rounded-lg">
                    <i class="fas fa-users text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm">Admin</p>
                    <p class="text-2xl font-bold mt-1">{{ $users->where('role', 'admin')->count() }}</p>
                </div>
                <div class="p-3 bg-green-400 rounded-lg">
                    <i class="fas fa-crown text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-100 text-sm">Pengawas</p>
                    <p class="text-2xl font-bold mt-1">{{ $users->where('role', 'pengawas')->count() }}</p>
                </div>
                <div class="p-3 bg-purple-400 rounded-lg">
                    <i class="fas fa-user-check text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Buttons & Search -->
    <div class="bg-white rounded-lg md:rounded-xl shadow-md border border-gray-100 mb-6">
        <div class="px-4 md:px-6 py-4 border-b border-gray-200">
            <div class="flex flex-col space-y-3 md:space-y-0 md:flex-row md:items-center md:justify-between">
                <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                    <i class="fas fa-list mr-2 text-blue-600"></i>
                    Daftar Users ({{ $users->count() }})
                </h3>
                <div class="flex flex-col sm:flex-row gap-2">
                    <div class="relative flex-1">
                        <input type="text" placeholder="Cari user..."
                            class="w-full pl-9 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm">
                        <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    </div>
                    <a href="{{ route('admin.users.create') }}"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition duration-200 flex items-center justify-center whitespace-nowrap text-sm">
                        <i class="fas fa-plus mr-2"></i>
                        Tambah User
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Users Table -->
    <div class="bg-white rounded-xl shadow-md border border-gray-100">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <div class="flex items-center">
                                <i class="fas fa-id-card mr-2"></i>
                                NIP
                            </div>
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <div class="flex items-center">
                                <i class="fas fa-user mr-2"></i>
                                User Info
                            </div>
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <div class="flex items-center">
                                <i class="fas fa-briefcase mr-2"></i>
                                Jabatan & Unit
                            </div>
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <div class="flex items-center">
                                <i class="fas fa-user-tag mr-2"></i>
                                Role
                            </div>
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <div class="flex items-center">
                                <i class="fas fa-calendar mr-2"></i>
                                Bergabung
                            </div>
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <div class="flex items-center">
                                <i class="fas fa-cog mr-2"></i>
                                Aksi
                            </div>
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($users as $user)
                        <tr class="hover:bg-gray-50 transition duration-150">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">
                                    {{ $user->nip ?? 'N/A' }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <div
                                            class="h-10 w-10 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white font-semibold text-sm">
                                            {{ substr($user->nama ?? ($user->name ?? 'U'), 0, 1) }}
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $user->nama ?? ($user->name ?? 'N/A') }}
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            {{ $user->email ?? 'N/A' }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900 font-medium">
                                    {{ $user->jabatan ?? 'Tidak ada jabatan' }}
                                </div>
                                <div class="text-sm text-gray-500">
                                    {{ $user->unit_kerja ?? 'Tidak ada unit' }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $roleColors = [
                                        'admin' => 'bg-red-100 text-red-800 border-red-200',
                                        'pengawas' => 'bg-green-100 text-green-800 border-green-200',
                                        'user' => 'bg-blue-100 text-blue-800 border-blue-200',
                                    ];
                                    $roleColor =
                                        $roleColors[$user->role ?? 'user'] ??
                                        'bg-gray-100 text-gray-800 border-gray-200';
                                    $roleIcons = [
                                        'admin' => 'fa-crown',
                                        'pengawas' => 'fa-user-check',
                                        'user' => 'fa-user',
                                    ];
                                    $roleIcon = $roleIcons[$user->role ?? 'user'] ?? 'fa-user';
                                @endphp
                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium border {{ $roleColor }}">
                                    <i class="fas {{ $roleIcon }} mr-1"></i>
                                    {{ ucfirst($user->role ?? 'user') }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <div class="flex flex-col">
                                    <span>{{ $user->created_at ? $user->created_at->format('d M Y') : 'N/A' }}</span>
                                    <span class="text-xs text-gray-400">
                                        {{ $user->created_at ? $user->created_at->diffForHumans() : '' }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('admin.users.edit', $user->id) }}"
                                        class="text-blue-600 hover:text-blue-900 transition duration-150 p-2 rounded-lg hover:bg-blue-50"
                                        title="Edit User">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="{{ route('admin.users.show', $user->id) }}"
                                        class="text-green-600 hover:text-green-900 transition duration-150 p-2 rounded-lg hover:bg-green-50"
                                        title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @if ($user->id !== Auth::id())
                                        <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST"
                                            class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="text-red-600 hover:text-red-900 transition duration-150 p-2 rounded-lg hover:bg-red-50"
                                                title="Delete User"
                                                onclick="return confirm('Apakah Anda yakin ingin menghapus user ini?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <i class="fas fa-users text-4xl text-gray-300 mb-4"></i>
                                    <p class="text-gray-500 text-lg">Belum ada data users</p>
                                    <p class="text-gray-400 text-sm mt-2">Users yang terdaftar akan muncul di sini</p>
                                    <a href="{{ route('admin.users.create') }}"
                                        class="mt-4 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition duration-200 flex items-center">
                                        <i class="fas fa-plus mr-2"></i>
                                        Tambah User Pertama
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Simple Results Counter (Tanpa Pagination) -->
        @if ($users->count() > 0)
            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                <div class="text-sm text-gray-600">
                    Menampilkan <span class="font-medium">{{ $users->count() }}</span> user
                </div>
            </div>
        @endif
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Simple search functionality
            const searchInput = document.querySelector('input[type="text"]');
            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    const searchTerm = this.value.toLowerCase();
                    const rows = document.querySelectorAll('tbody tr');

                    rows.forEach(row => {
                        const text = row.textContent.toLowerCase();
                        if (text.includes(searchTerm)) {
                            row.style.display = '';
                        } else {
                            row.style.display = 'none';
                        }
                    });
                });
            }
        });
    </script>
@endsection
