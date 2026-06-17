@php
    $activeTasks = $activeTasks ?? collect();
    $completedTasks = $completedTasks ?? collect();
    $availableTasks = $availableTasks ?? collect();
    $pendingTasks = $pendingTasks ?? collect();
@endphp
<x-app-layout>
    <!-- Leaflet CSS & JS for Live Map -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin=""/>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.css" />
    <script src="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.js"></script>

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            -webkit-font-smoothing: antialiased;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        @keyframes pulse-glow {
            0%, 100% {
                opacity: 0.15;
                transform: scale(1);
            }
            50% {
                opacity: 0.3;
                transform: scale(1.05);
            }
        }
        .animate-pulse-glow {
            animation: pulse-glow 8s ease-in-out infinite;
        }

        /* Custom Scrollbar for scrollable panels */
        ::-webkit-scrollbar {
            width: 5px;
            height: 5px;
        }
        ::-webkit-scrollbar-track {
            background: transparent;
        }
        ::-webkit-scrollbar-thumb {
            background: rgba(16, 185, 129, 0.25);
            border-radius: 9999px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: rgba(16, 185, 129, 0.45);
        }

        /* Map styling */
        .leaflet-container {
            background: #f1f5f9;
        }
        .leaflet-bar {
            border: none !important;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05) !important;
        }
        .leaflet-bar a {
            background-color: #ffffff !important;
            color: #1e293b !important;
            border-bottom: 1px solid #f1f5f9 !important;
            transition: background 0.2s;
        }
        .leaflet-bar a:hover {
            background-color: #f8fafc !important;
        }
        .leaflet-routing-container {
            display: none !important;
        }

        /* DARK MODE CSS OVERRIDES (To work without recompiling Tailwind) */
        html.dark-theme body,
        html.dark-theme .leaflet-container {
            background-color: #0f172a !important;
        }
        html.dark-theme .bg-white,
        html.dark-theme .bg-white\/60,
        html.dark-theme .bg-white\/70,
        html.dark-theme .bg-white\/80 {
            background-color: #1e293b !important;
            border-color: rgba(255, 255, 255, 0.05) !important;
        }
        html.dark-theme .text-slate-900,
        html.dark-theme .text-slate-800,
        html.dark-theme .text-slate-700,
        html.dark-theme .text-slate-600 {
            color: #f8fafc !important;
        }
        html.dark-theme .text-slate-500,
        html.dark-theme .text-slate-400 {
            color: #94a3b8 !important;
        }
        html.dark-theme .border-slate-100,
        html.dark-theme .border-slate-200,
        html.dark-theme .border-slate-200\/40,
        html.dark-theme .border-slate-200\/50,
        html.dark-theme .border-slate-200\/60,
        html.dark-theme .border-slate-100\/50 {
            border-color: rgba(255, 255, 255, 0.1) !important;
        }
        html.dark-theme .bg-slate-50,
        html.dark-theme .bg-slate-100,
        html.dark-theme .bg-slate-50\/50,
        html.dark-theme .hover\:bg-slate-50:hover,
        html.dark-theme .hover\:bg-slate-100:hover {
            background-color: #334155 !important;
        }
        html.dark-theme .bg-emerald-50,
        html.dark-theme .bg-emerald-100,
        html.dark-theme .bg-emerald-50\/50 {
            background-color: rgba(16, 185, 129, 0.1) !important;
            border-color: rgba(16, 185, 129, 0.2) !important;
        }
        html.dark-theme .text-emerald-700,
        html.dark-theme .text-emerald-600 {
            color: #34d399 !important;
        }
        html.dark-theme .bg-amber-50 {
            background-color: rgba(245, 158, 11, 0.1) !important;
            border-color: rgba(245, 158, 11, 0.2) !important;
        }
        html.dark-theme .text-amber-600 {
            color: #fbbf24 !important;
        }
        html.dark-theme .shadow-sm,
        html.dark-theme .shadow-md,
        html.dark-theme .shadow-lg,
        html.dark-theme .shadow-\[0_20px_50px_rgba\(0\,0\,0\,0\.06\)\] {
            box-shadow: 0 10px 30px rgba(0,0,0,0.3) !important;
        }
        html.dark-theme select,
        html.dark-theme input,
        html.dark-theme textarea {
            background-color: #0f172a !important;
            color: #f8fafc !important;
            border-color: #334155 !important;
        }
        html.dark-theme select option {
            background-color: #1e293b !important;
            color: #f8fafc !important;
        }
    </style>

    <!-- Top Navigation Bar -->
    <header class="fixed top-0 left-0 w-full h-16 bg-white/70 backdrop-blur-xl flex items-center justify-between px-4 sm:px-6 md:px-8 lg:px-12 z-50 border-b border-slate-200/40 shadow-sm transition-colors duration-300">
        <div class="flex items-center gap-3">
            <div class="flex items-center gap-3 hover:opacity-90 transition cursor-pointer group">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-emerald-500 to-teal-500 flex items-center justify-center shadow-lg shadow-emerald-500/20 shrink-0 transform group-hover:scale-105 transition duration-350">
                    <svg class="w-5 h-5 text-white" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8.25 18.75a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 0 1-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h1.125a1.125 1.125 0 0 0 1.125-1.125V9.75M3.82 8.167a3.75 3.75 0 0 1 5.377-2.833l10.932 5.466M3.82 8.167l-.149.277a3.75 3.75 0 0 0 2.215 5.059m-.149-5.336L3.728 8.11a3.75 3.75 0 0 0-.256 3.118m0-3.118a3.733 3.733 0 0 1 1.012-.083M9.75 9.75c0 .414-.168.788-.439 1.061m0 0a1.5 1.5 0 0 1-2.122 0m2.122 0h6.122m-8.244 0a1.5 1.5 0 0 1 0-2.122m0 0a1.5 1.5 0 0 1 2.122 0m-2.122 0h.008m13.492 3a3.375 3.375 0 0 1-3.375-3.375v-.154M12 21a9.003 9.003 0 0 0 8.354-5.646M12 21a9 9 0 0 1-8.354-5.646" /></svg>
                </div>
                <span class="text-lg font-extrabold tracking-tight text-slate-900 hidden md:inline">Track<span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-500 to-teal-500">IT</span> <span class="text-emerald-700 text-[10px] font-black ml-1 uppercase tracking-widest bg-emerald-100 px-2 py-0.5 rounded-full">Driver</span></span>
            </div>
        </div>

        <div class="flex items-center gap-3 md:gap-4">
            <!-- Dark Mode Toggle -->
            <button onclick="toggleDarkMode()" class="p-2 rounded-full hover:bg-slate-100 transition duration-200 text-slate-600 focus:outline-none" title="Ubah Tema Gelap/Terang">
                <!-- Sun Icon (Hidden in dark mode initially) -->
                <svg id="sunIcon" class="w-5 h-5 hidden" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386l-1.591 1.591M21 12h-2.25m-.386 6.364l-1.591-1.591M12 18.75V21m-4.773-4.227l-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z" /></svg>
                <!-- Moon Icon (Visible by default) -->
                <svg id="moonIcon" class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.718 9.718 0 0118 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 003 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 009.002-5.998z" /></svg>
            </button>

            <!-- Notifications Toggle -->
            <div class="relative">
                <button onclick="toggleNotifications()" class="p-2 rounded-full hover:bg-slate-100 transition duration-200 text-slate-600 focus:outline-none relative">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" /></svg>
                    <!-- Notification Badge -->
                    <span id="notifBadge" class="absolute top-1 right-1 flex h-3 w-3 hidden">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-3 w-3 bg-red-500 border-2 border-white"></span>
                    </span>
                </button>

                <!-- Notifications Dropdown -->
                <div id="notificationsDropdown" class="hidden fixed left-4 right-4 top-20 sm:absolute sm:top-14 sm:left-auto sm:right-0 w-[calc(100vw-32px)] sm:w-96 bg-white/95 backdrop-blur-xl rounded-[2rem] sm:rounded-2xl shadow-2xl border border-slate-200/50 p-5 z-50 transform origin-top sm:origin-top-right transition">
                    <!-- Header Tabs -->
                    <div class="flex items-center gap-1 bg-slate-100/80 p-1 rounded-xl mb-4 border border-slate-200/40">
                        <button id="tabNotifBtn" onclick="switchNotifTab('notif')" class="flex-1 py-2 text-center text-[10px] font-black uppercase tracking-wider rounded-lg transition-all duration-300 cursor-pointer bg-white text-emerald-700 shadow-sm border border-slate-200/40 relative">
                            Pemberitahuan
                            <span id="notifCountText" class="absolute -top-1 -right-1 bg-emerald-500 text-white text-[8px] font-black px-1.5 py-0.5 rounded-full hidden">0</span>
                        </button>
                        <button id="tabShipmentBtn" onclick="switchNotifTab('riwayat')" class="flex-1 py-2 text-center text-[10px] font-black uppercase tracking-wider rounded-lg transition-all duration-300 cursor-pointer text-slate-400 hover:text-slate-800">
                            Riwayat ({{ count($completedTasks) }})
                        </button>
                    </div>

                    <!-- Panel 1: Notifications -->
                    <div id="tabContent-notif" class="space-y-3 block">
                        <div class="flex items-center justify-between border-b border-slate-100 pb-2">
                            <span class="text-[9px] bg-emerald-50 text-emerald-600 px-2 py-0.5 rounded-full font-bold">Real-time</span>
                            <span class="text-[9px] text-slate-400 font-bold">Terbaru</span>
                        </div>
                        <div id="notificationsList" class="max-h-60 overflow-y-auto text-xs text-slate-600 flex flex-col gap-2">
                            <!-- Fetched Notifications injected here -->
                            <div class="p-8 text-center text-slate-400 text-xs font-medium flex flex-col items-center gap-3">
                                <svg class="w-8 h-8 text-slate-200 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                <span>Memuat notifikasi...</span>
                            </div>
                        </div>
                    </div>

                    <!-- Panel 2: Riwayat Selesai -->
                    <div id="tabContent-riwayat" class="hidden space-y-3">
                        <div class="flex items-center justify-between border-b border-slate-100 pb-2">
                            <span class="text-[9px] bg-orange-50 text-[#FB8500] px-2 py-0.5 rounded-full font-bold">Riwayat Tugas</span>
                            <span class="text-[9px] text-slate-400 font-bold">{{ count($completedTasks) }} Selesai</span>
                        </div>
                        <div class="max-h-60 overflow-y-auto pr-1 flex flex-col gap-2.5">
                            @forelse($completedTasks as $task)
                                <div class="w-full p-3.5 bg-slate-50 hover:bg-slate-100 border border-slate-200/60 rounded-xl text-left transition flex items-center justify-between gap-3 cursor-pointer group">
                                    <div class="flex items-center gap-2.5 min-w-0">
                                        <div class="w-7.5 h-7.5 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0 border border-emerald-100">
                                            <svg class="w-3.5 h-3.5 text-emerald-500" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                        </div>
                                        <div class="min-w-0">
                                            <span class="block text-[10px] font-black text-slate-800 leading-none group-hover:text-emerald-600 transition truncate">#{{ $task->order_id }}</span>
                                            <span class="block text-[9px] text-slate-400 mt-1 leading-none truncate max-w-[120px]">Penerima: {{ $task->receiver_name }}</span>
                                        </div>
                                    </div>
                                    <div class="text-right shrink-0">
                                        <span class="inline-block text-[8px] font-black text-emerald-600 bg-emerald-50 border border-emerald-500/10 px-2 py-0.5 rounded-full leading-none uppercase">Selesai</span>
                                    </div>
                                </div>
                            @empty
                                <div class="p-6 text-center text-slate-400 font-semibold text-xs border border-dashed border-slate-200 rounded-xl bg-slate-50">
                                    Belum ada tugas selesai.
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <!-- Profile Toggle -->
            <div onclick="toggleMobileSettings(); switchDrawerToSummary();" class="rounded-full bg-white border border-slate-200/60 shadow-sm flex items-center justify-center font-extrabold text-slate-700 hover:shadow-md hover:border-slate-300 transition duration-300 uppercase shrink-0 text-sm cursor-pointer" style="width: 40px; height: 40px; min-width: 40px; min-height: 40px; max-width: 40px; max-height: 40px;" title="{{ Auth::user()->name }}">
                @if(Auth::user()->name && trim(Auth::user()->name) !== '')
                    {{ substr(trim(Auth::user()->name), 0, 1) }}
                @else
                    <svg class="w-5 h-5 text-slate-500" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0zM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632z" /></svg>
                @endif
            </div>
        </div>
    </header>

    <!-- Main Container -->
    <div class="min-h-screen lg:h-screen w-full font-sans relative flex flex-col lg:flex-row bg-slate-50 overflow-y-auto lg:overflow-hidden pt-16 transition-colors duration-300">
        
        <!-- Ambient Background Glowing Orbs -->
        <div class="absolute top-[-10%] left-[-10%] w-[320px] sm:w-[500px] h-[320px] sm:h-[500px] rounded-full bg-emerald-300/20 blur-[100px] pointer-events-none animate-pulse-glow"></div>
        <div class="absolute bottom-[-10%] right-[-10%] w-[400px] sm:w-[600px] h-[400px] sm:h-[600px] rounded-full bg-teal-400/10 blur-[125px] pointer-events-none animate-pulse-glow" style="animation-delay: -3s;"></div>

        <!-- LEFT COLUMN: Available & Completed Tasks -->
        <div class="w-full lg:w-[42%] xl:w-[38%] px-6 pt-8 pb-24 sm:px-10 lg:py-16 flex flex-col shrink-0 border-b lg:border-b-0 lg:border-r border-slate-200/50 bg-white/30 backdrop-blur-3xl relative z-20 overflow-y-auto lg:h-full transition-colors duration-300">
            
            <div class="flex flex-col gap-6">
                <!-- Status Badge -->
                <div class="inline-flex self-start items-center gap-2 px-3 py-1.5 rounded-full bg-white border border-slate-200/60 shadow-sm hover:border-slate-300 transition duration-300">
                    <span class="relative flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                    </span>
                    <span class="text-[10px] font-extrabold tracking-widest text-slate-700 uppercase">Sistem Online</span>
                </div>

                <div>
                    <h1 class="text-4xl font-black text-slate-900 tracking-tight leading-[1.08] mb-2">
                        Tugas Anda<br>
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-500 to-teal-500">
                            Hari Ini.
                        </span>
                    </h1>
                    <p class="text-slate-500 text-xs font-medium max-w-sm mt-2 leading-relaxed">
                        Lihat tugas yang tersedia, ambil, dan segera selesaikan pengantaran ke lokasi pelanggan.
                    </p>
                </div>

                @if(Auth::user()->status !== 'active')
                    <div class="p-5 bg-amber-500/10 border border-amber-500/35 rounded-3xl text-slate-800 dark:text-amber-200 mt-2 flex flex-col gap-3 shadow-md">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-amber-500/20 text-amber-600 flex items-center justify-center shrink-0">
                                <svg class="w-6 h-6 text-amber-500" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                            </div>
                            <div class="min-w-0 flex-1">
                                <h3 class="text-sm font-black text-slate-800 leading-tight">Akun Menunggu Verifikasi</h3>
                                <p class="text-[11px] text-slate-600 dark:text-slate-300 mt-0.5 leading-relaxed font-semibold">Lengkapi No. SIM & Plat Nomor di profil Anda agar admin dapat melakukan verifikasi.</p>
                            </div>
                        </div>
                        <button onclick="toggleMobileSettings(); switchDrawerToEdit();" class="w-full py-2.5 rounded-xl bg-amber-500 hover:bg-amber-600 text-white text-xs font-black shadow-sm transition cursor-pointer text-center">
                            Lengkapi Profil Sekarang
                        </button>
                    </div>
                @endif

                <!-- Removed Completed Tasks History -->

                <!-- Panel Daftar Tugas (Tugas Masuk & Aktif) -->
                <div class="mt-8 space-y-4">
                    <h3 class="text-[10px] font-black text-slate-700 tracking-widest uppercase pl-1">
                        Daftar Tugas Anda
                    </h3>
                    <div class="space-y-4">
                        @forelse($pendingTasks as $task)
                            @php
                                $isPending = $task->tracking_status === 'Menunggu Konfirmasi Driver';
                                $borderClass = $isPending ? 'border-amber-200 shadow-amber-500/5 bg-amber-50/10' : 'border-emerald-200 shadow-emerald-500/5 bg-emerald-50/10';
                                $badgeClass = $isPending ? 'text-amber-600 bg-amber-50 border-amber-500/10' : 'text-emerald-600 bg-emerald-50 border-emerald-500/10';
                                $badgeText = $isPending ? 'Menunggu Konfirmasi' : 'Tugas Aktif';
                            @endphp
                            <div class="w-full p-5 bg-white border {{ $borderClass }} rounded-[1.5rem] text-left shadow-md flex flex-col gap-4 relative overflow-hidden group transition duration-300">
                                <div class="absolute -right-6 -bottom-6 w-24 h-24 rounded-full {{ $isPending ? 'bg-amber-50/30' : 'bg-emerald-50/30' }} group-hover:scale-110 transition duration-300 pointer-events-none"></div>
                                <div class="flex items-center justify-between min-w-0">
                                    <span class="inline-block text-[8px] font-black {{ $badgeClass }} border px-2.5 py-1 rounded-md uppercase leading-none">{{ $badgeText }}</span>
                                    <span class="text-[9px] text-slate-400 font-bold">#{{ $task->order_id }}</span>
                                </div>
                                <div class="min-w-0 space-y-1 z-10">
                                    <span class="text-[8px] font-black text-slate-400 uppercase tracking-widest block mb-1">Tujuan Penerima</span>
                                    <h4 class="text-sm font-black text-slate-800 leading-none truncate">{{ $task->receiver_name }}</h4>
                                    <p class="text-[11px] text-slate-500 font-semibold truncate mt-1.5">{{ $task->receiver_address }}</p>
                                    <p class="text-[9px] text-slate-400 mt-2.5 font-bold">Paket: {{ $task->package_description }} ({{ $task->package_weight }} kg)</p>
                                </div>
                                <div class="z-10 flex gap-2 pt-1">
                                    @if($isPending)
                                        <button onclick="acceptIncomingTask('{{ $task->order_id }}', this)" class="flex-1 py-3 rounded-xl bg-emerald-600 hover:bg-emerald-750 text-white text-[11px] font-black shadow-lg shadow-emerald-500/20 transition cursor-pointer text-center flex items-center justify-center gap-1.5">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                                            Terima Tugas
                                        </button>
                                    @else
                                        <div class="flex-1 py-3 rounded-xl bg-emerald-50 border border-emerald-250 text-emerald-700 text-[11px] font-black text-center flex items-center justify-center gap-1.5 pointer-events-none select-none">
                                            <span class="relative flex h-2 w-2">
                                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                                <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                                            </span>
                                            Sedang Diantar
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="w-full p-6 bg-white/60 border border-slate-200 rounded-[1.5rem] text-center shadow-md flex flex-col items-center justify-center gap-2">
                                <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                                </svg>
                                <p class="text-[11px] text-slate-400 font-bold">Belum ada tugas diberikan.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- RIGHT COLUMN: Active Task Map & Details -->
        <div class="w-full lg:w-[58%] xl:w-[62%] px-5 pt-5 pb-24 sm:px-8 sm:pt-8 sm:pb-24 lg:p-12 xl:p-16 flex flex-col justify-start items-center lg:items-start xl:pl-20 overflow-y-auto lg:h-full relative z-10 transition-colors duration-300">
            
            <div class="w-full max-w-2xl mx-auto lg:mx-0">
                @if(count($activeTasks) > 0)
                    @php $activeTask = $activeTasks[0]; @endphp
                    <div class="flex justify-between items-center mb-6 pl-2">
                        <h3 class="text-lg font-black text-slate-800 tracking-tight flex items-center gap-3">
                            <span class="w-3 h-3 rounded-full bg-emerald-500 shadow-[0_0_10px_rgba(16,185,129,0.5)] animate-pulse"></span>
                            Tugas Aktif Anda
                        </h3>
                    </div>

                    <!-- Glass Card -->
                    <div class="bg-white/80 backdrop-blur-xl rounded-[2rem] sm:rounded-[2.5rem] shadow-[0_15px_40px_rgba(0,0,0,0.06)] overflow-hidden border border-slate-200/60 transition duration-500 hover:shadow-[0_20px_50px_rgba(0,0,0,0.1)] w-full">
                        
                        <!-- Map Container -->
                        <div class="h-56 sm:h-72 w-full relative overflow-hidden z-10 border-b border-slate-100">
                            <div id="map" class="absolute inset-0 z-0"></div>
                            <div class="absolute inset-0 bg-gradient-to-t from-slate-900/30 via-transparent to-transparent pointer-events-none z-10"></div>
                            
                            <div class="absolute top-4 right-4 z-20">
                                <button id="simulateBtn" onclick="toggleRouteSimulation()" class="px-3.5 py-1.5 sm:px-4 sm:py-2 bg-slate-900/90 backdrop-blur-md text-white rounded-lg sm:rounded-xl text-[9px] sm:text-[10px] font-black shadow-lg border border-slate-700/50 hover:bg-emerald-600 transition flex items-center gap-1.5 sm:gap-2 cursor-pointer">
                                    <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5.25 5.653c0-.856.917-1.398 1.667-.986l11.54 6.348a1.125 1.125 0 010 1.971l-11.54 6.347a1.125 1.125 0 01-1.667-.985V5.653z"/></svg>
                                    <span class="hidden sm:inline">Simulasikan GPS</span>
                                    <span class="sm:hidden">GPS</span>
                                </button>
                            </div>
                        </div>

                        <!-- Details Section -->
                        <div class="p-6 sm:p-10 -mt-6 sm:-mt-8 relative bg-white rounded-t-[2.5rem] sm:rounded-t-[3rem] z-20 shadow-[0_-10px_20px_rgba(0,0,0,0.02)] border-t border-slate-100/50 transition-colors duration-300">
                            
                            <!-- Header & Status Update Dropdown -->
                            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-end mb-6 sm:mb-8 border-b border-slate-100/80 pb-5 sm:pb-6 gap-3 sm:gap-4 transition-colors duration-300">
                                <div>
                                    <div class="flex items-center gap-1.5 mb-1.5 sm:mb-2">
                                        <span class="text-[8px] sm:text-[9px] font-black text-emerald-700 uppercase tracking-widest bg-emerald-50 px-2 sm:px-2.5 py-1 rounded-md border border-emerald-500/10">Resi: {{ $activeTask->order_id }}</span>
                                    </div>
                                    <h4 class="text-lg sm:text-2xl font-black text-slate-800 tracking-tight leading-snug">
                                        Status Pengiriman
                                    </h4>
                                    <p class="text-[9px] sm:text-[10px] text-slate-400 font-medium mt-0.5 sm:mt-1 uppercase tracking-widest">Pilih status perbarui otomatis</p>
                                </div>
                                <div class="shrink-0 w-full sm:w-auto">
                                    <select id="taskStatus" onchange="updateTaskStatus('{{ $activeTask->order_id }}', this.value)" class="w-full sm:w-auto bg-slate-50 border border-slate-200/80 rounded-xl px-3 sm:px-4 py-2.5 sm:py-3 text-[11px] sm:text-xs font-black text-slate-800 focus:outline-none focus:border-emerald-500 focus:ring-4 focus:ring-emerald-50 transition duration-300 cursor-pointer shadow-sm appearance-none pr-9 sm:pr-10" style="background-image: url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20width%3D%2212%22%20height%3D%228%22%20viewBox%3D%220%200%2012%208%22%20fill%3D%22none%22%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%3E%3Cpath%20d%3D%22M1%201.5L6%206.5L11%201.5%22%20stroke%3D%22%2394A3B8%22%20stroke-width%3D%222%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%2F%3E%3C%2Fsvg%3E'); background-repeat: no-repeat; background-position: right 1rem center;">
                                        <option value="Driver Terpilih - Bersiap Meluncur" {{ $activeTask->tracking_status == 'Driver Terpilih - Bersiap Meluncur' ? 'selected' : '' }}>Bersiap Meluncur</option>
                                        <option value="Diperjalanan - Transit Hub" {{ $activeTask->tracking_status == 'Diperjalanan - Transit Hub' ? 'selected' : '' }}>Diperjalanan (Transit)</option>
                                        <option value="Kurir Menuju Lokasi Penerima" {{ $activeTask->tracking_status == 'Kurir Menuju Lokasi Penerima' ? 'selected' : '' }}>Menuju Lokasi</option>
                                        <option value="Pesanan Diterima - Selesai" {{ $activeTask->tracking_status == 'Pesanan Diterima - Selesai' ? 'selected' : '' }}>Pesanan Diterima</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Specs info -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-5 bg-slate-50/50 p-5 sm:p-6 rounded-[1.5rem] sm:rounded-3xl border border-slate-100 text-[11px] sm:text-xs transition-colors duration-300">
                                <div>
                                    <h4 class="font-black text-emerald-600 mb-1.5 sm:mb-2 uppercase tracking-widest text-[8px] sm:text-[9px] flex items-center gap-1 sm:gap-1.5"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/></svg> Tujuan Pengiriman</h4>
                                    <div class="space-y-1.5 sm:space-y-2 pl-4 sm:pl-5 border-l-2 border-emerald-100">
                                        <p><span class="font-black text-slate-800 text-[13px] sm:text-sm">{{ $activeTask->receiver_name }}</span> <span class="text-slate-400 font-medium text-[9px] sm:text-[10px] block mt-0.5">{{ $activeTask->receiver_phone }}</span></p>
                                        <p class="text-slate-600 font-medium leading-relaxed mt-1.5 sm:mt-2">{{ $activeTask->receiver_address }}</p>
                                        <div class="pt-1.5 sm:pt-2">
                                            <a href="tel:{{ $activeTask->receiver_phone }}" class="inline-flex items-center gap-1 sm:gap-1.5 px-2.5 py-1 sm:px-3 sm:py-1.5 bg-white border border-slate-200 text-emerald-600 rounded-lg text-[9px] sm:text-[10px] font-black shadow-sm hover:bg-emerald-50 transition">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.864-1.051l-3.21-.535a1.125 1.125 0 00-1.227.598l-.959 1.918c-2.43-1.16-4.4-3.13-5.56-5.56l1.918-.959a1.125 1.125 0 00.598-1.227l-.535-3.21C7.714 2.601 7.265 2.25 6.75 2.25H5.372A2.25 2.25 0 003 4.408c-.085.513-.136 1.04-.15 1.574"/></svg>
                                                Hubungi
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <h4 class="font-black text-amber-500 mb-1.5 sm:mb-2 uppercase tracking-widest text-[8px] sm:text-[9px] flex items-center gap-1 sm:gap-1.5"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 7.5l-9-5.25L3 7.5m18 0l-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9"/></svg> Detail Paket</h4>
                                    <div class="space-y-1.5 sm:space-y-2 pl-4 sm:pl-5 border-l-2 border-amber-100">
                                        <p class="text-slate-800 font-bold leading-relaxed">{{ $activeTask->package_description }}</p>
                                        <p class="inline-block px-2 sm:px-2.5 py-0.5 sm:py-1 rounded bg-slate-100 text-slate-600 font-black text-[9px] sm:text-[10px]">Berat: {{ $activeTask->package_weight }} kg</p>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                @else
                    <!-- Empty State -->
                    <div class="bg-white/80 backdrop-blur-xl rounded-[2rem] sm:rounded-[3rem] p-8 sm:p-12 border border-slate-200/60 shadow-[0_15px_40px_rgba(0,0,0,0.05)] h-full min-h-[350px] sm:min-h-[450px] flex flex-col justify-center items-center text-center transition-colors duration-300 w-full">
                        <div class="w-20 h-20 sm:w-24 sm:h-24 rounded-full bg-gradient-to-tr from-emerald-100 to-teal-50 text-emerald-400 flex items-center justify-center shadow-inner mb-6 sm:mb-8 border border-emerald-200/50 relative">
                            @if(Auth::user()->status === 'active')
                                <!-- Radar pulse animation for empty state -->
                                <span class="absolute flex h-20 w-20 sm:h-24 sm:w-24">
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-20"></span>
                                </span>
                                <svg class="w-8 h-8 sm:w-10 sm:h-10 relative z-10" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/></svg>
                            @else
                                <svg class="w-8 h-8 sm:w-10 sm:h-10 text-amber-500 relative z-10" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25z" /></svg>
                            @endif
                        </div>
                        <h3 class="text-xl sm:text-2xl font-black text-slate-800 mb-2 sm:mb-3 tracking-tight">
                            @if(Auth::user()->status === 'active')
                                @if(count($pendingTasks) > 0)
                                    Konfirmasi Tugas Masuk
                                @else
                                    Memantau Area...
                                @endif
                            @else
                                Akun Belum Aktif
                            @endif
                        </h3>
                        <p class="text-slate-500 text-xs sm:text-sm font-medium max-w-[280px] sm:max-w-sm leading-relaxed">
                            @if(Auth::user()->status === 'active')
                                @if(count($pendingTasks) > 0)
                                    Anda memiliki penugasan baru dari Admin. Silakan klik tombol <strong>Terima Tugas</strong> pada panel sebelah kiri untuk memulai pengiriman.
                                @else
                                    Sistem pelacakan GPS Anda aktif. Menunggu penugasan pengiriman baru dari Admin.
                                @endif
                            @else
                                Akun Anda belum aktif. Silakan lengkapi data profil Anda (Nomor SIM & Plat Nomor Kendaraan) di panel profil agar admin dapat memverifikasi akun Anda.
                            @endif
                        </p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Mobile Settings Drawer -->
    <div id="mobileSettingsDrawer" class="fixed inset-0 z-50 hidden">
        <!-- Backdrop -->
        <div onclick="toggleMobileSettings()" class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity"></div>
        
        <!-- Drawer Content -->
        <div class="absolute bottom-0 left-0 right-0 md:left-auto md:right-6 md:bottom-6 md:w-[420px] bg-white rounded-t-[2.5rem] md:rounded-[2rem] p-8 shadow-2xl transition-transform transform translate-y-full duration-300 z-50 max-h-[90vh] overflow-y-auto transition-colors duration-300" id="drawerContent">
            <div class="w-12 h-1.5 bg-slate-200 rounded-full mx-auto mb-6"></div>
            <h3 class="text-xl font-black text-slate-800 mb-6 text-center tracking-tight" id="drawerTitle">Pengaturan Kurir</h3>
            
            <!-- VIEW SUMMARY -->
            <div id="drawerSummaryView" class="space-y-5">
                <div class="p-5 bg-slate-50 rounded-3xl flex items-center gap-5 border border-slate-100 shadow-sm transition-colors duration-300">
                    <div class="w-14 h-14 min-w-[3.5rem] min-h-[3.5rem] max-w-[3.5rem] max-h-[3.5rem] rounded-full bg-emerald-500 text-white flex items-center justify-center font-black text-2xl uppercase shrink-0 shadow-lg shadow-emerald-500/30">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                    <div class="min-w-0 flex-1">
                        <h4 id="drawerName" class="font-black text-slate-800 text-base truncate">{{ Auth::user()->name }}</h4>
                        <p id="drawerEmail" class="text-xs text-slate-500 font-medium truncate mt-0.5">{{ Auth::user()->email }}</p>
                        <p id="drawerPhone" class="text-[11px] text-emerald-600 font-bold mt-1 truncate">{{ Auth::user()->phone ?? 'Telepon belum diatur' }}</p>
                    </div>
                </div>

                <div class="p-5 bg-emerald-50/50 rounded-3xl border border-emerald-100/60 shadow-sm transition-colors duration-300">
                    <span class="block text-[10px] font-black text-emerald-600 uppercase tracking-widest mb-2 flex items-center gap-2"><svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/></svg> Area Operasional</span>
                    <p id="drawerAddress" class="text-xs font-semibold text-slate-700 leading-relaxed pl-6 border-l-2 border-emerald-200">
                        {{ Auth::user()->address ?? 'Alamat operasional belum diatur.' }}
                    </p>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="p-4 bg-slate-50 rounded-2xl border border-slate-200/40 shadow-sm transition-colors duration-300">
                        <span class="block text-[9px] font-black text-slate-500 uppercase tracking-widest mb-1 pl-1">Nomor SIM</span>
                        <p id="drawerLicense" class="text-xs font-black text-slate-800 pl-1">
                            {{ Auth::user()->license_number ?? 'Belum diatur' }}
                        </p>
                    </div>
                    <div class="p-4 bg-slate-50 rounded-2xl border border-slate-200/40 shadow-sm transition-colors duration-300">
                        <span class="block text-[9px] font-black text-slate-500 uppercase tracking-widest mb-1 pl-1">Plat Nomor</span>
                        <p id="drawerVehicle" class="text-xs font-black text-slate-800 pl-1">
                            {{ Auth::user()->vehicle_number ?? 'Belum diatur' }}
                        </p>
                    </div>
                </div>

                <button onclick="switchDrawerToEdit()" class="w-full flex items-center justify-between p-5 bg-white hover:bg-slate-50 rounded-2xl transition duration-300 text-left cursor-pointer border border-slate-200 shadow-sm hover:shadow-md group transition-colors duration-300">
                    <div class="flex items-center gap-4 text-slate-700">
                        <div class="p-2.5 rounded-xl bg-slate-100 text-slate-600 group-hover:bg-emerald-100 group-hover:text-emerald-600 transition">
                            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" /></svg>
                        </div>
                        <span class="text-sm font-black text-slate-800">Ubah Profil Data</span>
                    </div>
                    <svg class="w-5 h-5 text-slate-400 group-hover:text-emerald-500 transition transform group-hover:translate-x-1" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
                </button>
                
                <form method="POST" action="{{ route('logout') }}" class="m-0 pt-2 border-t border-slate-100">
                    @csrf
                    <button type="submit" class="w-full flex items-center justify-center p-4 bg-red-50 hover:bg-red-500 rounded-2xl transition duration-300 text-left cursor-pointer border border-red-100 group text-red-600 hover:text-white">
                        <div class="flex items-center gap-2 font-black text-sm">
                            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0 3 3m-3-3h12.75" /></svg>
                            <span>Keluar dari Sistem</span>
                        </div>
                    </button>
                </form>
            </div>

            <!-- VIEW EDIT (AJAX Form) -->
            <div id="drawerEditView" class="hidden space-y-4">
                <form id="profileForm" onsubmit="saveProfileChanges(event)">
                    <div class="space-y-4 mb-8">
                        <div>
                            <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1.5 pl-1">Nama Lengkap</label>
                            <input type="text" id="editName" value="{{ Auth::user()->name }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-800 focus:outline-none focus:border-emerald-500 focus:ring-4 focus:ring-emerald-50 transition" required>
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1.5 pl-1">Alamat Email</label>
                            <input type="email" id="editEmail" value="{{ Auth::user()->email }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-800 focus:outline-none focus:border-emerald-500 focus:ring-4 focus:ring-emerald-50 transition" required>
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1.5 pl-1">Nomor Telepon</label>
                            <input type="text" id="editPhone" value="{{ Auth::user()->phone ?? '' }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-800 focus:outline-none focus:border-emerald-500 focus:ring-4 focus:ring-emerald-50 transition" placeholder="Contoh: 08123456789">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1.5 pl-1">Alamat Operasional Utama</label>
                            <textarea id="editAddress" rows="3" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-800 focus:outline-none focus:border-emerald-500 focus:ring-4 focus:ring-emerald-50 transition">{{ Auth::user()->address ?? '' }}</textarea>
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1.5 pl-1">Nomor SIM</label>
                            <input type="text" id="editLicense" value="{{ Auth::user()->license_number ?? '' }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-800 focus:outline-none focus:border-emerald-500 focus:ring-4 focus:ring-emerald-50 transition" placeholder="Contoh: SIM-123456">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1.5 pl-1">Plat Nomor Kendaraan</label>
                            <input type="text" id="editVehicle" value="{{ Auth::user()->vehicle_number ?? '' }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-800 focus:outline-none focus:border-emerald-500 focus:ring-4 focus:ring-emerald-50 transition" placeholder="Contoh: B 1234 ABC">
                        </div>
                    </div>
                    
                    <div class="flex gap-3">
                        <button type="button" onclick="switchDrawerToSummary()" class="flex-1 bg-white border border-slate-200 text-slate-600 hover:bg-slate-100 py-3.5 rounded-xl text-sm font-black transition duration-200 cursor-pointer shadow-sm hover:shadow">Batal</button>
                        <button type="submit" id="saveProfileBtn" class="flex-1 bg-emerald-600 text-white hover:bg-emerald-700 py-3.5 rounded-xl text-sm font-black transition duration-200 shadow-lg shadow-emerald-500/30 cursor-pointer flex justify-center items-center gap-2">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Toast Container -->
    <div id="toastContainer" class="fixed top-4 md:top-6 left-1/2 md:left-auto md:right-6 -translate-x-1/2 md:translate-x-0 z-[9999] flex flex-col gap-3 w-full max-w-[calc(100vw-2rem)] sm:max-w-sm px-4 pointer-events-none transition-all duration-300"></div>

    <script>
        // === Dark Mode Logic ===
        function toggleDarkMode() {
            const htmlEl = document.documentElement;
            if (htmlEl.classList.contains('dark-theme')) {
                htmlEl.classList.remove('dark-theme');
                localStorage.setItem('driverTheme', 'light');
                document.getElementById('sunIcon').classList.add('hidden');
                document.getElementById('moonIcon').classList.remove('hidden');
                showToast('Tema Terang diaktifkan.', 'info');
            } else {
                htmlEl.classList.add('dark-theme');
                localStorage.setItem('driverTheme', 'dark');
                document.getElementById('moonIcon').classList.add('hidden');
                document.getElementById('sunIcon').classList.remove('hidden');
                showToast('Tema Gelap diaktifkan.', 'info');
            }
        }

        // Init Dark Mode from LocalStorage
        document.addEventListener('DOMContentLoaded', () => {
            if (localStorage.getItem('driverTheme') === 'dark') {
                document.documentElement.classList.add('dark-theme');
                document.getElementById('moonIcon').classList.add('hidden');
                document.getElementById('sunIcon').classList.remove('hidden');
            }
        });

        // === Notifications Logic ===
        let notificationsOpen = false;

        function toggleNotifications() {
            const dropdown = document.getElementById('notificationsDropdown');
            notificationsOpen = !notificationsOpen;
            
            if (notificationsOpen) {
                dropdown.classList.remove('hidden');
                // Use a slight timeout to ensure transition plays
                setTimeout(() => {
                    dropdown.classList.add('scale-100', 'opacity-100');
                }, 10);
            } else {
                dropdown.classList.remove('scale-100', 'opacity-100');
                setTimeout(() => {
                    dropdown.classList.add('hidden');
                }, 200);
            }
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            const dropdown = document.getElementById('notificationsDropdown');
            const toggleBtn = dropdown.previousElementSibling;
            if (notificationsOpen && !dropdown.contains(event.target) && !toggleBtn.contains(event.target)) {
                toggleNotifications();
            }
        });

        function switchNotifTab(tabId) {
            const notifTab = document.getElementById('tabContent-notif');
            const riwayatTab = document.getElementById('tabContent-riwayat');
            const btnNotif = document.getElementById('tabNotifBtn');
            const btnRiwayat = document.getElementById('tabShipmentBtn');

            if (tabId === 'notif') {
                notifTab.classList.remove('hidden');
                riwayatTab.classList.add('hidden');
                
                // Style Active Notif
                btnNotif.classList.add('bg-white', 'text-emerald-700', 'shadow-sm', 'border-slate-200/40');
                btnNotif.classList.remove('text-slate-400', 'border-transparent');
                
                // Style Inactive Riwayat
                btnRiwayat.classList.remove('bg-white', 'text-emerald-700', 'shadow-sm', 'border-slate-200/40');
                btnRiwayat.classList.add('text-slate-400', 'border-transparent');
            } else {
                notifTab.classList.add('hidden');
                riwayatTab.classList.remove('hidden');
                
                // Style Inactive Notif
                btnNotif.classList.remove('bg-white', 'text-emerald-700', 'shadow-sm', 'border-slate-200/40');
                btnNotif.classList.add('text-slate-400', 'border-transparent');
                
                // Style Active Riwayat
                btnRiwayat.classList.add('bg-white', 'text-emerald-700', 'shadow-sm', 'border-slate-200/40');
                btnRiwayat.classList.remove('text-slate-400', 'border-transparent');
            }
        }

        function fetchNotifications() {
            fetch('/api/v1/notifications', {
                headers: { 'Accept': 'application/json' }
            })
            .then(res => res.json())
            .then(data => {
                if (data.error) return;
                
                const notifList = document.getElementById('notificationsList');
                const badge = document.getElementById('notifBadge');
                const countText = document.getElementById('notifCountText');
                
                // Update badge
                if (data.unread_count > 0) {
                    badge.classList.remove('hidden');
                    countText.classList.remove('hidden');
                    countText.innerText = `${data.unread_count} Baru`;
                } else {
                    badge.classList.add('hidden');
                    countText.classList.add('hidden');
                }

                // Render list
                notifList.innerHTML = '';
                if (data.notifications && data.notifications.length > 0) {
                    data.notifications.forEach(notif => {
                        const isUnread = notif.read_at === null;
                        const bgClass = isUnread ? 'bg-emerald-50/50' : 'bg-white';
                        const dot = isUnread ? '<span class="w-2 h-2 rounded-full bg-red-500 shrink-0 mt-1.5"></span>' : '';
                        
                        // Parse date
                        const d = new Date(notif.created_at);
                        const timeStr = d.toLocaleTimeString('id-ID', { hour: '2-digit', minute:'2-digit' });
                        
                        notifList.innerHTML += `
                            <div onclick="markNotifAsRead(${notif.id})" class="w-full p-3.5 bg-slate-50 hover:bg-slate-100 border border-slate-200/60 rounded-xl text-left transition flex items-center gap-3 cursor-pointer group">
                                <div class="w-7.5 h-7.5 rounded-lg ${dot ? 'bg-emerald-100 text-emerald-600 border border-emerald-200' : 'bg-slate-100 text-slate-400 border border-slate-200'} flex items-center justify-center shrink-0">
                                    <svg class="w-3.5 h-3.5" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" /></svg>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <span class="block text-[10px] font-black ${dot ? 'text-slate-800' : 'text-slate-600'} leading-none group-hover:text-emerald-600 transition truncate">${notif.title}</span>
                                    <span class="block text-[9px] text-slate-400 mt-1 leading-snug line-clamp-2">${notif.body}</span>
                                    <span class="text-[8px] font-bold text-emerald-500 mt-1.5 block flex items-center gap-1">${timeStr}</span>
                                </div>
                            </div>
                        `;
                    });
                } else {
                    notifList.innerHTML = `
                        <div class="p-8 text-center text-slate-400 text-xs font-medium flex flex-col items-center gap-3">
                            <svg class="w-10 h-10 text-slate-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
                            Belum ada notifikasi
                        </div>
                    `;
                }
            })
            .catch(err => console.error('Error fetching notifications:', err));
        }

        function markNotifAsRead(id) {
            const token = document.querySelector('meta[name="csrf-token"]').content;
            fetch(`/api/v1/notifications/${id}/read`, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': token, 'Accept': 'application/json' }
            }).then(() => {
                fetchNotifications(); // Reload list
            });
        }

        // Fetch notifications on load
        document.addEventListener('DOMContentLoaded', fetchNotifications);
        setInterval(fetchNotifications, 15000); // Polling every 15s

        // === Drawer & Profile Logic ===
        function toggleMobileSettings() {
            const drawer = document.getElementById('mobileSettingsDrawer');
            const content = document.getElementById('drawerContent');
            if (drawer.classList.contains('hidden')) {
                drawer.classList.remove('hidden');
                setTimeout(() => content.classList.remove('translate-y-full'), 10);
            } else {
                content.classList.add('translate-y-full');
                setTimeout(() => drawer.classList.add('hidden'), 300);
            }
        }

        function switchDrawerToSummary() {
            document.getElementById('drawerTitle').innerText = 'Pengaturan Kurir';
            document.getElementById('drawerSummaryView').classList.remove('hidden');
            document.getElementById('drawerEditView').classList.add('hidden');
        }

        function switchDrawerToEdit() {
            document.getElementById('drawerTitle').innerText = 'Ubah Profil';
            document.getElementById('drawerSummaryView').classList.add('hidden');
            document.getElementById('drawerEditView').classList.remove('hidden');
        }

        async function saveProfileChanges(e) {
            e.preventDefault();
            const btn = document.getElementById('saveProfileBtn');
            const originalText = btn.innerHTML;
            btn.innerHTML = `<svg class="animate-spin w-5 h-5 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Menyimpan...`;
            btn.disabled = true;

            const name = document.getElementById('editName').value;
            const email = document.getElementById('editEmail').value;
            const phone = document.getElementById('editPhone').value;
            const address = document.getElementById('editAddress').value;
            const license_number = document.getElementById('editLicense').value;
            const vehicle_number = document.getElementById('editVehicle').value;

            try {
                const token = document.querySelector('meta[name="csrf-token"]').content;
                const response = await fetch('/driver/profile/update', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': token },
                    body: JSON.stringify({ name, email, phone, address, license_number, vehicle_number })
                });

                const data = await response.json();
                
                if (response.ok) {
                    document.getElementById('drawerName').innerText = data.data.name;
                    document.getElementById('drawerEmail').innerText = data.data.email;
                    document.getElementById('drawerPhone').innerText = data.data.phone || 'Telepon belum diatur';
                    document.getElementById('drawerAddress').innerText = data.data.address || 'Alamat operasional belum diatur.';
                    document.getElementById('drawerLicense').innerText = data.data.license_number || 'Belum diatur';
                    document.getElementById('drawerVehicle').innerText = data.data.vehicle_number || 'Belum diatur';
                    
                    showToast(data.message, 'success');
                    switchDrawerToSummary();
                } else {
                    showToast('Gagal menyimpan profil: ' + (data.message || 'Error validasi'), 'error');
                }
            } catch (error) {
                showToast('Koneksi terputus.', 'error');
            } finally {
                btn.innerHTML = originalText;
                btn.disabled = false;
            }
        }

        // === Map & Update Logic profile===
        @if(count($activeTasks) > 0)
            const orderId = '{{ $activeTask->order_id }}';
            const senderAddress = '{{ $activeTask->sender_address }}';
            const receiverAddress = '{{ $activeTask->receiver_address }}';

            // Menggunakan titik koordinat valid (jalan raya di Jakarta) agar OSRM selalu berhasil membuat rute
            const validRoadPoints = [
                [-6.183000, 106.820000], // Area Monas / Thamrin
                [-6.205000, 106.822000], // Sudirman
                [-6.225000, 106.830000], // Kuningan
                [-6.245000, 106.805000], // Blok M
                [-6.155000, 106.840000]  // Kemayoran
            ];

            function getPointIndex(str) {
                let hash = 0;
                if (!str) return 0;
                for (let i = 0; i < str.length; i++) {
                    hash = str.charCodeAt(i) + ((hash << 5) - hash);
                }
                return Math.abs(hash) % validRoadPoints.length;
            }

            const startIdx = getPointIndex(senderAddress);
            let endIdx = getPointIndex(receiverAddress);
            if (endIdx === startIdx) endIdx = (startIdx + 1) % validRoadPoints.length;

            const startCoords = validRoadPoints[startIdx];
            const endCoords = validRoadPoints[endIdx];

            let currentLat = {{ $activeTask->latitude ?? 'null' }} || startCoords[0];
            let currentLng = {{ $activeTask->longitude ?? 'null' }} || startCoords[1];

            let map = null;
            let routingControl = null;
            let packageMarker = null;

            let routeCoordinates = [];
            let routeIndex = 0;
            let simulationInterval = null;

            document.addEventListener('DOMContentLoaded', () => {
                map = L.map('map', { zoomControl: false }).setView([currentLat, currentLng], 12);
                
                L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
                    attribution: '&copy; OpenStreetMap'
                }).addTo(map);

                const startIcon = L.divIcon({
                    className: 'custom-start-icon',
                    html: `<div class="w-5 h-5 bg-indigo-500 rounded-full border-2 border-white flex items-center justify-center shadow-lg"><span class="w-1.5 h-1.5 bg-white rounded-full"></span></div>`,
                    iconSize: [20, 20],
                    iconAnchor: [10, 10]
                });

                const endIcon = L.divIcon({
                    className: 'custom-end-icon',
                    html: `<div class="w-5 h-5 bg-rose-500 rounded-full border-2 border-white flex items-center justify-center shadow-lg"><span class="w-1.5 h-1.5 bg-white rounded-full"></span></div>`,
                    iconSize: [20, 20],
                    iconAnchor: [10, 10]
                });

                const packageIcon = L.divIcon({
                    className: 'custom-package-icon',
                    html: `<div class="relative w-8 h-8 bg-gradient-to-tr from-emerald-400 to-teal-500 rounded-full shadow-[0_0_20px_rgba(16,185,129,0.7)] border-2 border-white flex items-center justify-center">
                            <div class="absolute inset-0 bg-emerald-400 rounded-full animate-ping opacity-75"></div>
                            <svg class="w-4 h-4 text-white z-10" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 0 1-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h1.125a1.125 1.125 0 0 0 1.125-1.125V9.75M3.82 8.167a3.75 3.75 0 0 1 5.377-2.833l10.932 5.466M3.82 8.167l-.149.277a3.75 3.75 0 0 0 2.215 5.059m-.149-5.336L3.728 8.11a3.75 3.75 0 0 0-.256 3.118m0-3.118a3.733 3.733 0 0 1 1.012-.083M9.75 9.75c0 .414-.168.788-.439 1.061m0 0a1.5 1.5 0 0 1-2.122 0m2.122 0h6.122m-8.244 0a1.5 1.5 0 0 1 0-2.122m0 0a1.5 1.5 0 0 1 2.122 0m-2.122 0h.008m13.492 3a3.375 3.375 0 0 1-3.375-3.375v-.154M12 21a9.003 9.003 0 0 0 8.354-5.646M12 21a9 9 0 0 1-8.354-5.646"/></svg>
                           </div>`,
                    iconSize: [32, 32],
                    iconAnchor: [16, 16]
                });

                packageMarker = L.marker([currentLat, currentLng], { icon: packageIcon }).addTo(map);

                routingControl = L.Routing.control({
                    waypoints: [ L.latLng(startCoords[0], startCoords[1]), L.latLng(endCoords[0], endCoords[1]) ],
                    routeWhileDragging: false, addWaypoints: false, draggableWaypoints: false, fitSelectedRoutes: true, show: false,
                    createMarker: function(i, wp, n) {
                        if (i === 0) return L.marker(wp.latLng, { icon: startIcon });
                        else if (i === n - 1) return L.marker(wp.latLng, { icon: endIcon });
                        return null;
                    },
                    lineOptions: { styles: [{ color: '#10b981', opacity: 0.8, weight: 4 }] }
                }).addTo(map);

                (function() {
                    const originalWarn = console.warn;
                    console.warn = function(...args) {
                        if (args[0] && typeof args[0] === 'string' && args[0].includes("OSRM's demo server")) return;
                        originalWarn.apply(console, args);
                    };
                })();

                routingControl.on('routesfound', function(e) {
                    routeCoordinates = e.routes[0].coordinates;
                    routeIndex = 0;
                });

                map.on('click', function(e) {
                    if (simulationInterval) {
                        showToast('Simulasi sedang berjalan.', 'error');
                        return;
                    }
                    currentLat = e.latlng.lat;
                    currentLng = e.latlng.lng;
                    packageMarker.setLatLng([currentLat, currentLng]);
                    saveLocationToServer(currentLat, currentLng);
                });
            });

            function saveLocationToServer(lat, lng) {
                const token = document.querySelector('meta[name="csrf-token"]').content;
                fetch(`/driver/task/${orderId}/update-location`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': token },
                    body: JSON.stringify({ latitude: lat, longitude: lng })
                }).then(res => res.json()).then(data => {
                    if (data.status === 'Success') showToast('Lokasi diperbarui!', 'success');
                    else showToast(data.message, 'error');
                });
            }

            function toggleRouteSimulation() {
                const simulateBtn = document.getElementById('simulateBtn');
                if (simulationInterval) {
                    clearInterval(simulationInterval);
                    simulationInterval = null;
                    simulateBtn.innerHTML = `<svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5.25 5.653c0-.856.917-1.398 1.667-.986l11.54 6.348a1.125 1.125 0 010 1.971l-11.54 6.347a1.125 1.125 0 01-1.667-.985V5.653z"/></svg>Simulasikan GPS`;
                    simulateBtn.className = 'px-4 py-2 bg-slate-900/90 backdrop-blur-md text-white rounded-xl text-[10px] font-black shadow-lg border border-slate-700/50 hover:bg-emerald-600 transition flex items-center gap-2 cursor-pointer';
                    showToast('Simulasi dihentikan.', 'info');
                } else {
                    if (routeCoordinates.length === 0) {
                        showToast('Tunggu OSRM load rute.', 'error');
                        return;
                    }
                    routeIndex = 0;
                    simulateBtn.innerHTML = `<svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 5.25v13.5m-7.5-13.5v13.5"/></svg>Stop GPS`;
                    simulateBtn.className = 'px-4 py-2 bg-red-600 text-white rounded-xl text-[10px] font-black shadow-lg border border-red-500 hover:bg-red-700 transition flex items-center gap-2 cursor-pointer';
                    
                    document.getElementById('taskStatus').value = 'Diperjalanan - Transit Hub';
                    updateTaskStatus(orderId, 'Diperjalanan - Transit Hub');

                    simulationInterval = setInterval(() => {
                        if (routeIndex >= routeCoordinates.length) {
                            clearInterval(simulationInterval);
                            simulationInterval = null;
                            simulateBtn.innerHTML = `<svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5.25 5.653c0-.856.917-1.398 1.667-.986l11.54 6.348a1.125 1.125 0 010 1.971l-11.54 6.347a1.125 1.125 0 01-1.667-.985V5.653z"/></svg>Simulasikan GPS`;
                            simulateBtn.className = 'px-4 py-2 bg-slate-900/90 backdrop-blur-md text-white rounded-xl text-[10px] font-black shadow-lg border border-slate-700/50 hover:bg-emerald-600 transition flex items-center gap-2 cursor-pointer';
                            packageMarker.setLatLng([endCoords[0], endCoords[1]]);
                            saveLocationToServer(endCoords[0], endCoords[1]);
                            document.getElementById('taskStatus').value = 'Pesanan Diterima - Selesai';
                            updateTaskStatus(orderId, 'Pesanan Diterima - Selesai');
                            return;
                        }
                        const c = routeCoordinates[routeIndex];
                        packageMarker.setLatLng(c);
                        saveLocationToServer(c.lat, c.lng);
                        routeIndex += Math.ceil(routeCoordinates.length / 30);
                        if (routeIndex >= routeCoordinates.length) routeIndex = routeCoordinates.length;
                    }, 3000);
                }
            }

            function updateTaskStatus(id, newStatus) {
                const token = document.querySelector('meta[name="csrf-token"]').content;
                fetch(`/driver/task/${id}/update-status`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': token },
                    body: JSON.stringify({ status: newStatus })
                }).then(res => res.json()).then(data => {
                    if (data.status === 'Success') {
                        showToast(`Status diperbarui: ${newStatus}`, 'success');
                        if (newStatus.includes('Selesai') || newStatus.includes('Diterima')) {
                            setTimeout(() => window.location.reload(), 2500);
                        }
                    } else showToast(data.message, 'error');
                });
            }
        @endif

        function acceptIncomingTask(id, btn) {
            const originalText = btn.innerHTML;
            btn.innerHTML = `<svg class="animate-spin w-3.5 h-3.5 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Memproses...`;
            btn.disabled = true;

            const token = document.querySelector('meta[name="csrf-token"]').content;
            fetch(`/driver/task/${id}/accept`, {
                method: 'POST',
                headers: { 
                    'Content-Type': 'application/json', 
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'Success') {
                    showToast(data.message, 'success');
                    setTimeout(() => window.location.reload(), 1500);
                } else {
                    showToast(data.message || 'Gagal menerima tugas.', 'error');
                    btn.innerHTML = originalText;
                    btn.disabled = false;
                }
            })
            .catch(err => {
                showToast('Koneksi terputus.', 'error');
                btn.innerHTML = originalText;
                btn.disabled = false;
            });
        }

        // Toast logic
        function showToast(message, type = 'info') {
            const container = document.getElementById('toastContainer');
            const toast = document.createElement('div');
            let iconClass = 'text-blue-500', bgBorder = 'bg-white border-slate-200 text-slate-800';
            let iconSvg = '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>';

            if (type === 'success') {
                iconClass = 'text-emerald-500'; bgBorder = 'bg-emerald-50 border-emerald-200/50 text-emerald-800';
                iconSvg = '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>';
            } else if (type === 'error') {
                iconClass = 'text-red-500'; bgBorder = 'bg-red-50 border-red-200/50 text-red-800';
                iconSvg = '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>';
            }

            // Quick check for dark theme to adjust toast
            if (document.documentElement.classList.contains('dark-theme')) {
                bgBorder = 'bg-slate-800 border-slate-700 text-white';
                if(type === 'success') bgBorder = 'bg-emerald-900 border-emerald-800 text-emerald-100';
                if(type === 'error') bgBorder = 'bg-red-900 border-red-800 text-red-100';
            }

            toast.className = `p-4 rounded-3xl shadow-xl border flex items-center gap-4 backdrop-blur-md transition-all duration-350 transform translate-x-12 opacity-0 pointer-events-auto ${bgBorder}`;
            toast.innerHTML = `<div class="shrink-0 ${iconClass}">${iconSvg}</div><span class="text-xs font-black">${message}</span>`;
            
            container.appendChild(toast);
            setTimeout(() => toast.classList.remove('translate-x-12', 'opacity-0'), 10);
            setTimeout(() => {
                toast.classList.add('translate-x-12', 'opacity-0');
                setTimeout(() => toast.remove(), 400);
            }, 3500);
        }
    </script>
</x-app-layout>
