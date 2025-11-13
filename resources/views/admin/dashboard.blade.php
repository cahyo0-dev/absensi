@extends('layouts.admin')

@section('title', 'Dashboard Admin')

@section('content')
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800">Dashboard</h1>
        <p class="text-gray-600 mt-2">Welcome back, {{ Auth::user()->nama ?? (Auth::user()->name ?? 'Admin') }}! Here's
            what's
            happening today.</p>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Total Users Card -->
        <div class="bg-white rounded-xl shadow-md border border-gray-100 p-6 hover:shadow-lg transition-shadow duration-200">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600 mr-4">
                    <i class="fas fa-users text-xl"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Users</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $totalUsers }}</p>
                </div>
            </div>
        </div>

        <!-- Total Absensi Card -->
        <div
            class="bg-white rounded-xl shadow-md border border-gray-100 p-6 hover:shadow-lg transition-shadow duration-200">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-600 mr-4">
                    <i class="fas fa-clipboard-check text-xl"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Absensi</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $totalAbsensi }}</p>
                </div>
            </div>
        </div>

        <!-- Total Inspeksi Card -->
        <div
            class="bg-white rounded-xl shadow-md border border-gray-100 p-6 hover:shadow-lg transition-shadow duration-200">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-purple-100 text-purple-600 mr-4">
                    <i class="fas fa-chart-bar text-xl"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Inspeksi</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $totalInspeksi }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Aktivitas Terbaru -->
    <div class="bg-white rounded-xl shadow-md border border-gray-100">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                <i class="fas fa-history mr-2 text-blue-600"></i>
                Aktivitas Terbaru
            </h3>
        </div>
        <div class="p-6">
            @forelse($aktivitasTerbaru as $aktivitas)
                <div
                    class="flex items-center space-x-4 p-3 rounded-lg hover:bg-gray-50 transition-colors duration-150 mb-3">
                    @if (isset($aktivitas->nip))
                        <!-- Absensi Activity -->
                        <div class="flex-shrink-0 w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-clipboard-check text-blue-600"></i>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-900">
                                Absensi dari {{ $aktivitas->nama ?? 'User' }}
                            </p>
                            <p class="text-sm text-gray-500">
                                NIP: {{ $aktivitas->nip ?? 'N/A' }} •
                                {{ $aktivitas->created_at ? $aktivitas->created_at->diffForHumans() : 'N/A' }}
                            </p>
                        </div>
                        <span class="px-3 py-1 text-xs font-medium bg-blue-100 text-blue-800 rounded-full">
                            Absensi
                        </span>
                    @else
                        <!-- Inspeksi Activity -->
                        <div class="flex-shrink-0 w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-chart-bar text-green-600"></i>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-900">
                                Inspeksi oleh {{ $aktivitas->pengawas->nama ?? ($aktivitas->pengawas->name ?? 'Pengawas') }}
                            </p>
                            <p class="text-sm text-gray-500">
                                Kategori: {{ $aktivitas->kategori->nama ?? 'Tidak ada kategori' }} •
                                {{ $aktivitas->created_at ? $aktivitas->created_at->diffForHumans() : 'N/A' }}
                            </p>
                        </div>
                        <span class="px-3 py-1 text-xs font-medium bg-green-100 text-green-800 rounded-full">
                            Inspeksi
                        </span>
                    @endif
                </div>
            @empty
                <div class="text-center py-8">
                    <i class="fas fa-inbox text-4xl text-gray-300 mb-4"></i>
                    <p class="text-gray-500">Belum ada aktivitas terbaru</p>
                </div>
            @endforelse
        </div>
    </div>
@endsection
