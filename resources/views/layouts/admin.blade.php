<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Panel')</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Alpine JS untuk interaktivitas -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        .sidebar-transition {
            transition: all 0.3s ease-in-out;
        }

        .active-sidebar-item {
            background: linear-gradient(90deg, #3b82f6 0%, #1d4ed8 100%);
            border-right: 4px solid #fbbf24;
        }

        .hover-lift:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
        }

        .notification-dot {
            animation: pulse 2s infinite;
        }

        @keyframes pulse {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.5;
            }
        }
    </style>
</head>

<body class="bg-gray-50" x-data="{ sidebarOpen: window.innerWidth >= 768, mobileSidebarOpen: false }">
    <!-- Mobile sidebar backdrop -->
    <div x-show="mobileSidebarOpen" x-transition:enter="transition-opacity ease-linear duration-300"
        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        x-transition:leave="transition-opacity ease-linear duration-300" x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0" class="fixed inset-0 z-40 bg-gray-900 bg-opacity-50 md:hidden"
        @click="mobileSidebarOpen = false">
    </div>

    <!-- Sidebar -->
    <div class="fixed inset-y-0 left-0 z-50 flex flex-col bg-gradient-to-b from-blue-800 to-blue-900 text-white sidebar-transition"
        :class="sidebarOpen ? 'w-64' : 'w-20'" x-show="sidebarOpen || mobileSidebarOpen"
        x-transition:enter="transform transition-transform duration-300 ease-in-out"
        x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0"
        x-transition:leave="transform transition-transform duration-300 ease-in-out"
        x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full"
        @click.away="mobileSidebarOpen = false">

        <!-- Logo Section -->
        <div class="flex items-center justify-between h-16 px-4 border-b border-blue-700">
            <div class="flex items-center space-x-3" :class="sidebarOpen ? 'opacity-100' : 'opacity-0 w-0'">
                <div class="flex items-center justify-center w-10 h-10 bg-white rounded-lg">
                    <i class="fas fa-cogs text-2xl text-blue-600"></i>
                </div>
                <div>
                    <h1 class="text-lg font-bold">Admin Panel</h1>
                    <p class="text-xs text-blue-200">Management System</p>
                </div>
            </div>

            <!-- Toggle Button -->
            <button @click="sidebarOpen = !sidebarOpen; if(window.innerWidth < 768) mobileSidebarOpen = false"
                class="p-2 rounded-lg hover:bg-blue-700 transition-colors duration-200">
                <i class="fas fa-bars"></i>
            </button>
        </div>

        <!-- User Profile Section -->
        <div class="px-4 py-6 border-b border-blue-700" x-show="sidebarOpen">
            <div class="flex items-center space-x-3">
                <div class="relative">
                    <div
                        class="w-12 h-12 bg-gradient-to-br from-yellow-400 to-orange-500 rounded-full flex items-center justify-center text-white font-bold text-lg">
                        {{ substr(Auth::user()->nama ?? (Auth::user()->name ?? 'A'), 0, 1) }}
                    </div>
                    <div class="absolute bottom-0 right-0 w-3 h-3 bg-green-500 border-2 border-blue-800 rounded-full">
                    </div>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="font-semibold truncate">
                        {{ Auth::user()->nama ?? (Auth::user()->name ?? 'Administrator') }}
                    </p>
                    <p class="text-sm text-blue-200 truncate">{{ Auth::user()->email ?? 'admin@system.com' }}</p>
                </div>
            </div>
        </div>

        <!-- Navigation Menu -->
        <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
            <!-- Dashboard -->
            <a href="{{ route('admin.dashboard') }}"
                class="flex items-center px-4 py-3 rounded-xl transition-all duration-200 hover:bg-blue-700 hover-lift group {{ request()->routeIs('admin.dashboard') ? 'active-sidebar-item' : '' }}">
                <i class="fas fa-home text-lg w-6 text-center"></i>
                <span class="ml-3 font-medium" :class="sidebarOpen ? 'opacity-100' : 'opacity-0 w-0'">Dashboard</span>
                @if (request()->routeIs('admin.dashboard'))
                    <div class="ml-auto" :class="sidebarOpen ? 'opacity-100' : 'opacity-0 w-0'">
                        <div class="w-2 h-2 bg-yellow-400 rounded-full"></div>
                    </div>
                @endif
            </a>

            <!-- Users Management -->
            <a href="{{ route('admin.users') }}"
                class="flex items-center px-4 py-3 rounded-xl transition-all duration-200 hover:bg-blue-700 hover-lift group {{ request()->routeIs('admin.users') ? 'active-sidebar-item' : '' }}">
                <i class="fas fa-users text-lg w-6 text-center"></i>
                <span class="ml-3 font-medium" :class="sidebarOpen ? 'opacity-100' : 'opacity-0 w-0'">Users</span>
                <div class="ml-auto flex items-center space-x-1" :class="sidebarOpen ? 'opacity-100' : 'opacity-0 w-0'">
                    <span class="px-2 py-1 text-xs bg-blue-600 rounded-full">{{ \App\Models\User::count() }}</span>
                </div>
            </a>

            <!-- Reports -->
            <a href="{{ route('admin.laporan') }}"
                class="flex items-center px-4 py-3 rounded-xl transition-all duration-200 hover:bg-blue-700 hover-lift group {{ request()->routeIs('admin.laporan') ? 'active-sidebar-item' : '' }}">
                <i class="fas fa-chart-bar text-lg w-6 text-center"></i>
                <span class="ml-3 font-medium" :class="sidebarOpen ? 'opacity-100' : 'opacity-0 w-0'">Laporan</span>
                <div class="ml-auto" :class="sidebarOpen ? 'opacity-100' : 'opacity-0 w-0'">
                    <i
                        class="fas fa-chevron-right text-xs text-blue-300 group-hover:translate-x-1 transition-transform duration-200"></i>
                </div>
            </a>

            <!-- Divider -->
            <div class="border-t border-blue-700 my-4"></div>

            <!-- System-info -->
            <a href="{{ route('admin.system.info') }}"
                class="flex items-center px-4 py-3 rounded-xl transition-all duration-200 hover:bg-blue-700 hover-lift group {{ request()->routeIs('admin.system.info') ? 'active-sidebar-item' : '' }}">
                <i class="fas fa-cog text-lg w-6 text-center"></i>
                <span class="ml-3 font-medium" :class="sidebarOpen ? 'opacity-100' : 'opacity-0 w-0'">Info Sistem</span>
            </a>

            <!-- Help -->
            <a href="{{ route('admin.bantuan') }}"
                class="flex items-center px-4 py-3 rounded-xl transition-all duration-200 hover:bg-blue-700 hover-lift group {{ request()->routeIs('admin.bantuan') ? 'active-sidebar-item' : '' }}">
                <i class="fas fa-question-circle text-lg w-6 text-center"></i>
                <span class="ml-3 font-medium" :class="sidebarOpen ? 'opacity-100' : 'opacity-0 w-0'">Bantuan</span>
            </a>
        </nav>

        <!-- Footer Section -->
        <div class="p-4 border-t border-blue-700">
            <!-- Logout -->
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="flex items-center w-full px-4 py-3 rounded-xl transition-all duration-200 hover:bg-red-600 hover-lift group">
                    <i class="fas fa-sign-out-alt text-lg w-6 text-center"></i>
                    <span class="ml-3 font-medium" :class="sidebarOpen ? 'opacity-100' : 'opacity-0 w-0'">Logout</span>
                </button>
            </form>

            <!-- Collapse Indicator -->
            <div class="mt-4 text-center" x-show="sidebarOpen">
                <p class="text-xs text-blue-300">© 2025 Admin System</p>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="min-h-screen" :class="sidebarOpen ? 'md:ml-64' : 'md:ml-20'">
        <!-- Top Header -->
        <header class="bg-white shadow-sm border-b border-gray-200">
            <div class="flex items-center justify-between px-6 py-4">
                <!-- Left: Menu Button for Mobile -->
                <div class="flex items-center space-x-4">
                    <button @click="mobileSidebarOpen = true" class="p-2 rounded-lg hover:bg-gray-100 md:hidden">
                        <i class="fas fa-bars text-gray-600"></i>
                    </button>

                    <!-- Breadcrumb -->
                    <div class="hidden md:flex items-center space-x-2 text-sm text-gray-500">
                        <span>Admin</span>
                        <i class="fas fa-chevron-right text-xs"></i>
                        <span class="text-gray-700">@yield('title', 'Dashboard')</span>
                    </div>
                </div>

                <!-- Right: User Menu & Notifications -->
                <div class="flex items-center space-x-4">
                    <!-- User Menu -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open"
                            class="flex items-center space-x-3 p-2 rounded-lg hover:bg-gray-100">
                            <div
                                class="w-8 h-8 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white font-semibold text-sm">
                                {{ substr(Auth::user()->nama ?? (Auth::user()->name ?? 'A'), 0, 1) }}
                            </div>
                            <span class="hidden md:block text-sm font-medium text-gray-700">
                                {{ Auth::user()->nama ?? (Auth::user()->name ?? 'Admin') }}
                            </span>
                        </button>
                    </div>
                </div>
            </div>
        </header>

        <!-- Page Content -->
        <main class="p-6">
            @yield('content')
        </main>
    </div>

    <script>
        // Auto-close mobile sidebar when clicking on menu items
        document.addEventListener('DOMContentLoaded', function() {
            const mobileMenuItems = document.querySelectorAll('nav a');
            mobileMenuItems.forEach(item => {
                item.addEventListener('click', function() {
                    if (window.innerWidth < 768) {
                        Alpine.store('sidebar').mobileSidebarOpen = false;
                    }
                });
            });
        });
    </script>

    @yield('scripts')
</body>

</html>
