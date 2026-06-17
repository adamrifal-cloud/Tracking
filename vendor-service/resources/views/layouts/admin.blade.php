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
<body class="bg-brand-900 text-gray-200 min-h-screen font-sans flex overflow-hidden">

    <!-- Mobile Sidebar Overlay -->
    <div id="sidebar-overlay" class="fixed inset-0 bg-black/60 z-20 hidden md:hidden opacity-0 transition-opacity duration-300" onclick="toggleMobileSidebar()"></div>

    <!-- Sidebar -->
    <aside id="admin-sidebar" class="w-64 glass-header border-r border-brand-800 flex flex-col fixed inset-y-0 left-0 z-30 transform -translate-x-full md:translate-x-0 md:static md:flex transition-transform duration-300 ease-in-out">
        <div class="h-16 flex items-center justify-between px-6 border-b border-brand-800">
            <div class="flex items-center">
                <svg class="w-6 h-6 text-brand-400 mr-2" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 2L2 7L12 12L22 7L12 2Z" fill="currentColor" opacity="0.8"/>
                </svg>
                <span class="text-lg font-bold text-white tracking-wide">ADMIN PORTAL</span>
            </div>
            <!-- Close Button for Mobile -->
            <button onclick="toggleMobileSidebar()" class="p-1.5 text-gray-400 hover:text-white md:hidden bg-brand-800/50 hover:bg-brand-800 rounded-lg transition-colors" title="Tutup Menu">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        
        <nav class="flex-1 px-4 py-6 space-y-2">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center px-4 py-3 {{ request()->routeIs('admin.dashboard') ? 'bg-brand-800 text-white rounded-xl shadow-lg border border-brand-700 font-bold' : 'text-gray-400 hover:text-white hover:bg-brand-800/50 rounded-xl transition-colors font-medium' }}">
                <svg class="w-5 h-5 mr-3 {{ request()->routeIs('admin.dashboard') ? 'text-brand-400' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                Dashboard
            </a>
            <a href="{{ route('admin.vendors.index') }}" class="flex items-center px-4 py-3 {{ request()->routeIs('admin.vendors.index') ? 'bg-brand-800 text-white rounded-xl shadow-lg border border-brand-700 font-bold' : 'text-gray-400 hover:text-white hover:bg-brand-800/50 rounded-xl transition-colors font-medium' }}">
                <svg class="w-5 h-5 mr-3 {{ request()->routeIs('admin.vendors.index') ? 'text-brand-400' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                Validasi Vendor
            </a>

        </nav>

        <div class="p-4 border-t border-brand-800">
            <form method="POST" action="{{ route('admin.logout') }}">
                @csrf
                <button type="submit" class="flex items-center w-full px-4 py-3 text-red-400 hover:text-red-300 hover:bg-brand-800/50 rounded-xl transition-colors font-medium">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013 3h4a3 3 0 013 3v1"></path></svg>
                    Logout
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 flex flex-col h-screen overflow-hidden">
        <header class="h-16 glass-header flex items-center justify-between px-4 md:px-8 z-10">
            <div class="flex items-center gap-3 min-w-0 flex-1">
                <!-- Hamburger Button -->
                <button onclick="toggleMobileSidebar()" class="p-2 text-gray-400 hover:text-white hover:bg-brand-800/50 rounded-xl md:hidden transition-colors shrink-0" title="Menu Utama">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                </button>
                <h2 class="text-lg md:text-xl font-bold text-white truncate">@yield('header_title', 'Overview')</h2>
            </div>
            <div class="flex items-center space-x-3 shrink-0 ml-4">
                <div class="text-right hidden sm:block">
                    <p class="text-sm font-semibold text-white">{{ Auth::user()->name }}</p>
                    <p class="text-xs text-brand-400">ADMINISTRATOR</p>
                </div>
                <!-- Admin Avatar -->
                <div class="w-10 h-10 rounded-full bg-brand-800 border border-brand-700 text-brand-400 font-bold flex items-center justify-center shadow-sm">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
            </div>
        </header>

        <div id="main-content-container" class="flex-1 overflow-y-auto p-4 md:p-8 relative">
            @yield('content')
        </div>
    </main>

    <!-- Toast Notification Container -->
    <div id="toast-container" class="fixed bottom-5 right-5 z-50 flex flex-col gap-3 max-w-sm w-full px-4 sm:px-0"></div>

    <script>
        window.knownUnallocatedOrders = new Set();

        function toggleMobileSidebar() {
            const sidebar = document.getElementById('admin-sidebar');
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

        function playNotificationSound() {
            try {
                const audioCtx = new (window.AudioContext || window.webkitAudioContext)();
                
                // Tone 1: G5
                const osc1 = audioCtx.createOscillator();
                const gain1 = audioCtx.createGain();
                osc1.type = 'sine';
                osc1.frequency.setValueAtTime(783.99, audioCtx.currentTime); // G5
                gain1.gain.setValueAtTime(0.08, audioCtx.currentTime);
                gain1.gain.exponentialRampToValueAtTime(0.001, audioCtx.currentTime + 0.25);
                osc1.connect(gain1);
                gain1.connect(audioCtx.destination);
                osc1.start();
                osc1.stop(audioCtx.currentTime + 0.25);
                
                // Tone 2: C6
                setTimeout(() => {
                    const osc2 = audioCtx.createOscillator();
                    const gain2 = audioCtx.createGain();
                    osc2.type = 'sine';
                    osc2.frequency.setValueAtTime(1046.50, audioCtx.currentTime); // C6
                    gain2.gain.setValueAtTime(0.08, audioCtx.currentTime);
                    gain2.gain.exponentialRampToValueAtTime(0.001, audioCtx.currentTime + 0.35);
                    osc2.connect(gain2);
                    gain2.connect(audioCtx.destination);
                    osc2.start();
                    osc2.stop(audioCtx.currentTime + 0.35);
                }, 120);
            } catch (e) {
                console.warn('AudioContext not supported or blocked:', e);
            }
        }

        async function checkNewOrders(isInitial = false) {
            try {
                const response = await fetch('{{ route('admin.unread-orders') }}');
                if (!response.ok) return;
                const orders = await response.json();
                
                let hasNew = false;
                const dropdown = document.getElementById('order_id');
                
                orders.forEach(order => {
                    const orderId = order.order_id;
                    if (!window.knownUnallocatedOrders.has(orderId)) {
                        window.knownUnallocatedOrders.add(orderId);
                        
                        if (!isInitial) {
                            hasNew = true;
                            // Show notification toast
                            showToast(
                                '🔔 Pesanan Baru!',
                                `Pesanan ${orderId} telah masuk dan membutuhkan alokasi driver.`,
                                'info'
                            );
                            
                            // Inject into dropdown selection if it exists
                            if (dropdown) {
                                const disabledOption = dropdown.querySelector('option[disabled]');
                                if (disabledOption) {
                                    disabledOption.remove();
                                }
                                
                                if (!dropdown.querySelector(`option[value="${orderId}"]`)) {
                                    const option = document.createElement('option');
                                    option.value = orderId;
                                    option.className = 'bg-brand-900';
                                    option.textContent = orderId;
                                    dropdown.appendChild(option);
                                }
                            }
                        }
                    }
                });
                
                if (hasNew && !isInitial) {
                    playNotificationSound();
                }
            } catch (error) {
                console.error('Failed to poll unallocated orders:', error);
            }
        }

        function showToast(title, message, type = 'info') {
            const container = document.getElementById('toast-container');
            if (!container) return;
            
            const toast = document.createElement('div');
            toast.className = 'glass-card p-4 rounded-xl shadow-2xl border border-brand-500/30 flex items-start gap-3 transform translate-y-10 opacity-0 transition-all duration-300 ease-out';
            
            let icon = `<svg class="w-6 h-6 text-brand-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>`;
            if (type === 'success') {
                icon = `<svg class="w-6 h-6 text-green-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>`;
            } else if (type === 'warning') {
                icon = `<svg class="w-6 h-6 text-yellow-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>`;
            }
            
            toast.innerHTML = `
                ${icon}
                <div class="flex-1 min-w-0">
                    <h4 class="font-bold text-white text-sm truncate">${title}</h4>
                    <p class="text-xs text-gray-300 mt-0.5 break-words">${message}</p>
                </div>
                <button onclick="this.parentElement.remove()" class="text-gray-400 hover:text-white transition-colors shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            `;
            
            container.appendChild(toast);
            
            // Trigger animation
            setTimeout(() => {
                toast.classList.remove('translate-y-10', 'opacity-0');
                toast.classList.add('translate-y-0', 'opacity-100');
            }, 50);
            
            // Auto remove after 6 seconds
            setTimeout(() => {
                if (toast.parentElement) {
                    toast.classList.remove('translate-y-0', 'opacity-100');
                    toast.classList.add('translate-y-10', 'opacity-0');
                    setTimeout(() => {
                        toast.remove();
                    }, 300);
                }
            }, 6000);
        }

        document.addEventListener('DOMContentLoaded', async () => {
            // Fetch initial unallocated orders to populate the set without showing notifications
            await checkNewOrders(true);
            
            // Start polling every 5 seconds
            setInterval(() => checkNewOrders(false), 5000);
        });
    </script>
    @stack('scripts')
</body>
</html>
