<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Sistem Absensi & Inspeksi</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @yield('styles')
</head>

<body class="bg-gray-100">
    <!-- Mobile Overlay -->
    <div id="mobileOverlay" class="hidden fixed inset-0 bg-black bg-opacity-90 z-30"></div>

    <div class="min-h-screen flex">
        <!-- Sidebar - Mobile -->
        <div id="sidebarMobile"
            class="lg:hidden fixed inset-y-0 left-0 z-40 w-64 bg-blue-800 text-white transform -translate-x-full transition-transform duration-300 ease-in-out">
            <div class="p-4">
                <!-- Tombol Close -->
                <button onclick="closeSidebar()" class="absolute top-4 right-4 text-white text-xl">
                    <i class="fas fa-times"></i>
                </button>

                <!-- Konten Sidebar Mobile -->
                <div class="mt-8">
                    <h2 class="text-xl font-bold mb-6">Menu</h2>
                    <ul class="space-y-3">
                        <li>
                            <a href="{{ route('pengawas.dashboard') }}"
                                class="flex items-center py-3 px-4 hover:bg-blue-700 rounded transition duration-200">
                                <i class="fas fa-tachometer-alt w-6 mr-3"></i>
                                Dashboard
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('pengawas.inspeksi') }}"
                                class="flex items-center py-3 px-4 hover:bg-blue-700 rounded transition duration-200">
                                <i class="fas fa-clipboard-check w-6 mr-3"></i>
                                Inspeksi
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('pengawas.laporan') }}"
                                class="flex items-center py-3 px-4 hover:bg-blue-700 rounded transition duration-200">
                                <i class="fas fa-chart-bar w-6 mr-3"></i>
                                Laporan
                            </a>
                        </li>
                        <li class="border-t border-blue-700 pt-3 mt-3">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                    class="flex items-center w-full py-3 px-4 hover:bg-blue-700 rounded transition duration-200 text-red-300 hover:text-white">
                                    <i class="fas fa-sign-out-alt w-6 mr-3"></i>
                                    Logout
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Sidebar - Desktop -->
        <div class="hidden lg:flex lg:flex-col w-64 bg-blue-800 text-white flex-shrink-0">
            <div class="p-6">
                <h1 class="text-xl font-bold">Sistem Inspeksi</h1>
                <p class="text-blue-200 text-sm mt-1">Panel Pengawas</p>
            </div>

            <nav class="flex-1 px-4 pb-4">
                <h2 class="text-sm font-semibold text-blue-300 uppercase tracking-wider mb-4 px-2">Menu</h2>
                <ul class="space-y-2">
                    <li>
                        <a href="{{ route('pengawas.dashboard') }}"
                            class="flex items-center py-3 px-4 hover:bg-blue-700 rounded transition duration-200 {{ request()->routeIs('pengawas.dashboard') ? 'bg-blue-900' : '' }}">
                            <i class="fas fa-tachometer-alt w-5 mr-3"></i>
                            Dashboard
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('pengawas.inspeksi') }}"
                            class="flex items-center py-3 px-4 hover:bg-blue-700 rounded transition duration-200 {{ request()->routeIs('pengawas.inspeksi') ? 'bg-blue-900' : '' }}">
                            <i class="fas fa-clipboard-check w-5 mr-3"></i>
                            Inspeksi
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('pengawas.laporan') }}"
                            class="flex items-center py-3 px-4 hover:bg-blue-700 rounded transition duration-200 {{ request()->routeIs('pengawas.laporan') ? 'bg-blue-900' : '' }}">
                            <i class="fas fa-chart-bar w-5 mr-3"></i>
                            Laporan
                        </a>
                    </li>
                </ul>

                <div class="mt-8 pt-4 border-t border-blue-700">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="flex items-center w-full py-3 px-4 hover:bg-blue-700 rounded transition duration-200 text-red-300 hover:text-white">
                            <i class="fas fa-sign-out-alt w-5 mr-3"></i>
                            Logout
                        </button>
                    </form>
                </div>
            </nav>

            <!-- User Info -->
            <div class="p-4 border-t border-blue-700">
                <div class="flex items-center">
                    <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center">
                        <i class="fas fa-user text-sm"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium">{{ Auth::user()->name ?? 'Pengawas' }}</p>
                        <p class="text-xs text-blue-300">Online</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col min-w-0">
            <!-- Header -->
            <header class="lg:hidden bg-white shadow">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                        <div class="flex items-center">
                            <!-- Hamburger Menu Button -->
                            <button onclick="openSidebar()"
                                class="lg:hidden p-2 text-gray-600 hover:text-gray-900 mr-3">
                                <i class="fas fa-bars text-xl"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Main Content -->
            <main class="flex-1 max-w-7xl mx-auto w-full px-4 sm:px-6 lg:px-8 py-6">
                @yield('content')
            </main>
        </div>
    </div>

    <!-- Global Sidebar Script -->
    <script>
        function openSidebar() {
            document.getElementById('sidebarMobile').classList.remove('-translate-x-full');
            document.getElementById('mobileOverlay').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeSidebar() {
            document.getElementById('sidebarMobile').classList.add('-translate-x-full');
            document.getElementById('mobileOverlay').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        // Close sidebar when clicking outside
        document.getElementById('mobileOverlay').addEventListener('click', closeSidebar);

        // Close sidebar with Escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeSidebar();
            }
        });

        // Close sidebar on window resize (if mobile to desktop)
        window.addEventListener('resize', function() {
            if (window.innerWidth >= 1024) {
                closeSidebar();
            }
        });
    </script>

    @yield('scripts')
</body>

</html>
