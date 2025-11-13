@extends('layouts.admin')

@section('title', 'Tambah User Baru')

@section('content')
    <div class="mb-8">
        <div class="flex items-center">
            <a href="{{ route('admin.users') }}" class="text-blue-600 hover:text-blue-800 mr-4">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div>
                <h1 class="text-3xl font-bold text-gray-800 flex items-center">
                    <i class="fas fa-user-plus mr-3 text-green-600"></i>
                    Tambah User Baru
                </h1>
                <p class="text-gray-600 mt-2">Buat akun pengguna baru</p>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-md border border-gray-100 p-6">
        <form action="{{ route('admin.users.store') }}" method="POST">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- NIP -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        NIP *
                    </label>
                    <input type="text" name="nip" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        value="{{ old('nip') }}"
                        placeholder="Masukkan NIP">
                    @error('nip')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Nama -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Nama Lengkap *
                    </label>
                    <input type="text" name="name" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        value="{{ old('name') }}"
                        placeholder="Masukkan nama lengkap">
                    @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Jabatan -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Jabatan *
                    </label>
                    <input type="text" name="jabatan" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        value="{{ old('jabatan') }}"
                        placeholder="Masukkan jabatan">
                    @error('jabatan')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Unit Kerja -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Unit Kerja *
                    </label>
                    <input type="text" name="unit_kerja" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        value="{{ old('unit_kerja') }}"
                        placeholder="Masukkan unit kerja">
                    @error('unit_kerja')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Provinsi -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Provinsi *
                    </label>
                    <select name="provinsi" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Pilih Provinsi</option>
                        <option value="DKI Jakarta" {{ old('provinsi') == 'DKI Jakarta' ? 'selected' : '' }}>DKI Jakarta</option>
                        <option value="Jawa Barat" {{ old('provinsi') == 'Jawa Barat' ? 'selected' : '' }}>Jawa Barat</option>
                        <option value="Jawa Tengah" {{ old('provinsi') == 'Jawa Tengah' ? 'selected' : '' }}>Jawa Tengah</option>
                        <option value="Jawa Timur" {{ old('provinsi') == 'Jawa Timur' ? 'selected' : '' }}>Jawa Timur</option>
                    </select>
                    @error('provinsi')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Role -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Role *
                    </label>
                    <select name="role" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Pilih Role</option>
                        <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="pengawas" {{ old('role') == 'pengawas' ? 'selected' : '' }}>Pengawas</option>
                    </select>
                    @error('role')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Email *
                    </label>
                    <input type="email" name="email" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        value="{{ old('email') }}"
                        placeholder="Masukkan email">
                    @error('email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Password *
                    </label>
                    <input type="password" name="password" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        placeholder="Masukkan password">
                    @error('password')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Konfirmasi Password *
                    </label>
                    <input type="password" name="password_confirmation" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        placeholder="Konfirmasi password">
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
                <a href="{{ route('admin.users') }}" 
                   class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition duration-200">
                    Batal
                </a>
                <button type="submit" 
                        class="px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition duration-200 font-medium">
                    <i class="fas fa-save mr-2"></i>
                    Simpan User
                </button>
            </div>
        </form>
    </div>
@endsection