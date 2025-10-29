<!-- resources/views/admin/dashboard.blade.php -->
@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')
    <div class="min-h-screen bg-gray-100">
        <!-- Sidebar -->
        <div
            class="fixed inset-y-0 left-0 w-64 bg-blue-800 transform transition-transform duration-200 ease-in-out -translate-x-full md:translate-x-0">
            <div class="flex items-center justify-center h-16 bg-blue-900">
                <span class="text-white text-lg font-semibold">Dashboard Admin</span>
            </div>
            <nav class="mt-5">
                <a href="{{ route('admin.dashboard') }}"
                    class="block py-2.5 px-4 rounded transition duration-200 hover:bg-blue-700 text-white bg-blue-700">
                    Dashboard
                </a>
                <a href="{{ route('admin.users') }}"
                    class="block py-2.5 px-4 rounded transition duration-200 hover:bg-blue-700 text-white">
                    User
                </a>
                <a href="{{ route('admin.laporan') }}"
                    class="block py-2.5 px-4 rounded transition duration-200 hover:bg-blue-700 text-white">
                    Laporan
                </a>
                <form method="POST" action="{{ route('logout') }}"
                    class="block py-2.5 px-4 rounded transition duration-200 hover:bg-blue-700 text-white">
                    @csrf
                    <button type="submit">Logout</button>
                </form>
            </nav>
        </div>

        <!-- Main content -->
        <div class="md:ml-64">
            <!-- Header -->
            <div class="bg-white shadow">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between items-center py-4">
                        <h1 class="text-2xl font-bold text-gray-900">Dashboard Admin</h1>
                        <div class="text-sm text-gray-500">
                            Selamat datang, {{ Auth::user()->name }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="p-6">
                <!-- Statistik -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex items-center">
                            <div class="p-3 bg-blue-500 rounded-lg">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z">
                                    </path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600">Total Users</p>
                                <p class="text-2xl font-semibold text-gray-900">{{ $totalUsers }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex items-center">
                            <div class="p-3 bg-green-500 rounded-lg">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                                    </path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600">Total Absensi</p>
                                <p class="text-2xl font-semibold text-gray-900">{{ $totalAbsensi }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex items-center">
                            <div class="p-3 bg-yellow-500 rounded-lg">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600">Total Inspeksi</p>
                                <p class="text-2xl font-semibold text-gray-900">{{ $totalInspeksi }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Aktivitas Terbaru -->
                <div class="bg-white rounded-lg shadow">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-800">Aktivitas Terbaru</h2>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            @forelse($aktivitasTerbaru as $aktivitas)
                                <div class="flex items-start space-x-4 p-4 border border-gray-200 rounded-lg">
                                    <div class="flex-shrink-0">
                                        @if (isset($aktivitas->nip))
                                            <!-- Ini adalah absensi -->
                                            <div class="p-2 bg-blue-100 rounded-lg">
                                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                                                    </path>
                                                </svg>
                                            </div>
                                        @else
                                            <!-- Ini adalah inspeksi -->
                                            <div class="p-2 bg-green-100 rounded-lg">
                                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex-1">
                                        @if (isset($aktivitas->nip))
                                            <!-- Tampilan untuk Absensi -->
                                            <p class="text-sm font-medium text-gray-900">
                                                Absensi dari {{ $aktivitas->nama ?? 'Unknown User' }}
                                            </p>
                                            <p class="text-sm text-gray-500">
                                                NIP: {{ $aktivitas->nip ?? 'N/A' }} •
                                                {{ $aktivitas->created_at->diffForHumans() }}
                                            </p>
                                            <p class="text-xs text-gray-400 mt-1">
                                                Provinsi: {{ $aktivitas->provinsi ?? 'Tidak diketahui' }}
                                            </p>
                                        @else
                                            <!-- Tampilan untuk Inspeksi -->
                                            <p class="text-sm font-medium text-gray-900">
                                                Inspeksi oleh {{ $aktivitas->pengawas->name ?? 'Unknown Pengawas' }}
                                            </p>
                                            <p class="text-sm text-gray-500">
                                                Kategori: {{ $aktivitas->kategori->nama ?? 'Tidak ada kategori' }} •
                                                {{ $aktivitas->created_at->diffForHumans() }}
                                            </p>
                                            <p class="text-xs text-gray-400 mt-1">
                                                Total Jawaban: {{ $aktivitas->jawaban->count() ?? 0 }} pertanyaan
                                            </p>
                                        @endif
                                    </div>
                                    <div class="text-xs text-gray-400">
                                        @if (isset($aktivitas->nip))
                                            <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded">Absensi</span>
                                        @else
                                            <span class="px-2 py-1 bg-green-100 text-green-800 rounded">Inspeksi</span>
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-8">
                                    <svg class="w-12 h-12 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                        </path>
                                    </svg>
                                    <p class="text-gray-500">Belum ada aktivitas terbaru.</p>
                                    <p class="text-sm text-gray-400 mt-1">Aktivitas akan muncul di sini setelah ada absensi
                                        atau inspeksi.</p>
                                </div>
                            @endforelse
                        </div>

                        <!-- Tombol Lihat Semua Aktivitas -->
                        @if (count($aktivitasTerbaru) > 0)
                            <div class="mt-6 text-center">
                                <a href="{{ route('admin.laporan') }}"
                                    class="inline-flex items-center px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 transition duration-200">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5l7 7-7 7"></path>
                                    </svg>
                                    Lihat Semua Aktivitas
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
