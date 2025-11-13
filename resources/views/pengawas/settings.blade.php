@extends('layouts.app')

@section('title', 'Pengaturan')

@section('content')
    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-md mx-auto px-4">
            <div class="bg-white rounded-lg shadow-md border border-gray-200 overflow-hidden">
                <!-- Header -->
                <div class="px-6 py-4 border-b border-gray-200 bg-white">
                    <h5 class="text-lg font-semibold text-gray-800 flex items-center">
                        <i class="fas fa-cog text-gray-600 mr-2"></i> Pengaturan
                    </h5>
                </div>

                <!-- Body -->
                <div class="p-6">
                    <!-- Alert Messages -->
                    @if (session('success'))
                        <div class="mb-4 p-3 bg-green-100 border border-green-400 text-green-700 rounded-lg">
                            {{ session('success') }}
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="mb-4 p-3 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                            {{ session('error') }}
                        </div>
                    @endif

                    <!-- Form -->
                    <form method="POST" action="{{ route('pengawas.settings.update-password') }}">
                        @csrf

                        <!-- Current Password -->
                        <div class="mb-4">
                            <label for="current_password" class="block text-sm font-medium text-gray-700 mb-1">
                                Password Saat Ini
                            </label>
                            <input type="password" id="current_password" name="current_password" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('current_password') border-red-500 @enderror">
                            @error('current_password')
                                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- New Password -->
                        <div class="mb-4">
                            <label for="new_password" class="block text-sm font-medium text-gray-700 mb-1">
                                Password Baru
                            </label>
                            <input type="password" id="new_password" name="new_password" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('new_password') border-red-500 @enderror">
                            @error('new_password')
                                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Confirm New Password -->
                        <div class="mb-6">
                            <label for="new_password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">
                                Konfirmasi Password Baru
                            </label>
                            <input type="password" id="new_password_confirmation" name="new_password_confirmation" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <!-- Buttons -->
                        <div class="space-y-3">
                            <button type="submit"
                                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition duration-200 ease-in-out flex items-center justify-center">
                                <i class="fas fa-save mr-2"></i> Ganti Password
                            </button>

                            <a href="{{ route('pengawas.dashboard') }}"
                                class="w-full bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-4 rounded-lg transition duration-200 ease-in-out flex items-center justify-center">
                                <i class="fas fa-arrow-left mr-2"></i> Kembali ke Dashboard
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
