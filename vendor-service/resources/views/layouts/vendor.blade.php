<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vendor Dashboard - Logistik App</title>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                    },
                    colors: {
                        vendor: {
                            50: '#fff7ed',
                            100: '#ffedd5',
                            500: '#f97316', // Orange
                            600: '#ea580c',
                            900: '#7c2d12',
                        }
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-50 text-gray-800 min-h-screen font-sans flex overflow-hidden">

    <!-- Mobile Sidebar Overlay -->
    <div id="sidebar-overlay" class="fixed inset-0 bg-black/40 z-20 hidden md:hidden opacity-0 transition-opacity duration-300" onclick="toggleMobileSidebar()"></div>

    <!-- Sidebar -->
    <aside id="vendor-sidebar" class="w-64 bg-white border-r border-gray-200 flex flex-col fixed inset-y-0 left-0 z-30 transform -translate-x-full md:translate-x-0 md:static md:flex shadow-sm transition-transform duration-300 ease-in-out">
        <div class="h-16 flex items-center justify-between px-6 border-b border-gray-100">
            <div class="flex items-center">
                <svg class="w-6 h-6 text-vendor-500 mr-2" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <span class="text-lg font-extrabold text-gray-900 tracking-wide">VENDOR HUB</span>
            </div>
            <!-- Close Button for Mobile -->
            <button onclick="toggleMobileSidebar()" class="p-1.5 text-gray-400 hover:text-gray-600 md:hidden bg-gray-50 hover:bg-gray-100 rounded-lg transition-colors" title="Tutup Menu">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        
        <nav class="flex-1 px-4 py-6 space-y-2">
            <a href="{{ route('vendor.dashboard') }}" class="flex items-center px-4 py-3 {{ request()->routeIs('vendor.dashboard') ? 'bg-vendor-50 text-vendor-600' : 'text-gray-500 hover:text-vendor-600 hover:bg-gray-50' }} rounded-xl font-medium transition-colors">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                Dashboard
            </a>
            <a href="{{ route('vendor.profile') }}" class="flex items-center px-4 py-3 {{ request()->routeIs('vendor.profile') ? 'bg-vendor-50 text-vendor-600' : 'text-gray-500 hover:text-vendor-600 hover:bg-gray-50' }} rounded-xl font-medium transition-colors">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                Profil Saya
            </a>

            <a href="{{ route('vendor.drivers.index') }}" class="flex items-center px-4 py-3 {{ request()->routeIs('vendor.drivers.index') ? 'bg-vendor-50 text-vendor-600' : 'text-gray-500 hover:text-vendor-600 hover:bg-gray-50' }} rounded-xl font-medium transition-colors">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                Kelola Driver
            </a>
            <a href="{{ route('vendor.notifications.index') }}" class="flex items-center px-4 py-3 {{ request()->routeIs('vendor.notifications.index') ? 'bg-vendor-50 text-vendor-600' : 'text-gray-500 hover:text-vendor-600 hover:bg-gray-50' }} rounded-xl font-medium transition-colors">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.151 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                Notifikasi
            </a>
        </nav>

        <div class="p-4 border-t border-gray-100">
            <form method="POST" action="{{ route('vendor.logout') }}">
                @csrf
                <button type="submit" class="flex items-center w-full px-4 py-3 text-red-500 hover:bg-red-50 rounded-xl transition-colors font-medium">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    Logout
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 flex flex-col h-screen overflow-hidden">
        <header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-4 md:px-8 z-10 shadow-sm">
            <div class="flex items-center gap-3 min-w-0 flex-1">
                <!-- Hamburger Button -->
                <button onclick="toggleMobileSidebar()" class="p-2 text-gray-500 hover:text-vendor-600 hover:bg-gray-50 rounded-xl md:hidden transition-colors shrink-0" title="Menu Utama">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                </button>
                <h2 class="text-lg md:text-xl font-bold text-gray-800 truncate">@yield('header_title', 'Overview')</h2>
            </div>
            <div class="flex items-center space-x-3 shrink-0 ml-4">
                <div class="text-right hidden md:block">
                    <p class="text-sm font-bold text-gray-900">{{ Auth::user()->name }}</p>
                    <p class="text-xs text-vendor-500 font-semibold">PARTNER VENDOR</p>
                </div>
                <!-- Vendor Avatar Link to Profile -->
                <a href="{{ route('vendor.profile') }}" class="w-10 h-10 rounded-full bg-vendor-100 hover:bg-vendor-200 text-vendor-600 font-bold flex items-center justify-center border border-vendor-200 shadow-sm transition-colors cursor-pointer" title="Lihat Profil">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </a>
            </div>
        </header>

        <div class="flex-1 overflow-y-auto p-4 md:p-8 relative">
            @yield('content')
        </div>
    </main>

    <script>
        function toggleMobileSidebar() {
            const sidebar = document.getElementById('vendor-sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            
            if (sidebar.classList.contains('-translate-x-full')) {
                // Open sidebar
                sidebar.classList.remove('-translate-x-full');
                sidebar.classList.add('translate-x-0');
                
                overlay.classList.remove('hidden');
                setTimeout(() => {
                    overlay.classList.remove('opacity-0');
                    overlay.classList.add('opacity-100');
                }, 50);
            } else {
                // Close sidebar
                sidebar.classList.remove('translate-x-0');
                sidebar.classList.add('-translate-x-full');
                
                overlay.classList.remove('opacity-100');
                overlay.classList.add('opacity-0');
                setTimeout(() => {
                    overlay.classList.add('hidden');
                }, 300);
            }
        }
    </script>
    @stack('scripts')
</body>
</html>
