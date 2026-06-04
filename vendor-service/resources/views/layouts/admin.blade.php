<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Logistik App</title>
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
                        brand: {
                            900: '#0A1128', // Dark Navy
                            800: '#121F40', // Midnight Blue
                            700: '#1A2D5C', // Deep Blue
                            600: '#263F7A', 
                            500: '#3A60C8', // Accent Blue
                            400: '#4F7CFF', // Light Blue
                        }
                    }
                }
            }
        }
    </script>
    <style>
        .glass-header {
            background: rgba(18, 31, 64, 0.85);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }
        .glass-card {
            background: rgba(18, 31, 64, 0.6);
            backdrop-filter: blur(8px);
            border: 1px solid rgba(79, 124, 255, 0.15);
        }
        .input-dark {
            background: rgba(10, 17, 40, 0.6);
            border: 1px solid rgba(79, 124, 255, 0.2);
            color: white;
            transition: all 0.3s ease;
        }
        .input-dark:focus {
            border-color: #4F7CFF;
            outline: none;
        }
    </style>
</head>
<body class="bg-brand-900 text-gray-200 min-h-screen font-sans flex">

    <!-- Sidebar -->
    <aside class="w-64 glass-header border-r border-brand-800 flex flex-col hidden md:flex">
        <div class="h-16 flex items-center px-6 border-b border-brand-800">
            <svg class="w-6 h-6 text-brand-400 mr-2" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M12 2L2 7L12 12L22 7L12 2Z" fill="currentColor" opacity="0.8"/>
            </svg>
            <span class="text-lg font-bold text-white tracking-wide">ADMIN PORTAL</span>
        </div>
        
        <nav class="flex-1 px-4 py-6 space-y-2">
            <a href="#" class="flex items-center px-4 py-3 bg-brand-800 text-white rounded-xl shadow-lg border border-brand-700">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                Dashboard
            </a>
            <a href="#" class="flex items-center px-4 py-3 text-gray-400 hover:text-white hover:bg-brand-800/50 rounded-xl transition-colors">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                Validasi Vendor
            </a>
            <a href="#" class="flex items-center px-4 py-3 text-gray-400 hover:text-white hover:bg-brand-800/50 rounded-xl transition-colors">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                Alokasi Driver
            </a>
        </nav>

        <div class="p-4 border-t border-brand-800">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="flex items-center w-full px-4 py-3 text-red-400 hover:text-red-300 hover:bg-brand-800/50 rounded-xl transition-colors">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    Logout
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 flex flex-col h-screen overflow-hidden">
        <header class="h-16 glass-header flex items-center justify-between px-8 z-10">
            <h2 class="text-xl font-semibold text-white">@yield('header_title', 'Dashboard')</h2>
            <div class="flex items-center space-x-4">
                <div class="text-right">
                    <p class="text-sm font-semibold text-white">{{ Auth::user()->name }}</p>
                    <p class="text-xs text-brand-400">ADMINISTRATOR</p>
                </div>
            </div>
        </header>

        <div class="flex-1 overflow-y-auto p-8 relative">
            @yield('content')
        </div>
    </main>

    @stack('scripts')
</body>
</html>
