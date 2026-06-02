<x-app-layout>

    <!-- Leaflet CSS & JS for Live Map -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

    <!-- Leaflet Routing Machine CSS & JS for Live Routing -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.css" />
    <script src="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.js"></script>

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            -webkit-font-smoothing: antialiased;
        }

        @keyframes bounce-slow {
            0%, 100% {
                transform: translateY(-4px);
                animation-timing-function: cubic-bezier(0.8, 0, 1, 1);
            }
            50% {
                transform: translateY(0);
                animation-timing-function: cubic-bezier(0, 0, 0.2, 1);
            }
        }
        .animate-bounce-slow {
            animation: bounce-slow 3s infinite;
        }

        @keyframes float-slow {
            0%, 100% {
                transform: translateY(0px) rotate(0deg);
            }
            50% {
                transform: translateY(-8px) rotate(1deg);
            }
        }
        .animate-float-slow {
            animation: float-slow 4.5s ease-in-out infinite;
        }

        @keyframes pulse-glow {
            0%, 100% {
                opacity: 0.2;
                transform: scale(1);
            }
            50% {
                opacity: 0.35;
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
            background: rgba(148, 163, 184, 0.25);
            border-radius: 9999px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: rgba(148, 163, 184, 0.45);
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

        /* Hide Leaflet Routing Machine turn-by-turn directions box */
        .leaflet-routing-container {
            display: none !important;
        }
    </style>

    <!-- Top Navigation Bar -->
    <header class="fixed top-0 left-0 w-full h-16 bg-white/70 backdrop-blur-xl flex items-center justify-between px-4 sm:px-6 md:px-8 lg:px-12 z-50 border-b border-slate-200/40 shadow-sm">
        <div class="flex items-center gap-3">
            <div class="flex items-center gap-3 hover:opacity-90 transition cursor-pointer group">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-[#FFB703] to-[#FB8500] flex items-center justify-center shadow-lg shadow-orange-500/20 shrink-0 transform group-hover:scale-105 transition duration-350">
                    <svg class="w-5 h-5 text-white" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
                </div>
                <span class="text-lg font-extrabold tracking-tight text-slate-900 hidden md:inline">Track<span class="text-transparent bg-clip-text bg-gradient-to-r from-[#FFB703] to-[#FB8500]">IT</span></span>
            </div>
        </div>

        <div class="flex items-center gap-2">
            @auth
                <!-- Notification Button & Dropdown -->
                <div class="relative shrink-0 flex items-center justify-center" style="width: 40px; height: 40px; min-width: 40px; min-height: 40px; max-width: 40px; max-height: 40px;">
                    <button id="notifBtn" onclick="toggleNotifications()" class="relative rounded-full bg-white border border-slate-200/60 shadow-sm flex items-center justify-center text-slate-500 hover:text-slate-800 hover:shadow-md transition duration-300 cursor-pointer shrink-0" style="width: 40px; height: 40px; min-width: 40px; min-height: 40px; max-width: 40px; max-height: 40px;">
                        <svg class="w-5 h-5 text-slate-500" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                        <span id="notifBadge" class="hidden absolute -top-0.5 -right-0.5 bg-red-500 text-[9px] font-black text-white rounded-full w-4.5 h-4.5 flex items-center justify-center border-2 border-white">0</span>
                    </button>
                    <!-- Notification dropdown menu -->
                    <div id="notificationDropdown" class="hidden fixed md:absolute top-20 md:top-14 left-4 right-4 md:left-auto md:right-0 w-[calc(100vw-32px)] md:w-90 bg-white/95 backdrop-blur-xl rounded-[2rem] md:rounded-2xl shadow-2xl border border-slate-200/50 p-5 z-50 transform origin-top md:origin-top-right transition">
                        <!-- Header Tabs -->
                        <div class="flex items-center gap-1 bg-slate-100/80 p-1 rounded-xl mb-4 border border-slate-200/40">
                            <button id="tabNotifBtn" onclick="switchNotifTab('notif')" class="flex-1 py-2 text-center text-[10px] font-black uppercase tracking-wider rounded-lg transition-all duration-300 cursor-pointer bg-white text-slate-800 shadow-sm border border-slate-200/40">
                                Pemberitahuan
                            </button>
                            <button id="tabShipmentBtn" onclick="switchNotifTab('shipments')" class="flex-1 py-2 text-center text-[10px] font-black uppercase tracking-wider rounded-lg transition-all duration-300 cursor-pointer text-slate-450 hover:text-slate-800">
                                Pengiriman Saya
                            </button>
                        </div>
                        
                        <!-- Panel 1: Notifications -->
                        <div id="panelNotif" class="space-y-3">
                            <div class="flex items-center justify-between border-b border-slate-100 pb-2">
                                <span class="text-[9px] bg-indigo-50 text-indigo-650 px-2 py-0.5 rounded-full font-bold">Real-time</span>
                                <span class="text-[9px] text-slate-400 font-bold">Terbaru</span>
                            </div>
                            <div id="notifList" class="max-h-60 overflow-y-auto text-xs text-slate-600 flex flex-col gap-2"></div>
                        </div>

                        <!-- Panel 2: My Shipments -->
                        <div id="panelShipments" class="hidden space-y-3">
                            <div class="flex items-center justify-between border-b border-slate-100 pb-2">
                                <span class="text-[9px] bg-orange-50 text-[#FB8500] px-2 py-0.5 rounded-full font-bold">Riwayat Pengiriman</span>
                                <span class="text-[9px] text-slate-400 font-bold">{{ isset($myOrders) ? count($myOrders) : 0 }} Pesanan</span>
                            </div>
                            
                            <div class="max-h-60 overflow-y-auto pr-1 flex flex-col gap-2.5">
                                @if(isset($myOrders) && count($myOrders) > 0)
                                    @foreach($myOrders as $ord)
                                        <div onclick="selectShipmentResi('{{ $ord->order_id }}')" class="w-full p-3.5 bg-slate-50 hover:bg-slate-100 border border-slate-200/60 rounded-xl text-left transition flex items-center justify-between gap-3 cursor-pointer group">
                                            <div class="flex items-center gap-2.5 min-w-0">
                                                <div class="w-7.5 h-7.5 rounded-lg bg-orange-50 text-[#FB8500] flex items-center justify-center shrink-0 border border-orange-100">
                                                    <svg class="w-3.5 h-3.5 text-orange-500" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
                                                </div>
                                                <div class="min-w-0">
                                                    <span class="block text-[10px] font-black text-slate-800 leading-none group-hover:text-[#FB8500] transition">#{{ $ord->order_id }}</span>
                                                    <span class="block text-[9px] text-slate-455 mt-1 leading-none truncate max-w-[120px]">{{ $ord->package_description }}</span>
                                                </div>
                                            </div>
                                            <div class="text-right shrink-0">
                                                <span class="block text-[9px] font-extrabold text-slate-650 leading-none">Rp {{ number_format($ord->price, 0, ',', '.') }}</span>
                                                @if($ord->payment_status === 'PAID')
                                                    @php
                                                        $isCompleted = false;
                                                        $statusText = 'Lunas';
                                                        $badgeClass = 'text-emerald-600 bg-emerald-50 border-emerald-500/10';
                                                        
                                                        if ($ord->tracking) {
                                                            $statusText = $ord->tracking->status;
                                                            $statusLower = strtolower($statusText);
                                                            if (strpos($statusLower, 'diterima') !== false || strpos($statusLower, 'selesai') !== false || strpos($statusLower, 'delivered') !== false) {
                                                                $isCompleted = true;
                                                                $badgeClass = 'text-emerald-600 bg-emerald-50 border-emerald-500/10';
                                                            } elseif (strpos($statusLower, 'perjalanan') !== false || strpos($statusLower, 'transit') !== false || strpos($statusLower, 'kirim') !== false) {
                                                                $badgeClass = 'text-indigo-600 bg-indigo-50 border-indigo-500/10';
                                                            } else {
                                                                $badgeClass = 'text-amber-600 bg-amber-50 border-amber-500/10';
                                                            }
                                                        }
                                                    @endphp
                                                    <div class="flex items-center gap-1 mt-1 justify-end" onclick="event.stopPropagation()">
                                                        <span class="inline-block text-[8px] font-black {{ $badgeClass }} border px-2 py-0.5 rounded-full leading-none uppercase">{{ $statusText }}</span>
                                                        @if($isCompleted)
                                                            <form action="{{ route('customer.order.destroy', $ord->order_id) }}" method="POST" class="inline m-0 p-0" onsubmit="return confirm('Apakah Anda yakin ingin menghapus riwayat pengiriman ini?')">
                                                                @csrf
                                                                <button type="submit" class="inline-block text-[8px] font-black text-red-650 bg-red-50 border border-red-200 px-2 py-0.5 rounded-full leading-none uppercase hover:bg-red-500 hover:text-white hover:border-transparent transition cursor-pointer">Hapus</button>
                                                            </form>
                                                        @endif
                                                    </div>
                                                @elseif($ord->payment_status === 'CANCELLED')
                                                    <div class="flex items-center gap-1.5 mt-1.5 justify-end" onclick="event.stopPropagation()">
                                                        <span class="inline-block text-[8px] font-black text-slate-400 bg-slate-100 border border-slate-200 px-2 py-0.5 rounded-full leading-none uppercase">Dibatalkan</span>
                                                        <form action="{{ route('customer.order.destroy', $ord->order_id) }}" method="POST" class="inline m-0 p-0" onsubmit="return confirm('Apakah Anda yakin ingin menghapus riwayat pengiriman ini?')">
                                                            @csrf
                                                            <button type="submit" class="inline-block text-[8px] font-black text-red-650 bg-red-50 border border-red-200 px-2 py-0.5 rounded-full leading-none uppercase hover:bg-red-500 hover:text-white hover:border-transparent transition cursor-pointer">Hapus</button>
                                                        </form>
                                                    </div>
                                                @else
                                                    <div class="flex items-center gap-1 mt-1 justify-end" onclick="event.stopPropagation()">
                                                        <a href="{{ route('customer.payment', $ord->order_id) }}" class="inline-block text-[8px] font-black text-rose-600 bg-rose-50 border border-rose-500/10 px-2 py-0.5 rounded-full leading-none uppercase hover:bg-rose-500 hover:text-white transition">Bayar</a>
                                                        <form action="{{ route('customer.order.cancel', $ord->order_id) }}" method="POST" class="inline m-0 p-0" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan pesanan ini?')">
                                                            @csrf
                                                            <button type="submit" class="inline-block text-[8px] font-black text-slate-500 bg-slate-50 border border-slate-200 px-2 py-0.5 rounded-full leading-none uppercase hover:bg-rose-600 hover:text-white hover:border-transparent transition cursor-pointer">Batal</button>
                                                        </form>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="p-6 text-center text-slate-400 font-semibold text-xs border border-dashed border-slate-200 rounded-xl bg-slate-50">
                                        Belum ada pengiriman.
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- User Profile Circle Button -->
                <div onclick="toggleMobileSettings(); switchDrawerToSummary();" class="rounded-full bg-white border border-slate-200/60 shadow-sm flex items-center justify-center font-extrabold text-slate-700 hover:shadow-md hover:border-slate-300 transition duration-300 uppercase shrink-0 text-sm cursor-pointer" style="width: 40px; height: 40px; min-width: 40px; min-height: 40px; max-width: 40px; max-height: 40px;" title="{{ Auth::user()->name }}">
                    @if(Auth::user()->name && trim(Auth::user()->name) !== '')
                        {{ substr(trim(Auth::user()->name), 0, 1) }}
                    @else
                        <svg class="w-5 h-5 text-slate-500" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0zM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632z" /></svg>
                    @endif
                </div>
            @else
                <a href="/" class="group w-10 h-10 rounded-full bg-white/80 shadow-sm flex items-center justify-center text-slate-500 hover:text-slate-855 hover:shadow-md hover:-translate-y-0.5 transition-all duration-300 border border-slate-200/40 shrink-0" title="Kembali">
                    <svg class="w-5 h-5" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
                </a>
            @endauth
        </div>
    </header>

    <!-- Main Container -->
    <div class="min-h-screen lg:h-screen w-full font-sans relative flex flex-col lg:flex-row bg-slate-50 overflow-y-auto lg:overflow-hidden pt-16">
        
        <!-- Ambient Background Glowing Orbs -->
        <div class="absolute top-[-10%] left-[-10%] w-[320px] sm:w-[500px] h-[320px] sm:h-[500px] rounded-full bg-amber-300/20 blur-[100px] pointer-events-none animate-pulse-glow"></div>
        <div class="absolute bottom-[-10%] right-[-10%] w-[400px] sm:w-[600px] h-[400px] sm:h-[600px] rounded-full bg-orange-400/10 blur-[125px] pointer-events-none animate-pulse-glow" style="animation-delay: -3s;"></div>

        <!-- LEFT COLUMN: Greeting & Search -->
        <div class="w-full lg:w-[42%] xl:w-[38%] px-6 pt-8 pb-24 sm:px-10 lg:py-16 flex flex-col justify-between shrink-0 border-b lg:border-b-0 lg:border-r border-slate-200/50 bg-white/30 backdrop-blur-3xl relative z-20 overflow-y-auto lg:h-full">
            
            <div class="my-auto flex flex-col gap-8">
                <!-- Status Badge -->
                <div class="inline-flex self-start items-center gap-2 px-3 py-1.5 rounded-full bg-white border border-slate-200/60 shadow-sm hover:border-slate-300 transition duration-300">
                    <span class="relative flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                    </span>
                    <span class="text-[10px] font-extrabold tracking-widest text-slate-650 uppercase">Sistem Online</span>
                </div>

                <!-- Greeting Header Title -->
                @php
                    $fullName = Auth::check() ? Auth::user()->name : 'Guest';
                    $firstName = explode(' ', $fullName)[0];
                @endphp
                <div>
                    <h1 class="text-4xl sm:text-5xl lg:text-[3.25rem] font-black text-slate-900 tracking-tight leading-[1.08] mb-4">
                        Lacak Paket<br>
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-[#FFB703] to-[#FB8500]">
                            Lebih Mudah.
                        </span>
                    </h1>
                    <p class="text-slate-550 text-sm sm:text-base leading-relaxed font-medium max-w-md">
                        Halo <strong class="text-slate-800 font-bold">{{ $firstName }}</strong>, pantau detail pergerakan pengiriman Anda secara <span class="text-slate-805 font-semibold italic">real-time</span>. Cukup masukkan nomor resi Anda di bawah.
                    </p>
                </div>

                <!-- Search input pill widget -->
                <div class="relative w-full group">
                    <div class="absolute -inset-1 bg-gradient-to-r from-amber-400 via-orange-500 to-yellow-500 rounded-3xl blur-lg opacity-25 group-hover:opacity-40 transition duration-500"></div>
                    <div class="relative bg-white rounded-2xl shadow-md p-2 flex items-center border border-slate-200/60 focus-within:border-orange-300 focus-within:ring-4 focus-within:ring-orange-100 transition duration-300 w-full">
                        <div class="flex items-center pl-3 text-slate-400 shrink-0">
                            <svg class="w-5 h-5 text-slate-400" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </div>
                        <input type="text" id="search_order_id" placeholder="Cari Resi: ORD-123" 
                               class="w-full py-2.5 px-3 bg-transparent outline-none text-slate-850 font-extrabold text-sm sm:text-base placeholder-slate-300 tracking-wide">
                        <button onclick="searchTracking()" class="bg-slate-900 text-white rounded-xl px-7 py-3 font-bold hover:bg-orange-550 active:scale-95 transition-all duration-300 shadow-md shrink-0 flex items-center justify-center min-w-[100px] text-xs sm:text-sm gap-2">
                            Lacak
                            <svg class="w-3.5 h-3.5" width="14" height="14" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        </button>
                    </div>
                </div>

                <!-- Shortcuts / Sample Data Tags -->
                <div class="flex flex-col gap-2.5 -mt-3 pl-1">
                    <div class="flex flex-wrap gap-2 items-center text-xs text-slate-455 font-medium">
                        <span>Contoh Resi:</span>
                        <button onclick="fillSampleResi('ORD-001')" class="px-2.5 py-1 bg-white hover:bg-slate-100 hover:text-slate-700 rounded-lg border border-slate-200/60 shadow-sm transition cursor-pointer">ORD-001</button>
                        <button onclick="fillSampleResi('ORD-002')" class="px-2.5 py-1 bg-white hover:bg-slate-100 hover:text-slate-700 rounded-lg border border-slate-200/60 shadow-sm transition cursor-pointer">ORD-002</button>
                    </div>

                    @auth
                        @if(isset($recentSearches) && count($recentSearches) > 0)
                            <div class="flex flex-wrap gap-2 items-center text-xs text-slate-400 font-medium">
                                <span class="text-slate-455">Pencarian Terakhir:</span>
                                @foreach($recentSearches as $rs)
                                    <div class="inline-flex items-center gap-1.5 bg-white border border-slate-200/60 shadow-sm rounded-lg px-2.5 py-1 transition hover:bg-slate-50">
                                        <button onclick="fillSampleResi('{{ $rs->order_id }}')" class="hover:text-slate-800 font-bold transition cursor-pointer">
                                            {{ $rs->order_id }}
                                        </button>
                                        <form action="{{ route('customer.recent.delete', $rs->id) }}" method="POST" class="inline flex items-center m-0 p-0">
                                            @csrf
                                            <button type="submit" class="text-slate-300 hover:text-rose-500 transition font-black leading-none text-xs px-0.5 cursor-pointer" title="Hapus">&times;</button>
                                        </form>
                                    </div>
                                @endforeach
                                <form action="{{ route('customer.recent.clear') }}" method="POST" class="inline flex items-center m-0 p-0 ml-1">
                                    @csrf
                                    <button type="submit" class="text-slate-400 hover:text-rose-600 transition font-extrabold text-[10px] uppercase cursor-pointer" title="Hapus Semua Riwayat">Hapus Semua</button>
                                </form>
                            </div>
                        @endif
                    @endauth
                </div>

                @auth
                    <!-- Call to Action Button: Create Shipping Order (Desktop / Laptop only) -->
                    <div class="mt-6 hidden md:block">
                        <a href="{{ route('customer.order.create') }}" class="w-full flex items-center justify-center gap-2.5 px-6 py-3.5 bg-slate-900 text-white rounded-2xl font-black text-sm hover:bg-[#FB8500] shadow-md hover:shadow-orange-500/20 active:scale-[0.98] transition duration-300">
                            <svg class="w-5 h-5 text-white" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                            Buat Pengiriman Baru
                        </a>
                    </div>
                @endauth
            </div>

            <!-- Priority Support Card -->
            <div class="mt-8 flex items-center gap-4 p-4 bg-white/50 backdrop-blur-md rounded-2xl border border-white/60 shadow-sm hover:shadow-md transition duration-300 max-w-sm">
                <div class="flex -space-x-3">
                    <img class="w-8 h-8 rounded-full border-2 border-white shadow bg-white" src="https://i.pravatar.cc/100?img=33" alt="CS Support">
                    <img class="w-8 h-8 rounded-full border-2 border-white shadow bg-white" src="https://i.pravatar.cc/100?img=47" alt="CS Support">
                </div>
                <div>
                    <p class="text-xs font-bold text-slate-800">Dukungan Prioritas 24/7</p>
                    <p class="text-[10px] text-slate-500 mt-0.5 leading-relaxed">Hubungi admin untuk keluhan operasional logistik.</p>
                </div>
            </div>
        </div>

        <!-- RIGHT COLUMN: Active Order panel ("Pesanan Aktif") containing embedded map at the top -->
        <div class="w-full lg:w-[58%] xl:w-[62%] px-5 pt-5 pb-24 sm:px-8 sm:pt-8 sm:pb-24 lg:p-12 xl:p-16 flex flex-col justify-center items-center lg:items-start xl:pl-20 overflow-y-auto lg:h-full relative z-10">
            
            <div class="w-full max-w-2xl mx-auto lg:mx-0">
                
                <!-- Main Header Badge Label -->
                <div class="flex justify-between items-center mb-4 pl-2">
                    <h3 class="text-base font-black text-slate-800 tracking-tight flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-[#FB8500] animate-ping"></span>
                        Pesanan Aktif
                    </h3>
                    <span id="res_badge_time" class="text-[10px] font-extrabold text-slate-500 bg-white/70 border border-slate-200/50 rounded-full px-3 py-1 shadow-sm uppercase tracking-wider hidden">Diambil hari ini, 10:30</span>
                </div>

                <!-- Result Active Order Glass Card (Map at the Top) -->
                <div id="resultCard" class="w-full transition-all duration-700 opacity-100 transform translate-y-0">
                    
                    <div class="bg-white rounded-[2.5rem] shadow-[0_20px_50px_rgba(0,0,0,0.06)] overflow-hidden border border-slate-200/40">
                        
                        <!-- Top panel content: Embedded Map Banner (Full Width) -->
                        <div class="h-64 w-full relative overflow-hidden z-10 border-b border-slate-100">
                            <div id="map" class="absolute inset-0 z-0"></div>
                            
                            <!-- Vignette edge shadow overlay -->
                            <div class="absolute inset-0 bg-gradient-to-t from-slate-900/10 via-transparent to-transparent pointer-events-none z-10"></div>
                            
                            <!-- Floating status badge overlay -->
                            <div id="floatingBadge" class="absolute top-6 left-6 bg-slate-900/90 backdrop-blur-md px-4 py-2 rounded-full shadow-lg flex items-center gap-1.5 border border-slate-700/50 z-20 hidden">
                                <span class="relative flex h-1.5 w-1.5">
                                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-orange-450 opacity-75"></span>
                                  <span class="relative inline-flex rounded-full h-1.5 w-1.5 bg-orange-500"></span>
                                </span>
                                <span class="text-[9px] font-black text-slate-200 uppercase tracking-wider" id="res_badge">Pickup</span>
                            </div>
                        </div>

                        <!-- Result Details Content Area (Below Map) -->
                        <div id="detailsCard" class="p-8 sm:p-10 -mt-8 relative bg-white rounded-t-[3rem] z-20 shadow-[0_-15px_30px_rgba(0,0,0,0.02)] border-t border-slate-100/50 hidden">
                            
                            <!-- Status & Order Title Row -->
                            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-end mb-6 border-b border-slate-100 pb-5 gap-3">
                                <div>
                                    <div class="flex items-center gap-1.5 mb-1.5">
                                        <span class="text-[9px] font-black text-indigo-650 uppercase tracking-widest bg-indigo-50 px-2 py-0.5 rounded">Status Terakhir</span>
                                    </div>
                                    <h4 class="text-2xl font-black text-slate-800 tracking-tight leading-snug" id="res_status">
                                        Kurir sedang menjemput paket
                                    </h4>
                                </div>
                                <div class="bg-slate-50 border border-slate-200/50 px-3 py-2 rounded-xl text-left sm:text-right shrink-0">
                                    <span class="text-[8px] font-bold text-slate-455 uppercase tracking-widest block leading-none">ID Lacak</span>
                                    <span class="text-xs font-black text-slate-700 mt-1 block" id="res_order_id">No. Order #INV-240523-0018</span>
                                </div>
                            </div>

                            <!-- ETA Banner Widget -->
                            <div class="mb-6 bg-slate-50 border border-slate-200/50 p-4.5 rounded-2xl flex items-center gap-4.5">
                                <div class="w-11 h-11 rounded-xl bg-orange-505 text-[#FB8500] flex items-center justify-center shrink-0">
                                    <svg class="w-5.5 h-5.5 text-orange-500" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                                </div>
                                <div>
                                    <p class="text-[9px] font-bold text-slate-450 uppercase tracking-wider leading-none">Estimasi sampai</p>
                                    <h5 class="text-sm sm:text-base font-black text-slate-805 mt-1.5" id="res_eta">Hari ini, 15:30 WIB</h5>
                                </div>
                            </div>

                            <!-- Stepper Progress nodes -->
                            <div class="mb-8 relative px-2 py-4 border-t border-slate-100">
                                <!-- Horizontal Timeline bar -->
                                <div class="absolute top-[36px] left-8 right-8 h-1 bg-slate-100 -translate-y-1/2 rounded-full">
                                    <div id="progress_line" class="h-full bg-[#FB8500] rounded-full transition-all duration-700 shadow-md shadow-orange-500/20" style="width: 33%;"></div>
                                </div>
                                
                                <div class="flex justify-between items-start relative z-10">
                                    <!-- Step 1: Order -->
                                    <div class="flex flex-col items-center text-center w-1/4" id="step_node_0">
                                        <div class="step-circle w-10 h-10 min-w-[2.5rem] min-h-[2.5rem] max-w-[2.5rem] max-h-[2.5rem] shrink-0 rounded-full bg-slate-100 border-2 border-slate-200 flex items-center justify-center transition-all duration-500">
                                            <svg class="w-5.5 h-5.5 text-current" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>
                                        </div>
                                        <span class="step-label text-[9px] sm:text-[11px] font-bold text-slate-450 mt-2.5 transition-all duration-500">1. Order</span>
                                    </div>

                                    <!-- Step 2: Pickup -->
                                    <div class="flex flex-col items-center text-center w-1/4" id="step_node_1">
                                        <div class="step-circle w-10 h-10 min-w-[2.5rem] min-h-[2.5rem] max-w-[2.5rem] max-h-[2.5rem] shrink-0 rounded-full bg-slate-100 border-2 border-slate-200 flex items-center justify-center transition-all duration-500">
                                            <svg class="w-5.5 h-5.5 text-current" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" /></svg>
                                        </div>
                                        <span class="step-label text-[9px] sm:text-[11px] font-bold text-slate-455 mt-2.5 transition-all duration-500">2. Pickup</span>
                                    </div>

                                    <!-- Step 3: In Transit -->
                                    <div class="flex flex-col items-center text-center w-1/4" id="step_node_2">
                                        <div class="step-circle w-10 h-10 min-w-[2.5rem] min-h-[2.5rem] max-w-[2.5rem] max-h-[2.5rem] shrink-0 rounded-full bg-slate-100 border-2 border-slate-200 flex items-center justify-center transition-all duration-500">
                                            <svg class="w-5.5 h-5.5 text-current" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 0 1-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124l-.318-5.085a1.875 1.875 0 0 0-1.85-1.751h-2.25m-9 12h9m0 0V8.25m0 10.5h-6M21 14.25a2.24 2.24 0 0 0-2.25-2.25H18M3 14.25h15v-9a1.125 1.125 0 0 0-1.125-1.125H3.75A1.125 1.125 0 0 0 2.625 5.25v9a1.125 1.125 0 0 0 1.125 1.125Z" /></svg>
                                        </div>
                                        <span class="step-label text-[9px] sm:text-[11px] font-bold text-slate-460 mt-2.5 transition-all duration-500">3. In Transit</span>
                                    </div>

                                    <!-- Step 4: Arrived -->
                                    <div class="flex flex-col items-center text-center w-1/4" id="step_node_3">
                                        <div class="step-circle w-10 h-10 min-w-[2.5rem] min-h-[2.5rem] max-w-[2.5rem] max-h-[2.5rem] shrink-0 rounded-full bg-slate-100 border-2 border-slate-200 flex items-center justify-center transition-all duration-500">
                                            <svg class="w-5.5 h-5.5 text-current" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>
                                        </div>
                                        <span class="step-label text-[9px] sm:text-[11px] font-bold text-slate-465 mt-2.5 transition-all duration-500">4. Arrived</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Active Driver and Log update info -->
                            <div class="p-5 bg-slate-50 border border-slate-200/50 rounded-2xl flex flex-col sm:flex-row justify-between items-center gap-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 min-w-[2.5rem] min-h-[2.5rem] max-w-[2.5rem] max-h-[2.5rem] rounded-full bg-indigo-50/60 text-indigo-650 flex items-center justify-center shrink-0 border border-slate-200/40">
                                        <svg class="w-5.5 h-5.5 text-indigo-650" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" /></svg>
                                    </div>
                                    <div class="text-left">
                                        <span class="text-[8px] font-bold text-slate-450 uppercase tracking-widest leading-none block">Kurir Ditugaskan</span>
                                        <h5 class="text-xs font-black text-slate-800 mt-1" id="res_driver_id">Driver Arief Setiawan</h5>
                                    </div>
                                </div>
                                <div class="text-left sm:text-right shrink-0">
                                    <span class="text-[8px] font-bold text-slate-455 uppercase tracking-widest block leading-none">Terakhir Diupdate</span>
                                    <span class="text-[11px] font-extrabold text-slate-600 mt-1 block" id="res_time">Baru Saja</span>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <!-- Not Found Error Display -->
                <div id="notFoundCard" class="hidden w-full mt-4 transition duration-500">
                    <div class="bg-rose-950/40 backdrop-blur-2xl border border-rose-900/40 p-6 rounded-2xl flex items-center gap-4 shadow-xl relative overflow-hidden mt-6">
                        <div class="absolute top-0 left-0 w-1.5 h-full bg-rose-500"></div>
                        <div class="w-11 h-11 rounded-full bg-rose-900/30 text-rose-450 flex items-center justify-center shrink-0 border border-rose-800/40 shadow-inner">
                            <svg class="w-6 h-6" width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <div>
                            <p class="text-sm font-black text-slate-100">Resi Tidak Ditemukan</p>
                            <p class="text-[11px] text-rose-200/70 mt-0.5 leading-relaxed">Pastikan Anda mengetikkan kode resi dengan benar. Untuk pesanan baru, mohon tunggu beberapa menit hingga logistik mendaftarkan resi Anda.</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Toast Notification Container (Top-Right on Desktop, Top-Center on Mobile) -->
    <div id="toastContainer" class="fixed top-4 md:top-6 left-1/2 md:left-auto md:right-6 -translate-x-1/2 md:translate-x-0 z-[9999] flex flex-col gap-3 w-full max-w-[calc(100vw-2rem)] sm:max-w-sm px-4 pointer-events-none transition-all duration-300"></div>

    <!-- Script handling dynamic layout, API & Map operations -->
    <script>
        // Intercept and suppress the OSRM demo server console warning from Leaflet Routing Machine
        (function() {
            const originalWarn = console.warn;
            console.warn = function(...args) {
                if (args[0] && typeof args[0] === 'string' && args[0].includes("OSRM's demo server")) {
                    return;
                }
                originalWarn.apply(console, args);
            };
        })();

        let map = null;
        let driverMarker = null;
        let destination = [-6.200000, 106.816666];
        let routingControl = null;
        let trackingInterval = null;
        let activeTrackingOrderId = null;

        function detectAndDrawRoute() {
            if (!navigator.geolocation) {
                showToast('Browser Anda tidak mendukung deteksi lokasi (Geolocation).', 'error');
                return;
            }

            navigator.geolocation.getCurrentPosition(
                (position) => {
                    const customerLat = position.coords.latitude;
                    const customerLng = position.coords.longitude;
                    
                    // Tarik rute dinamis dari lokasi customer (Titik A) ke lokasi penerima (Titik B)
                    calculateRouting(customerLat, customerLng, destination[0], destination[1]);
                },
                (error) => {
                    let errMsg = 'Gagal mendeteksi lokasi otomatis.';
                    if (error.code === error.PERMISSION_DENIED) {
                        errMsg = 'Izin akses lokasi ditolak. Silakan aktifkan izin lokasi di browser Anda.';
                    } else if (error.code === error.POSITION_UNAVAILABLE) {
                        errMsg = 'Informasi lokasi saat ini tidak tersedia.';
                    } else if (error.code === error.TIMEOUT) {
                        errMsg = 'Waktu permintaan lokasi habis.';
                    }
                    showToast(errMsg, 'error');
                },
                {
                    enableHighAccuracy: true,
                    timeout: 8000,
                    maximumAge: 0
                }
            );
        }

        function calculateRouting(startLat, startLng, endLat, endLng) {
            if (!map) return;

            // Sembunyikan marker fallback utama jika rute aktif
            if (driverMarker && map.hasLayer(driverMarker)) {
                map.removeLayer(driverMarker);
            }

            // Hapus rute yang digambar sebelumnya
            if (routingControl) {
                map.removeControl(routingControl);
            }

            // Kustomisasi marker premium
            const startIcon = L.divIcon({
                className: 'custom-div-icon',
                html: `<div class="relative w-7 h-7 bg-gradient-to-tr from-blue-400 to-indigo-600 rounded-full shadow-[0_0_20px_rgba(59,130,246,0.6)] border-2 border-white flex items-center justify-center">
                        <div class="absolute inset-0 bg-blue-400 rounded-full animate-ping opacity-75"></div>
                        <div class="w-2.5 h-2.5 bg-white rounded-full"></div>
                       </div>`,
                iconSize: [28, 28],
                iconAnchor: [14, 14]
            });

            const endIcon = L.divIcon({
                className: 'custom-div-icon',
                html: `<div class="relative w-7 h-7 bg-gradient-to-tr from-[#FFB703] to-[#FB8500] rounded-full shadow-[0_0_20px_rgba(251,133,0,0.6)] border-2 border-white flex items-center justify-center">
                        <div class="absolute inset-0 bg-orange-400 rounded-full animate-ping opacity-75"></div>
                        <div class="w-2.5 h-2.5 bg-white rounded-full"></div>
                       </div>`,
                iconSize: [28, 28],
                iconAnchor: [14, 14]
            });

            // Lakukan routing dengan OSRM (Gratis & tanpa API key)
            routingControl = L.Routing.control({
                waypoints: [
                    L.latLng(startLat, startLng),
                    L.latLng(endLat, endLng)
                ],
                routeWhileDragging: false,
                addWaypoints: false,
                draggableWaypoints: false,
                fitSelectedRoutes: true,
                show: false, // Sembunyikan petunjuk arah teks agar UI bersih
                createMarker: function(i, waypoint, n) {
                    if (i === 0) {
                        return L.marker(waypoint.latLng, { icon: startIcon, title: "Lokasi Anda (Titik A)" });
                    } else if (i === n - 1) {
                        return L.marker(waypoint.latLng, { icon: endIcon, title: "Lokasi Penerima (Titik B)" });
                    }
                    return null;
                },
                lineOptions: {
                    styles: [
                        { color: '#00ff80', opacity: 0.8, weight: 4 } // Jalur hijau neon premium
                    ]
                }
            }).addTo(map);
        }

        document.addEventListener('DOMContentLoaded', () => {
            // Fetch notifications and start poller on load
            @if(auth()->check())
                fetchNotifications();
                setInterval(fetchNotifications, 15000);
            @endif

            // Inisialisasi peta terpusat pada lokasi user atau Jakarta (tanpa marker/rute paket)
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    (position) => {
                        initializeOrUpdateMap(position.coords.latitude, position.coords.longitude, true);
                    },
                    () => {
                        initializeOrUpdateMap(-6.200000, 106.816666, true);
                    }
                );
            } else {
                initializeOrUpdateMap(-6.200000, 106.816666, true);
            }
        });

        // Custom premium toast notifications replacement for alert()
        function showToast(message, type = 'success') {
            const container = document.getElementById('toastContainer');
            if (!container) return;

            const toast = document.createElement('div');
            toast.className = `p-4 rounded-2xl shadow-xl border backdrop-blur-md transition-all duration-350 transform translate-y-2 opacity-0 pointer-events-auto flex items-center gap-3 w-full`;
            
            if (type === 'success') {
                toast.className += ' bg-slate-900/95 border-emerald-500/20 text-slate-100 shadow-slate-900/20';
            } else if (type === 'error') {
                toast.className += ' bg-rose-950/95 border-rose-800/40 text-rose-100 shadow-rose-950/20';
            } else {
                toast.className += ' bg-slate-900/95 border-slate-800/40 text-slate-100 shadow-slate-950/20';
            }

            let icon = '';
            if (type === 'success') {
                icon = `<div class="w-6 h-6 rounded-full bg-emerald-500/20 text-emerald-450 flex items-center justify-center shrink-0 border border-emerald-500/30">
                            <svg class="w-3.5 h-3.5" width="14" height="14" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                        </div>`;
            } else if (type === 'error') {
                icon = `<div class="w-6 h-6 rounded-full bg-rose-500/20 text-rose-400 flex items-center justify-center shrink-0 border border-rose-500/30">
                            <svg class="w-3.5 h-3.5" width="14" height="14" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                        </div>`;
            } else {
                icon = `<div class="w-6 h-6 rounded-full bg-blue-500/20 text-blue-400 flex items-center justify-center shrink-0 border border-blue-500/30">
                            <svg class="w-3.5 h-3.5" width="14" height="14" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 111.063.852l-.708 2.836a.75.75 0 001.063.852l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>`;
            }

            toast.innerHTML = `
                ${icon}
                <div class="flex-1 text-xs font-bold leading-normal">${message}</div>
                <button onclick="this.parentElement.remove()" class="text-slate-400 hover:text-white transition font-black text-sm shrink-0 leading-none">×</button>
            `;

            container.appendChild(toast);

            setTimeout(() => {
                toast.classList.remove('translate-y-2', 'opacity-0');
                toast.classList.add('translate-y-0', 'opacity-100');
            }, 10);

            setTimeout(() => {
                toast.classList.remove('translate-y-0', 'opacity-100');
                toast.classList.add('-translate-y-2', 'opacity-0');
                setTimeout(() => {
                    toast.remove();
                }, 300);
            }, 4500);
        }

        function loadDefaultSimulatedOrder() {
            document.getElementById('res_order_id').innerText = 'No. Order #INV-240523-0018';
            document.getElementById('res_status').innerText = 'Kurir sedang menjemput paket';
            document.getElementById('res_badge').innerText = 'Pickup';
            document.getElementById('res_badge_time').innerText = 'Diambil hari ini, 10:30';
            document.getElementById('res_driver_id').innerText = 'Driver Arief Setiawan';
            document.getElementById('res_time').innerText = 'Baru Saja';
            document.getElementById('res_eta').innerText = 'Hari ini, 15:30 WIB';
            
            updateTimeline(1); // Set stage to 1 (Pickup)

            const lat = -6.200000;
            const lng = 106.816666;
            
            // Set dummy destination (Titik B)
            destination = [lat, lng];
            
            initializeOrUpdateMap(lat, lng);
            
            // Lakukan deteksi lokasi otomatis & routing
            detectAndDrawRoute();
        }

        function initializeOrUpdateMap(lat, lng, skipMarker = false) {
            if (!map) {
                map = L.map('map', {
                    zoomControl: true, 
                    attributionControl: false
                }).setView([lat, lng], 14);
                
                // Basemap CartoDB Dark Matter gratis & aman tanpa API key
                L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', {
                    maxZoom: 20
                }).addTo(map);
            } else {
                map.setView([lat, lng], 14);
            }
            
            if (skipMarker) {
                if (driverMarker && map.hasLayer(driverMarker)) {
                    map.removeLayer(driverMarker);
                }
                setTimeout(() => {
                    map.invalidateSize();
                }, 100);
                return;
            }
            
            // Marker fallback standar (jika rute aktif dinonaktifkan / akses lokasi ditolak)
            const pulsingIcon = L.divIcon({
                className: 'custom-div-icon',
                html: `<div class="relative w-7 h-7 bg-gradient-to-tr from-[#FFB703] to-[#FB8500] rounded-full shadow-[0_0_20px_rgba(251,133,0,0.6)] border-2 border-white flex items-center justify-center">
                        <div class="absolute inset-0 bg-orange-400 rounded-full animate-ping opacity-75"></div>
                        <div class="w-2.5 h-2.5 bg-white rounded-full"></div>
                       </div>`,
                iconSize: [28, 28],
                iconAnchor: [14, 14]
            });
            
            if (!driverMarker) {
                driverMarker = L.marker([lat, lng], {icon: pulsingIcon}).addTo(map);
            } else {
                driverMarker.setLatLng([lat, lng]);
                if (!map.hasLayer(driverMarker) && !routingControl) {
                    driverMarker.addTo(map);
                }
            }
            
            // Invalidate map size to prevent rendering issues
            setTimeout(() => {                map.invalidateSize();
            }, 100);
        }

        function clearMapRoutingAndMarkers() {
            if (!map) return;
            if (routingControl) {
                map.removeControl(routingControl);
                routingControl = null;
            }
            if (driverMarker && map.hasLayer(driverMarker)) {
                map.removeLayer(driverMarker);
            }
            driverMarker = null;
        }

        // Search tags shortcut triggers
        function fillSampleResi(id) {
            const input = document.getElementById('search_order_id');
            input.value = id;
            searchTracking();
        }

        async function searchTracking() {
            const searchBtn = document.querySelector('button[onclick="searchTracking()"]');
            const originalBtnContent = searchBtn.innerHTML;
            
            // Clear any active tracking polling
            if (trackingInterval) {
                clearInterval(trackingInterval);
                trackingInterval = null;
            }
            activeTrackingOrderId = null;
            
            searchBtn.innerHTML = '<svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>';
            
            const orderId = document.getElementById('search_order_id').value.trim();
            const resultCard = document.getElementById('resultCard');
            const notFoundCard = document.getElementById('notFoundCard');

            if(!orderId) {
                searchBtn.innerHTML = originalBtnContent;
                return;
            }

            // Reset peta dan sembunyikan kartu detail sementara loading
            clearMapRoutingAndMarkers();
            document.getElementById('res_badge_time').classList.add('hidden');
            document.getElementById('floatingBadge').classList.add('hidden');
            document.getElementById('detailsCard').classList.add('hidden');
            
            resultCard.classList.remove('opacity-100', 'translate-y-0');
            resultCard.classList.add('opacity-0', 'translate-y-4');
            notFoundCard.style.display = 'none';

            setTimeout(async () => {
                try {
                    const response = await fetch(`/api/v1/track/${orderId}`);
                    const res = await response.json();

                    if (response.ok) {
                        document.getElementById('res_order_id').innerText = `No. Order #${res.data.order_id}`;
                        document.getElementById('res_status').innerText = res.data.status_pengiriman;
                        
                        const stagesMap = ['Dikemas', 'Diperjalanan', 'Kurir', 'Arrived'];
                        document.getElementById('res_badge').innerText = stagesMap[res.data.stage] || res.data.status_pengiriman;
                        
                        document.getElementById('res_driver_id').innerText = res.data.driver_name || res.data.driver_id || 'Menunggu Kurir';
                        
                        const date = new Date(res.data.terakhir_diupdate);
                        document.getElementById('res_time').innerText = date.toLocaleString('id-ID', { dateStyle: 'long', timeStyle: 'short' });
                        document.getElementById('res_badge_time').innerText = `Update: ${date.toLocaleTimeString('id-ID', {hour: '2-digit', minute: '2-digit'})}`;

                        // Update ETA Text
                        document.getElementById('res_eta').innerText = `${res.data.eta}`;

                        // Update Timeline Stages
                        updateTimeline(res.data.stage);

                        // Update Map & Routing secara dinamis
                        if (res.data.lat && res.data.lng) {
                            // Update lokasi tujuan (Titik B) dengan data terbaru dari REST API
                            destination = [res.data.lat, res.data.lng];
                            initializeOrUpdateMap(res.data.lat, res.data.lng, false);
                            // Tarik rute dinamis baru
                            detectAndDrawRoute();
                        }

                        // Tampilkan kartu detail hasil pencarian
                        document.getElementById('res_badge_time').classList.remove('hidden');
                        document.getElementById('floatingBadge').classList.remove('hidden');
                        document.getElementById('detailsCard').classList.remove('hidden');

                        // Reveal results card
                        setTimeout(() => {
                            resultCard.classList.remove('opacity-0', 'translate-y-4');
                            resultCard.classList.add('opacity-100', 'translate-y-0');
                        }, 50);

                        // Start real-time background tracking poller
                        activeTrackingOrderId = res.data.order_id;
                        if (res.data.stage < 3) {
                            if (!trackingInterval) {
                                trackingInterval = setInterval(pollActiveTracking, 5000); // Poll every 5 seconds
                            }
                        }
                    } else {
                        if (trackingInterval) {
                            clearInterval(trackingInterval);
                            trackingInterval = null;
                        }
                        activeTrackingOrderId = null;

                        document.getElementById('res_badge_time').classList.add('hidden');
                        document.getElementById('floatingBadge').classList.add('hidden');
                        document.getElementById('detailsCard').classList.add('hidden');
                        
                        notFoundCard.style.display = 'block';
                        notFoundCard.style.opacity = '0';
                        setTimeout(() => {
                            notFoundCard.style.transition = 'opacity 0.4s ease';
                            notFoundCard.style.opacity = '1';
                        }, 50);
                    }
                } catch (error) {
                    if (trackingInterval) {
                        clearInterval(trackingInterval);
                        trackingInterval = null;
                    }
                    activeTrackingOrderId = null;

                    console.error(error);
                    document.getElementById('res_badge_time').classList.add('hidden');
                    document.getElementById('floatingBadge').classList.add('hidden');
                    document.getElementById('detailsCard').classList.add('hidden');
                    notFoundCard.style.display = 'block';
                }
                
                searchBtn.innerHTML = originalBtnContent;
            }, 300);
        }

        async function pollActiveTracking() {
            if (!activeTrackingOrderId) return;
            try {
                const response = await fetch(`/api/v1/track/${activeTrackingOrderId}`);
                const res = await response.json();

                if (response.ok) {
                    document.getElementById('res_status').innerText = res.data.status_pengiriman;
                    
                    const stagesMap = ['Dikemas', 'Diperjalanan', 'Kurir', 'Arrived'];
                    document.getElementById('res_badge').innerText = stagesMap[res.data.stage] || res.data.status_pengiriman;
                    
                    document.getElementById('res_driver_id').innerText = res.data.driver_name || res.data.driver_id || 'Menunggu Kurir';
                    
                    const date = new Date(res.data.terakhir_diupdate);
                    document.getElementById('res_time').innerText = date.toLocaleString('id-ID', { dateStyle: 'long', timeStyle: 'short' });
                    document.getElementById('res_badge_time').innerText = `Update: ${date.toLocaleTimeString('id-ID', {hour: '2-digit', minute: '2-digit'})}`;
                    document.getElementById('res_eta').innerText = `${res.data.eta}`;

                    updateTimeline(res.data.stage);

                    // Update Map & Routing secara dinamis
                    if (res.data.lat && res.data.lng) {
                        destination = [res.data.lat, res.data.lng];
                        initializeOrUpdateMap(res.data.lat, res.data.lng, false);
                        detectAndDrawRoute();
                    }

                    // Stop polling if order has arrived
                    if (res.data.stage === 3) {
                        clearInterval(trackingInterval);
                        trackingInterval = null;
                        activeTrackingOrderId = null;
                        showToast('Paket Anda telah sampai! Terima kasih telah menggunakan layanan kami.', 'success');
                    }
                }
            } catch (error) {
                console.error('Error polling tracking status:', error);
            }
        }

        function updateTimeline(stage) {
            const progressLine = document.getElementById('progress_line');
            if (!progressLine) return;
            
            const percentages = [0, 33, 66, 100];
            progressLine.style.width = percentages[stage] + '%';
            
            for (let i = 0; i <= 3; i++) {
                const node = document.getElementById(`step_node_${i}`);
                if (!node) continue;
                const circle = node.querySelector('.step-circle');
                const label = node.querySelector('.step-label');
                
                circle.className = 'step-circle w-10 h-10 min-w-[2.5rem] min-h-[2.5rem] max-w-[2.5rem] max-h-[2.5rem] shrink-0 rounded-full flex items-center justify-center border-2 transition-all duration-500';
                label.className = 'step-label text-[9px] sm:text-[11px] font-bold mt-2.5 transition-all duration-500';
                
                if (i < stage) {
                    // Completed status
                    circle.className = 'step-circle w-10 h-10 min-w-[2.5rem] min-h-[2.5rem] max-w-[2.5rem] max-h-[2.5rem] shrink-0 rounded-full flex items-center justify-center border-2 transition-all duration-500 bg-white border-indigo-650 text-indigo-600 shadow-sm';
                    label.className = 'step-label text-[9px] sm:text-[11px] font-extrabold mt-2.5 transition-all duration-500 text-indigo-650';
                } else if (i === stage) {
                    // Active status
                    circle.className = 'step-circle w-10 h-10 min-w-[2.5rem] min-h-[2.5rem] max-w-[2.5rem] max-h-[2.5rem] shrink-0 rounded-full flex items-center justify-center border-2 transition-all duration-500 bg-[#FB8500] border-white text-white shadow-[0_0_12px_rgba(251,133,0,0.5)] scale-110 ring-4 ring-orange-500/20';
                    label.className = 'step-label text-[9px] sm:text-[11px] font-black mt-2.5 transition-all duration-500 text-slate-800';
                } else {
                    // Inactive status
                    circle.className = 'step-circle w-10 h-10 min-w-[2.5rem] min-h-[2.5rem] max-w-[2.5rem] max-h-[2.5rem] shrink-0 rounded-full flex items-center justify-center border-2 transition-all duration-500 bg-white border-slate-200 text-slate-400';
                    label.className = 'step-label text-[9px] sm:text-[11px] font-bold mt-2.5 transition-all duration-500 text-slate-400';
                }
            }
        }

        // Notification panel toggle operations
        function toggleNotifications() {
            const dropdown = document.getElementById('notificationDropdown');
            if (dropdown.classList.contains('hidden')) {
                fetchNotifications();
                dropdown.classList.remove('hidden');
            } else {
                dropdown.classList.add('hidden');
            }
        }
        async function fetchNotifications() {
            try {
                const response = await fetch('/api/v1/notifications');
                if (!response.ok) return;
                const data = await response.json();
                
                const badge = document.getElementById('notifBadge');
                const badgeMobile = document.getElementById('notifBadgeMobile');
                const unreadCount = data.unread_count || 0;
                
                if (unreadCount > 0) {
                    if (badge) {
                        badge.textContent = unreadCount;
                        badge.classList.remove('hidden');
                    }
                    if (badgeMobile) {
                        badgeMobile.textContent = unreadCount;
                        badgeMobile.classList.remove('hidden');
                    }
                } else {
                    if (badge) badge.classList.add('hidden');
                    if (badgeMobile) badgeMobile.classList.add('hidden');
                }
                
                const list = document.getElementById('notifList');
                if (list) {
                    list.innerHTML = '';
                    
                    if (data.notifications && data.notifications.length) {
                        data.notifications.forEach(notif => {
                            const item = document.createElement('div');
                            item.className = 'p-3 border-b border-slate-100 hover:bg-slate-50 rounded-xl cursor-pointer transition';
                            item.innerHTML = `<strong class="block text-[11px] text-slate-800 font-bold">${notif.title}</strong>` +
                                             `<span class="block text-[10px] text-slate-500 mt-0.5">${notif.body}</span>` +
                                             `<span class="block text-[8px] text-slate-400 mt-1">${new Date(notif.created_at).toLocaleTimeString('id-ID')}</span>`;
                            item.onclick = () => markAsRead(notif.id, notif.action_url);
                            list.appendChild(item);
                        });
                    } else {
                        list.innerHTML = '<div class="p-4 text-center text-slate-400 font-semibold">Tidak ada notifikasi baru.</div>';
                    }
                }
            } catch (e) {
                console.error(e);
            }
        }
 
        async function markAsRead(id, actionUrl = null) {
            try {
                await fetch(`/api/v1/notifications/${id}/read`, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
                });
                if (actionUrl) {
                    window.location.href = actionUrl;
                } else {
                    fetchNotifications();
                }
            } catch (e) {
                console.error(e);
                if (actionUrl) {
                    window.location.href = actionUrl;
                }
            }
        }

        // Define tab switching and shipment selection functions globally
        window.switchNotifTab = function(tabName) {
            const tabNotifBtn = document.getElementById('tabNotifBtn');
            const tabShipmentBtn = document.getElementById('tabShipmentBtn');
            const panelNotif = document.getElementById('panelNotif');
            const panelShipments = document.getElementById('panelShipments');
            
            if (!tabNotifBtn || !tabShipmentBtn || !panelNotif || !panelShipments) return;
            
            const activeClasses = ['bg-white', 'text-slate-800', 'shadow-sm', 'border', 'border-slate-200/40'];
            const inactiveClasses = ['text-slate-450', 'hover:text-slate-800'];
            
            if (tabName === 'notif') {
                activeClasses.forEach(c => {
                    tabNotifBtn.classList.add(c);
                    tabShipmentBtn.classList.remove(c);
                });
                inactiveClasses.forEach(c => {
                    tabNotifBtn.classList.remove(c);
                    tabShipmentBtn.classList.add(c);
                });
                panelNotif.classList.remove('hidden');
                panelShipments.classList.add('hidden');
            } else if (tabName === 'shipments') {
                activeClasses.forEach(c => {
                    tabShipmentBtn.classList.add(c);
                    tabNotifBtn.classList.remove(c);
                });
                inactiveClasses.forEach(c => {
                    tabShipmentBtn.classList.remove(c);
                    tabNotifBtn.classList.add(c);
                });
                panelShipments.classList.remove('hidden');
                panelNotif.classList.add('hidden');
            }
        };

        window.selectShipmentResi = function(orderId) {
            const input = document.getElementById('search_order_id');
            if (input) {
                input.value = orderId;
            }
            // Trigger tracking
            searchTracking();
            
            // Close dropdown
            const dropdown = document.getElementById('notificationDropdown');
            if (dropdown) {
                dropdown.classList.add('hidden');
            }
            
            // Revert active tab indicator back to tab 2 (Track)
            if (typeof setActiveTab === 'function') {
                setActiveTab(2);
            }
            
            // Scroll back to top
            if (typeof scrollToTop === 'function') {
                scrollToTop();
            }
        };

        window.openMyShipments = function() {
            const dropdown = document.getElementById('notificationDropdown');
            if (dropdown) {
                dropdown.classList.remove('hidden');
            }
            window.switchNotifTab('shipments');
            if (typeof fetchNotifications === 'function') {
                fetchNotifications();
            }
        };

        document.addEventListener('click', function(event) {
            const dropdown = document.getElementById('notificationDropdown');
            const btn = document.getElementById('notifBtn');
            const mobileBtn = document.getElementById('mobileTab1');
            
            // Do not close if clicking the notification toggle buttons
            const clickOnToggle = (btn && btn.contains(event.target)) || 
                                  (mobileBtn && mobileBtn.contains(event.target)) || 
                                  event.target.closest('[onclick="toggleNotificationsMobile()"]');
                                  
            if (dropdown && !dropdown.contains(event.target) && !clickOnToggle) {
                dropdown.classList.add('hidden');
            }
        });

        // Mobile bottom navigation actions
        let activeMobileTab = 2; // Default active tab is Track (Tab 2)

        function setActiveTab(tabIndex) {
            // Revert active tab indicator
            document.querySelectorAll('.mobile-tab-btn').forEach(btn => {
                btn.classList.remove('text-[#FB8500]');
                btn.classList.add('text-slate-350');
            });
            
            const activeBtn = document.getElementById(`mobileTab${tabIndex}`);
            if (activeBtn) {
                activeBtn.classList.remove('text-slate-350');
                activeBtn.classList.add('text-[#FB8500]');
                activeMobileTab = tabIndex;
            }
        }

        function scrollToHistory() {
            const section = document.getElementById('myOrdersSection') || document.getElementById('search_order_id');
            if (section) {
                section.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        }

        function scrollToTop() {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        function showBookmarkToast() {
            const input = document.getElementById('search_order_id');
            const resi = input ? input.value.trim() : '';
            if (resi) {
                let bookmarks = JSON.parse(localStorage.getItem('track_bookmarks') || '[]');
                if (!bookmarks.includes(resi)) {
                    bookmarks.push(resi);
                    localStorage.setItem('track_bookmarks', JSON.stringify(bookmarks));
                    showToast('Resi berhasil disimpan ke Bookmark!', 'success');
                } else {
                    showToast('Resi sudah ada di Bookmark.', 'info');
                }
            } else {
                showToast('Masukkan nomor resi untuk menyimpannya ke bookmark.', 'warning');
            }
        }

        function showToast(message, type = 'info') {
            const container = document.getElementById('toastContainer') || (() => {
                const c = document.createElement('div');
                c.id = 'toastContainer';
                c.className = 'fixed top-20 right-4 z-[60] flex flex-col gap-2 max-w-sm pointer-events-none';
                document.body.appendChild(c);
                return c;
            })();
            
            const toast = document.createElement('div');
            toast.className = 'p-4 bg-white/95 backdrop-blur-md border border-slate-200/50 shadow-xl rounded-2xl flex items-center gap-3 transition duration-300 transform translate-x-10 opacity-0 pointer-events-auto';
            
            let color = 'text-slate-550';
            let icon = '<svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>';
            
            if (type === 'success') {
                color = 'text-emerald-500 bg-emerald-50 border-emerald-100';
                icon = '<svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>';
            } else if (type === 'warning') {
                color = 'text-orange-500 bg-orange-50 border-orange-100';
            } else if (type === 'info') {
                color = 'text-indigo-500 bg-indigo-50 border-indigo-100';
                icon = '<svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 111.063.852l-.708 2.836a.75.75 0 001.063.852l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z"/></svg>';
            }
            
            toast.innerHTML = `<div class="p-2 rounded-xl ${color}">${icon}</div><div class="text-xs font-bold text-slate-700">${message}</div>`;
            container.appendChild(toast);
            
            setTimeout(() => {
                toast.classList.remove('translate-x-10', 'opacity-0');
            }, 10);
            
            setTimeout(() => {
                toast.classList.add('translate-x-10', 'opacity-0');
                setTimeout(() => toast.remove(), 300);
            }, 3000);
        }

        function toggleMobileSettings() {
            const drawer = document.getElementById('mobileSettingsDrawer');
            const content = document.getElementById('drawerContent');
            if (drawer.classList.contains('hidden')) {
                drawer.classList.remove('hidden');
                setTimeout(() => {
                    content.classList.remove('translate-y-full');
                }, 10);
                fetchNotifications();
            } else {
                content.classList.add('translate-y-full');
                setTimeout(() => {
                    drawer.classList.add('hidden');
                    setActiveTab(activeMobileTab);
                }, 300);
            }
        }

        function toggleNotificationsMobile() {
            toggleMobileSettings();
            toggleNotifications();
        }

        // Profile drawer view switching
        function switchDrawerToEdit() {
            document.getElementById('drawerSummaryView').classList.add('hidden');
            document.getElementById('drawerEditView').classList.remove('hidden');
            document.getElementById('drawerTitle').textContent = 'Ubah Profil & Alamat';
            
            // Load EMSIFA regions if not loaded yet
            loadProfileProvinces();
        }

        function switchDrawerToSummary() {
            document.getElementById('drawerEditView').classList.add('hidden');
            document.getElementById('drawerSummaryView').classList.remove('hidden');
            document.getElementById('drawerTitle').textContent = 'Pengaturan Akun';
        }

        let profileProvincesLoaded = false;
        function loadProfileProvinces() {
            if (profileProvincesLoaded) return;
            
            const provSelect = document.getElementById('profile_province');
            const regSelect = document.getElementById('profile_regency');
            const distSelect = document.getElementById('profile_district');
            const villSelect = document.getElementById('profile_village');

            fetch('https://www.emsifa.com/api-wilayah-indonesia/api/provinces.json')
                .then(res => res.json())
                .then(data => {
                    provSelect.innerHTML = '<option value="" disabled selected>Pilih Provinsi</option>';
                    data.forEach(prov => {
                        const opt = document.createElement('option');
                        opt.value = prov.id;
                        opt.textContent = prov.name;
                        provSelect.appendChild(opt);
                    });
                    profileProvincesLoaded = true;
                })
                .catch(err => console.error('Gagal memuat provinsi:', err));

            // Reset dropdown helper
            function resetSelect(el, text) {
                el.innerHTML = `<option value="" disabled selected>${text}</option>`;
                el.disabled = true;
            }

            // Province Listener
            provSelect.addEventListener('change', function() {
                resetSelect(regSelect, 'Pilih Kota');
                resetSelect(distSelect, 'Pilih Kecamatan');
                resetSelect(villSelect, 'Pilih Kelurahan');
                
                if (this.value) {
                    fetch(`https://www.emsifa.com/api-wilayah-indonesia/api/regencies/${this.value}.json`)
                        .then(res => res.json())
                        .then(data => {
                            regSelect.disabled = false;
                            data.forEach(reg => {
                                const opt = document.createElement('option');
                                opt.value = reg.id;
                                opt.textContent = reg.name;
                                regSelect.appendChild(opt);
                            });
                        });
                }
            });

            // Regency Listener
            regSelect.addEventListener('change', function() {
                resetSelect(distSelect, 'Pilih Kecamatan');
                resetSelect(villSelect, 'Pilih Kelurahan');
                
                if (this.value) {
                    fetch(`https://www.emsifa.com/api-wilayah-indonesia/api/districts/${this.value}.json`)
                        .then(res => res.json())
                        .then(data => {
                            distSelect.disabled = false;
                            data.forEach(dist => {
                                const opt = document.createElement('option');
                                opt.value = dist.id;
                                opt.textContent = dist.name;
                                distSelect.appendChild(opt);
                            });
                        });
                }
            });

            // District Listener
            distSelect.addEventListener('change', function() {
                resetSelect(villSelect, 'Pilih Kelurahan');
                
                if (this.value) {
                    fetch(`https://www.emsifa.com/api-wilayah-indonesia/api/villages/${this.value}.json`)
                        .then(res => res.json())
                        .then(data => {
                            villSelect.disabled = false;
                            data.forEach(vill => {
                                const opt = document.createElement('option');
                                opt.value = vill.id;
                                opt.textContent = vill.name;
                                villSelect.appendChild(opt);
                            });
                        });
                }
            });
        }

        async function submitProfileForm(event) {
            event.preventDefault();
            
            const saveBtn = document.getElementById('saveProfileBtn');
            const originalText = saveBtn.innerHTML;
            saveBtn.disabled = true;
            saveBtn.innerHTML = `<svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Menyimpan...`;

            // Concatenate address if region inputs are filled
            const provText = document.getElementById('profile_province').options[document.getElementById('profile_province').selectedIndex]?.text;
            const regText = document.getElementById('profile_regency').options[document.getElementById('profile_regency').selectedIndex]?.text;
            const distText = document.getElementById('profile_district').options[document.getElementById('profile_district').selectedIndex]?.text;
            const villText = document.getElementById('profile_village').options[document.getElementById('profile_village').selectedIndex]?.text;
            const detail = document.getElementById('profile_detail_address').value.trim();

            let finalAddress = document.getElementById('profile_address_hidden').value;

            if (provText && regText && distText && villText && detail && 
                !provText.includes('Pilih') && !regText.includes('Pilih') && !distText.includes('Pilih') && !villText.includes('Pilih')) {
                finalAddress = `${detail}, Kel. ${villText}, Kec. ${distText}, ${regText}, Provinsi ${provText}`;
            } else if (detail) {
                finalAddress = detail;
            }

            document.getElementById('profile_address_hidden').value = finalAddress;

            const form = document.getElementById('profileEditForm');
            const formData = new FormData(form);

            try {
                const response = await fetch('{{ route("customer.profile.update") }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                const res = await response.json();
                
                if (response.ok && res.status === 'Success') {
                    showToast(res.message, 'success');
                    
                    // Update DOM values dynamically
                    document.getElementById('drawerName').textContent = res.data.name;
                    document.getElementById('drawerEmail').textContent = res.data.email;
                    document.getElementById('drawerPhone').textContent = res.data.phone || 'Telepon belum diatur';
                    document.getElementById('drawerAddress').textContent = res.data.address || 'Alamat belum diatur. Lengkapi alamat untuk memudahkan pengantaran.';
                    document.getElementById('drawerAvatar').textContent = res.data.name.substring(0, 1);
                    
                    // Pre-fill the edit form inputs with the newly saved values
                    document.getElementById('edit_name').value = res.data.name;
                    document.getElementById('edit_email').value = res.data.email;
                    document.getElementById('edit_phone').value = res.data.phone || '';
                    document.getElementById('profile_address_hidden').value = res.data.address || '';
                    
                    // Return back to summary view
                    switchDrawerToSummary();
                } else {
                    const errorMsg = res.message || 'Gagal menyimpan perubahan.';
                    showToast(errorMsg, 'error');
                }
            } catch (err) {
                console.error(err);
                showToast('Terjadi kesalahan koneksi atau server.', 'error');
            } finally {
                saveBtn.disabled = false;
                saveBtn.innerHTML = originalText;
            }
        }

        // --- Bookmark Drawer Actions ---
        window.toggleMobileBookmarks = function() {
            const drawer = document.getElementById('mobileBookmarksDrawer');
            const content = document.getElementById('bookmarksDrawerContent');
            if (drawer) {
                if (drawer.classList.contains('hidden')) {
                    renderBookmarksList();
                    drawer.classList.remove('hidden');
                    setTimeout(() => {
                        if (content) content.classList.remove('translate-y-full');
                    }, 10);
                } else {
                    if (content) content.classList.add('translate-y-full');
                    setTimeout(() => {
                        drawer.classList.add('hidden');
                        setActiveTab(activeMobileTab);
                    }, 300);
                }
            }
        }

        window.renderBookmarksList = function() {
            const list = document.getElementById('bookmarksList');
            if (!list) return;
            
            const bookmarks = JSON.parse(localStorage.getItem('track_bookmarks') || '[]');
            list.innerHTML = '';
            
            if (bookmarks.length === 0) {
                list.innerHTML = `
                    <div class="p-6 text-center text-slate-400 font-semibold text-xs border border-dashed border-slate-200 rounded-2xl bg-slate-50">
                        Tidak ada resi tersimpan.<br>
                        <span class="text-[10px] text-slate-400 font-medium mt-1.5 block">Ketik nomor resi lalu klik "Bookmark Resi Saat Ini" untuk menyimpan.</span>
                    </div>
                `;
                return;
            }
            
            bookmarks.forEach(resi => {
                const item = document.createElement('div');
                item.className = 'flex items-center justify-between p-3.5 bg-slate-50 hover:bg-slate-100 rounded-2xl border border-slate-250 transition group';
                
                item.innerHTML = `
                    <button onclick="selectBookmarkResi('${resi}')" class="flex-1 text-left font-bold text-slate-700 hover:text-[#FB8500] text-xs truncate cursor-pointer transition">
                        #${resi}
                    </button>
                    <button onclick="deleteBookmark('${resi}')" class="text-slate-350 hover:text-rose-600 transition font-black text-sm px-2.5 py-1.5 cursor-pointer bg-white border border-slate-200 rounded-xl shadow-sm" title="Hapus">&times;</button>
                `;
                list.appendChild(item);
            });
        }

        window.selectBookmarkResi = function(resi) {
            const input = document.getElementById('search_order_id');
            if (input) input.value = resi;
            toggleMobileBookmarks();
            searchTracking();
        }

        window.deleteBookmark = function(resi) {
            let bookmarks = JSON.parse(localStorage.getItem('track_bookmarks') || '[]');
            bookmarks = bookmarks.filter(b => b !== resi);
            localStorage.setItem('track_bookmarks', JSON.stringify(bookmarks));
            renderBookmarksList();
            showToast('Bookmark berhasil dihapus.', 'success');
        }

        window.clearAllBookmarks = function() {
            const bookmarks = JSON.parse(localStorage.getItem('track_bookmarks') || '[]');
            if (bookmarks.length === 0) {
                showToast('Tidak ada bookmark untuk dihapus.', 'info');
                return;
            }
            if (confirm('Hapus semua resi di bookmark?')) {
                localStorage.removeItem('track_bookmarks');
                renderBookmarksList();
                showToast('Semua bookmark berhasil dihapus.', 'success');
            }
        }

        window.addCurrentResiToBookmark = function() {
            const searchInput = document.getElementById('search_order_id');
            const resi = searchInput ? searchInput.value.trim() : '';
            if (!resi) {
                showToast('Masukkan nomor resi terlebih dahulu.', 'warning');
                return;
            }
            
            let bookmarks = JSON.parse(localStorage.getItem('track_bookmarks') || '[]');
            if (!bookmarks.includes(resi)) {
                bookmarks.push(resi);
                localStorage.setItem('track_bookmarks', JSON.stringify(bookmarks));
                renderBookmarksList();
                showToast('Resi berhasil disimpan ke Bookmark!', 'success');
            } else {
                showToast('Resi sudah ada di Bookmark.', 'info');
            }
        }
    </script>

    @auth
        <!-- Docked Curved Bottom Navigation Bar for Mobile / Android (Edge-to-Edge) -->
        <div class="fixed bottom-0 left-0 right-0 h-16 z-40 md:hidden flex items-center justify-between">
            <!-- SVG Background with Cradle Dip -->
            <div class="absolute inset-0 z-0">
                <svg class="w-full h-full text-white filter drop-shadow-[0_-8px_24px_rgba(0,0,0,0.08)]" viewBox="0 0 360 64" preserveAspectRatio="none" fill="currentColor">
                    <path d="M 0 0 L 150 0 C 158 0, 160 28, 180 28 C 200 28, 202 0, 210 0 L 360 0 L 360 64 L 0 64 Z" />
                </svg>
            </div>

            <!-- Left Icons (w-[calc(50%-30px)]) -->
            <div class="relative z-10 flex items-center justify-around w-[calc(50%-30px)] h-full">
                <!-- Tab 1: List / History -->
                <button id="mobileTab1" onclick="openMyShipments(); setActiveTab(1)" class="mobile-tab-btn text-slate-400 hover:text-slate-600 transition flex flex-col items-center justify-center cursor-pointer relative py-2">
                    <svg class="w-6.5 h-6.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="4" width="18" height="16" rx="2" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M9 4v16M13 8h5M13 12h5M13 16h3" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </button>
                
                <!-- Tab 2: Dashboard / Leaf (Active by default) -->
                <button id="mobileTab2" onclick="scrollToTop(); setActiveTab(2)" class="mobile-tab-btn text-[#FB8500] transition flex flex-col items-center justify-center cursor-pointer relative py-2">
                    <svg class="w-6.5 h-6.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linejoin="round" stroke-linecap="round">
                        <path d="M17 8c-2.5-2.5-6.5-1.5-8 1.5-1 2-0.5 4.5 1 5.5l-2.5 2.5M11.5 12.5c1.5-1.5 3.5-2.5 5.5-2.5" />
                        <path d="M6 7.5c-0.8-0.8-2 0-2.2 1s0.8 1 2.2-1z" />
                        <path d="M9 4.5c-0.6-0.6-1.5 0-1.7 0.8s0.6 0.8 1.7-0.8z" />
                    </svg>
                </button>
            </div>

            <!-- Center Cradle & FAB (w-[60px]) -->
            <div class="relative z-10 w-[60px] h-full flex items-center justify-center">
                <a href="{{ route('customer.order.create') }}" class="absolute -top-5 w-12 h-12 rounded-full bg-gradient-to-tr from-[#FFB703] to-[#FB8500] hover:from-[#FB8500] hover:to-[#FFB703] active:scale-95 transition-all duration-300 flex items-center justify-center text-white shadow-lg shadow-orange-500/20 cursor-pointer">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="3.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                </a>
            </div>

            <!-- Right Icons (w-[calc(50%-30px)]) -->
            <div class="relative z-10 flex items-center justify-around w-[calc(50%-30px)] h-full">
                <!-- Tab 3: Bookmark -->
                <button id="mobileTab3" onclick="toggleMobileBookmarks(); setActiveTab(3)" class="mobile-tab-btn text-slate-400 hover:text-slate-600 transition flex flex-col items-center justify-center cursor-pointer relative py-2">
                    <svg class="w-6.5 h-6.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M19 21l-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z" />
                    </svg>
                </button>
                
                <!-- Tab 4: Wrench / Settings -->
                <button id="mobileTab4" onclick="toggleMobileSettings(); setActiveTab(4)" class="mobile-tab-btn text-slate-400 hover:text-slate-600 transition flex flex-col items-center justify-center cursor-pointer relative py-2">
                    <svg class="w-6.5 h-6.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- Mobile Bookmarks Drawer -->
        <div id="mobileBookmarksDrawer" class="fixed inset-0 z-50 hidden">
            <!-- Backdrop -->
            <div onclick="toggleMobileBookmarks()" class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity"></div>
            <!-- Drawer Content -->
            <div class="absolute bottom-0 left-0 right-0 md:left-auto md:right-6 md:bottom-6 md:w-96 bg-white rounded-t-[2.5rem] md:rounded-2xl p-6 shadow-2xl transition-transform transform translate-y-full duration-300 z-50 max-h-[90vh] overflow-y-auto" id="bookmarksDrawerContent">
                <div class="w-12 h-1 bg-slate-200 rounded-full mx-auto mb-6"></div>
                <div class="flex items-center justify-between mb-4 pb-2 border-b border-slate-100">
                    <h3 class="text-lg font-black text-slate-800">Bookmark Resi</h3>
                    <button onclick="clearAllBookmarks()" class="text-slate-400 hover:text-rose-600 font-extrabold text-[10px] uppercase transition cursor-pointer">Hapus Semua</button>
                </div>
                
                <!-- Bookmarks List -->
                <div id="bookmarksList" class="space-y-2.5 max-h-[50vh] overflow-y-auto pr-1">
                    <!-- Dynamic bookmarks will be rendered here via JS -->
                </div>
                
                <!-- Actions -->
                <div class="pt-4 border-t border-slate-100 mt-4 space-y-2">
                    <button onclick="addCurrentResiToBookmark()" class="w-full bg-[#FB8500]/10 hover:bg-[#FB8500]/15 text-[#FB8500] py-3 rounded-xl text-xs font-black transition duration-200 cursor-pointer flex items-center justify-center gap-1.5">
                        <svg class="w-4.5 h-4.5 text-[#FB8500]" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                        Bookmark Resi Saat Ini
                    </button>
                    <button onclick="toggleMobileBookmarks()" class="w-full bg-slate-900 text-white hover:bg-slate-800 py-3 rounded-xl text-xs font-bold transition duration-200 cursor-pointer">
                        Tutup
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Settings Drawer -->
        <div id="mobileSettingsDrawer" class="fixed inset-0 z-50 hidden">
            <!-- Backdrop -->
            <div onclick="toggleMobileSettings()" class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity"></div>
            <!-- Drawer Content (Sleek right-side popover on desktop, bottom-sheet on mobile) -->
            <div class="absolute bottom-0 left-0 right-0 md:left-auto md:right-6 md:bottom-6 md:w-96 bg-white rounded-t-[2.5rem] md:rounded-2xl p-6 shadow-2xl transition-transform transform translate-y-full duration-300 z-50 max-h-[90vh] overflow-y-auto" id="drawerContent">
                <div class="w-12 h-1 bg-slate-200 rounded-full mx-auto mb-6"></div>
                <h3 class="text-lg font-black text-slate-800 mb-4 text-center" id="drawerTitle">Pengaturan Akun</h3>
                
                <!-- 1. VIEW SUMMARY (Tampilan Utama Drawer) -->
                <div id="drawerSummaryView" class="space-y-4">
                    <!-- User Info Card -->
                    <div class="p-4 bg-slate-50 rounded-2xl flex items-center gap-4 border border-slate-100">
                        <div id="drawerAvatar" class="w-12 h-12 rounded-full bg-[#FB8500]/10 text-[#FB8500] flex items-center justify-center font-extrabold text-lg uppercase shrink-0">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </div>
                        <div class="min-w-0 flex-1">
                            <h4 id="drawerName" class="font-bold text-slate-800 text-sm truncate">{{ Auth::user()->name }}</h4>
                            <p id="drawerEmail" class="text-xs text-slate-400 font-medium truncate">{{ Auth::user()->email }}</p>
                            <p id="drawerPhone" class="text-[10px] text-[#FB8500] font-bold mt-0.5 truncate">{{ Auth::user()->phone ?? 'Telepon belum diatur' }}</p>
                        </div>
                    </div>

                    <!-- User Address Card -->
                    <div class="p-4 bg-orange-50/50 rounded-2xl border border-orange-100/50">
                        <span class="block text-[9px] font-black text-[#FB8500] uppercase tracking-widest mb-1">Alamat Utama</span>
                        <p id="drawerAddress" class="text-xs font-semibold text-slate-600 leading-relaxed">
                            {{ Auth::user()->address ?? 'Alamat belum diatur. Lengkapi alamat untuk memudahkan pengantaran.' }}
                        </p>
                    </div>

                    <!-- Action: Ubah Profil & Alamat -->
                    <button onclick="switchDrawerToEdit()" class="w-full flex items-center justify-between p-4 bg-white hover:bg-slate-50 rounded-xl transition text-left cursor-pointer border border-slate-100/50">
                        <div class="flex items-center gap-3 text-slate-700">
                            <svg class="w-5 h-5 text-[#FB8500] shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                            </svg>
                            <span class="text-xs font-bold text-slate-700">Ubah Profil & Alamat</span>
                        </div>
                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
                    </button>

                    <!-- Notifications toggle or list -->
                    <button onclick="toggleNotificationsMobile()" class="w-full flex items-center justify-between p-4 hover:bg-slate-50 rounded-xl transition text-left cursor-pointer border border-slate-100/50 bg-white">
                        <div class="flex items-center gap-3 text-slate-700">
                            <svg class="w-5 h-5 text-slate-500 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" /></svg>
                            <span class="text-xs font-bold text-slate-700">Pemberitahuan</span>
                        </div>
                        <span id="notifBadgeMobile" class="bg-red-500 text-white text-[10px] font-black rounded-full px-2 py-0.5 hidden">0</span>
                    </button>

                    <!-- Log Out Button -->
                    <form method="POST" action="{{ route('logout') }}" class="m-0 p-0">
                        @csrf
                        <button type="submit" class="w-full flex items-center gap-3 p-4 text-red-650 hover:bg-red-50 rounded-xl transition text-left font-bold cursor-pointer border border-slate-100/50 bg-white">
                            <svg class="w-5 h-5 text-red-500 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75" /></svg>
                            <span class="text-xs text-red-600">Keluar Sesi (Logout)</span>
                        </button>
                    </form>
                </div>

                <!-- 2. EDIT FORM (Tampilan Edit Profil & Alamat - Tersembunyi Awalnya) -->
                <div id="drawerEditView" class="hidden space-y-4 pb-4">
                    <form id="profileEditForm" onsubmit="submitProfileForm(event)" class="space-y-4 m-0 p-0">
                        @csrf
                        <!-- Input Nama -->
                        <div>
                            <label class="block text-[10px] font-extrabold text-slate-400 uppercase tracking-widest mb-1.5">Nama Lengkap</label>
                            <input type="text" id="edit_name" name="name" value="{{ Auth::user()->name }}" required
                                   class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:border-orange-500 focus:ring-4 focus:ring-orange-100 transition font-bold text-slate-800 text-xs bg-slate-50/50">
                        </div>

                        <!-- Input Email -->
                        <div>
                            <label class="block text-[10px] font-extrabold text-slate-400 uppercase tracking-widest mb-1.5">Email</label>
                            <input type="email" id="edit_email" name="email" value="{{ Auth::user()->email }}" required
                                   class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:border-orange-500 focus:ring-4 focus:ring-orange-100 transition font-bold text-slate-800 text-xs bg-slate-50/50">
                        </div>

                        <!-- Input Telepon -->
                        <div>
                            <label class="block text-[10px] font-extrabold text-slate-400 uppercase tracking-widest mb-1.5">Nomor Telepon</label>
                            <input type="text" id="edit_phone" name="phone" value="{{ Auth::user()->phone }}" placeholder="Contoh: 08123456789"
                                   class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:border-orange-500 focus:ring-4 focus:ring-orange-100 transition font-bold text-slate-800 text-xs bg-slate-50/50">
                        </div>

                        <!-- Indonesian Address Regional Dropdowns -->
                        <div class="border-t border-slate-100 pt-3 space-y-3">
                            <span class="block text-[10px] font-black text-slate-800 uppercase tracking-wider mb-1">Pilih Wilayah Alamat</span>
                            
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-[9px] font-extrabold text-slate-400 uppercase tracking-widest mb-1">Provinsi</label>
                                    <select id="profile_province"
                                            class="w-full px-3 py-2 rounded-xl border border-slate-200 focus:outline-none focus:border-orange-500 transition font-bold text-slate-800 text-[11px] bg-slate-50/50">
                                        <option value="" disabled selected>Pilih Provinsi</option>
                                    </select>
                                </div>
                                
                                <div>
                                    <label class="block text-[9px] font-extrabold text-slate-400 uppercase tracking-widest mb-1">Kabupaten/Kota</label>
                                    <select id="profile_regency" disabled
                                            class="w-full px-3 py-2 rounded-xl border border-slate-200 focus:outline-none focus:border-orange-500 transition font-bold text-slate-800 text-[11px] bg-slate-50/50 disabled:opacity-50">
                                        <option value="" disabled selected>Pilih Kota</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-[9px] font-extrabold text-slate-400 uppercase tracking-widest mb-1">Kecamatan</label>
                                    <select id="profile_district" disabled
                                            class="w-full px-3 py-2 rounded-xl border border-slate-200 focus:outline-none focus:border-orange-500 transition font-bold text-slate-800 text-[11px] bg-slate-50/50 disabled:opacity-50">
                                        <option value="" disabled selected>Pilih Kecamatan</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-[9px] font-extrabold text-slate-400 uppercase tracking-widest mb-1">Kelurahan/Desa</label>
                                    <select id="profile_village" disabled
                                            class="w-full px-3 py-2 rounded-xl border border-slate-200 focus:outline-none focus:border-orange-500 transition font-bold text-slate-800 text-[11px] bg-slate-50/50 disabled:opacity-50">
                                        <option value="" disabled selected>Pilih Kelurahan</option>
                                    </select>
                                </div>
                            </div>

                            <div>
                                <label class="block text-[10px] font-extrabold text-slate-400 uppercase tracking-widest mb-1">Alamat Lengkap & Jalan/No. Rumah</label>
                                <textarea id="profile_detail_address" rows="2" placeholder="Contoh: Jl. Sudirman No. 45, RT 01/RW 02"
                                          class="w-full px-4 py-2 rounded-xl border border-slate-200 focus:outline-none focus:border-orange-500 transition font-semibold text-slate-800 text-xs bg-slate-50/50"></textarea>
                            </div>

                            <!-- Hidden field to submit concatenated address string -->
                            <input type="hidden" name="address" id="profile_address_hidden" value="{{ Auth::user()->address }}">
                        </div>

                        <!-- Submit Buttons -->
                        <div class="flex items-center justify-end gap-3 pt-3 border-t border-slate-100">
                            <button type="button" onclick="switchDrawerToSummary()" class="px-4 py-2.5 rounded-xl border border-slate-200 font-bold text-slate-500 hover:text-slate-800 text-xs transition">
                                Batal
                            </button>
                            <button type="submit" id="saveProfileBtn" class="bg-gradient-to-tr from-[#FFB703] to-[#FB8500] hover:from-[#FB8500] hover:to-[#FFB703] text-white px-6 py-2.5 rounded-xl font-bold shadow-md shadow-orange-500/20 transition-all duration-300 flex items-center justify-center gap-1.5 text-xs cursor-pointer">
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @php
            $unpaidOrder = isset($myOrders) ? $myOrders->where('payment_status', 'UNPAID')->first() : null;
        @endphp

        @if($unpaidOrder)
            <!-- Floating Unpaid Order Notification Banner -->
            <div id="unpaidOrderBanner" class="fixed top-20 left-1/2 -translate-x-1/2 z-[60] w-[calc(100vw-2rem)] max-w-md transition-all duration-500 transform translate-y-[-20px] opacity-0">
                <div onclick="window.location.href='{{ route('customer.payment', $unpaidOrder->order_id) }}'" class="bg-gradient-to-tr from-[#FFB703] to-[#FB8500] text-white rounded-2xl p-4 shadow-2xl shadow-orange-500/30 border border-white/20 flex items-center justify-between gap-4 cursor-pointer hover:scale-[1.02] active:scale-[0.98] transition-all duration-300">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-white/25 flex items-center justify-center shrink-0 border border-white/20 animate-pulse">
                            <svg class="w-5.5 h-5.5 text-white" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="min-w-0">
                            <span class="block text-[9px] font-black text-orange-100 uppercase tracking-widest leading-none mb-1">Tagihan Tertunda</span>
                            <p class="text-xs font-bold leading-normal truncate">Order #{{ $unpaidOrder->order_id }} belum dibayar</p>
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-1.5 shrink-0" onclick="event.stopPropagation()">
                        <a href="{{ route('customer.payment', $unpaidOrder->order_id) }}" class="bg-slate-900 text-white px-3.5 py-2.5 rounded-xl text-[10px] font-extrabold shadow-sm transition hover:bg-slate-800 flex items-center justify-center">
                            Bayar
                        </a>
                        <form action="{{ route('customer.order.cancel', $unpaidOrder->order_id) }}" method="POST" class="inline m-0 p-0" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan pesanan ini?')">
                            @csrf
                            <button type="submit" class="bg-white/25 hover:bg-rose-600 hover:text-white text-white px-3 py-2.5 rounded-xl text-[10px] font-extrabold shadow-sm transition cursor-pointer">
                                Batal
                            </button>
                        </form>
                        <button onclick="dismissUnpaidBanner()" class="text-orange-100 hover:text-white transition font-black text-lg px-2.5 py-2 leading-none cursor-pointer bg-white/10 hover:bg-white/20 rounded-xl">&times;</button>
                    </div>
                </div>
            </div>

            <script>
                document.addEventListener('DOMContentLoaded', () => {
                    setTimeout(() => {
                        const banner = document.getElementById('unpaidOrderBanner');
                        if (banner) {
                            banner.classList.remove('translate-y-[-20px]', 'opacity-0');
                            banner.classList.add('translate-y-0', 'opacity-100');
                        }
                    }, 1200);
                });

                function dismissUnpaidBanner() {
                    const banner = document.getElementById('unpaidOrderBanner');
                    if (banner) {
                        banner.classList.remove('translate-y-0', 'opacity-100');
                        banner.classList.add('translate-y-[-20px]', 'opacity-0');
                        setTimeout(() => banner.remove(), 500);
                    }
                }
            </script>
        @endif
    @endauth
</x-app-layout>