@php
    $activeTasks = $activeTasks ?? collect();
    $completedTasks = $completedTasks ?? collect();
    $availableTasks = $availableTasks ?? collect();
@endphp
<x-app-layout>
    <!-- Leaflet CSS & JS for Live Map -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

    <!-- Leaflet Routing Machine CSS & JS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.css" />
    <script src="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.js"></script>

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            -webkit-font-smoothing: antialiased;
        }
        /* Custom scrollbars */
        ::-webkit-scrollbar {
            width: 5px;
            height: 5px;
        }
        ::-webkit-scrollbar-track {
            background: transparent;
        }
        ::-webkit-scrollbar-thumb {
            background: rgba(16, 185, 129, 0.2);
            border-radius: 9999px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: rgba(16, 185, 129, 0.4);
        }
    </style>

    <div class="flex-1 flex flex-col min-h-screen bg-slate-50 relative overflow-x-hidden pb-12">
        
        <!-- Top Navigation Bar (Emerald Gradient Theme) -->
        <div class="w-full bg-gradient-to-r from-emerald-600 to-teal-500 px-4 sm:px-6 md:px-12 py-4 shadow-lg z-20 flex justify-between items-center relative">
            <div class="flex items-center gap-3">
                <div class="bg-white text-emerald-600 w-10 h-10 rounded-2xl flex items-center justify-center shadow-md transform rotate-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 0 1-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h1.125a1.125 1.125 0 0 0 1.125-1.125V9.75M3.82 8.167a3.75 3.75 0 0 1 5.377-2.833l10.932 5.466M3.82 8.167l-.149.277a3.75 3.75 0 0 0 2.215 5.059m-.149-5.336L3.728 8.11a3.75 3.75 0 0 0-.256 3.118m0-3.118a3.733 3.733 0 0 1 1.012-.083M9.75 9.75c0 .414-.168.788-.439 1.061m0 0a1.5 1.5 0 0 1-2.122 0m2.122 0h6.122m-8.244 0a1.5 1.5 0 0 1 0-2.122m0 0a1.5 1.5 0 0 1 2.122 0m-2.122 0h.008m13.492 3a3.375 3.375 0 0 1-3.375-3.375v-.154M12 21a9.003 9.003 0 0 0 8.354-5.646M12 21a9 9 0 0 1-8.354-5.646"/></svg>
                </div>
                <span class="font-black text-white text-xl tracking-tight">Track<span class="text-emerald-100">IT</span> Driver</span>
            </div>

            <div class="flex items-center gap-3">
                <div class="hidden sm:flex flex-col text-right">
                    <span class="font-bold text-white text-sm">{{ Auth::user()->name }}</span>
                    <span class="text-emerald-100 text-xs font-semibold">Kurir Lapangan</span>
                </div>
                
                <form method="POST" action="{{ route('logout') }}" class="m-0 p-0">
                    @csrf
                    <button type="submit" class="bg-white/15 hover:bg-white/25 text-white w-10 h-10 rounded-xl flex items-center justify-center shadow-lg transition duration-200" title="Keluar">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15M12 9l-3 3m0 0 3 3m-3-3h12.75"/></svg>
                    </button>
                </form>
            </div>
        </div>

        <!-- Main Workspace Container -->
        <main class="flex-grow max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 mt-8">
            
            @if(session('success'))
                <div class="mb-6 p-4 rounded-2xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-700 font-bold text-sm flex items-center gap-3 animate-fade-in">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 p-4 rounded-2xl bg-red-500/10 border border-red-500/20 text-red-700 font-bold text-sm flex items-center gap-3 animate-fade-in">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                
                <!-- LEFT COLUMN: Profile & Task lists (4 Cols) -->
                <div class="lg:col-span-4 space-y-8">
                    
                    <!-- Profile & Stats Card -->
                    <div class="bg-white/85 backdrop-blur-md rounded-3xl p-6 shadow-[0_8px_32px_0_rgba(0,0,0,0.04)] border border-slate-200/50 flex flex-col items-center">
                        <div class="w-20 h-20 rounded-2xl bg-emerald-500 flex items-center justify-center text-white text-2xl font-black shadow-lg shadow-emerald-500/25 mb-4">
                            {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                        </div>
                        <h2 class="text-lg font-black text-slate-800">{{ Auth::user()->name }}</h2>
                        <span class="px-3 py-1 rounded-full bg-emerald-100 text-emerald-700 text-xs font-bold mt-1">Kurir Lapangan</span>
                        
                        <!-- Driver Details -->
                        <div class="w-full mt-6 space-y-2 border-t border-slate-100 pt-4 text-sm text-slate-600">
                            <div class="flex justify-between">
                                <span class="text-slate-400">Plat Nomor:</span>
                                <span class="font-extrabold text-slate-800">{{ Auth::user()->vehicle_number ?? 'B 1234 CD' }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-slate-400">Lisensi/SIM:</span>
                                <span class="font-bold text-slate-800">{{ Auth::user()->license_number ?? 'SIM-987654321' }}</span>
                            </div>
                        </div>

                        <!-- Stats Box -->
                        <div class="w-full mt-6 grid grid-cols-2 gap-4 border-t border-slate-100 pt-4 text-center">
                            <div>
                                <span class="text-slate-400 text-xs font-bold block uppercase tracking-wider">Aktif</span>
                                <span class="text-2xl font-black text-slate-800">{{ count($activeTasks) }}</span>
                            </div>
                            <div class="border-l border-slate-100">
                                <span class="text-slate-400 text-xs font-bold block uppercase tracking-wider">Selesai</span>
                                <span class="text-2xl font-black text-emerald-500">{{ count($completedTasks) }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Available Tasks List -->
                    <div class="bg-white/85 backdrop-blur-md rounded-3xl p-6 shadow-[0_8px_32px_0_rgba(0,0,0,0.04)] border border-slate-200/50">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-base font-black text-slate-800">Tugas Tersedia</h3>
                            <span class="px-2.5 py-0.5 rounded-full bg-emerald-100 text-emerald-700 text-xs font-bold">{{ count($availableTasks) }}</span>
                        </div>

                        <div class="space-y-4 max-h-[300px] overflow-y-auto pr-1">
                            @forelse($availableTasks as $task)
                                <div class="p-4 bg-slate-50 hover:bg-slate-100/70 rounded-2xl border border-slate-100 transition duration-200 flex flex-col justify-between gap-3">
                                    <div>
                                        <div class="flex justify-between items-center mb-1">
                                            <span class="text-xs font-black text-slate-800">{{ $task->order_id }}</span>
                                            <span class="px-2 py-0.5 rounded-md bg-amber-100 text-amber-700 text-[10px] font-black uppercase">Siap Klaim</span>
                                        </div>
                                        <p class="text-xs text-slate-500 font-medium">Tujuan: {{ explode(',', $task->receiver_address)[0] }}</p>
                                        <p class="text-[11px] text-slate-400 mt-1">Paket: {{ Str::limit($task->package_description, 30) }}</p>
                                    </div>
                                    <form method="POST" action="{{ route('driver.task.claim', $task->order_id) }}" class="m-0">
                                        @csrf
                                        <button type="submit" class="w-full py-2 bg-emerald-500 hover:bg-emerald-600 text-white rounded-xl text-xs font-black shadow-md shadow-emerald-500/10 transition duration-250 flex items-center justify-center gap-1.5 cursor-pointer">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                                            Ambil Tugas
                                        </button>
                                    </form>
                                </div>
                            @empty
                                <div class="text-center py-6 text-slate-400 text-xs">
                                    <svg class="w-8 h-8 mx-auto text-slate-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                    Tidak ada tugas tersedia saat ini.
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- Completed Tasks History List -->
                    <div class="bg-white/85 backdrop-blur-md rounded-3xl p-6 shadow-[0_8px_32px_0_rgba(0,0,0,0.04)] border border-slate-200/50">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-base font-black text-slate-800">Riwayat Selesai</h3>
                            <span class="px-2.5 py-0.5 rounded-full bg-slate-100 text-slate-600 text-xs font-bold">{{ count($completedTasks) }}</span>
                        </div>

                        <div class="space-y-3 max-h-[220px] overflow-y-auto pr-1">
                            @forelse($completedTasks as $task)
                                <div class="p-3 bg-slate-50 rounded-xl border border-slate-100 flex items-center justify-between text-xs">
                                    <div>
                                        <span class="font-extrabold text-slate-700 block">{{ $task->order_id }}</span>
                                        <span class="text-[10px] text-slate-400">Penerima: {{ $task->receiver_name }}</span>
                                    </div>
                                    <span class="px-2 py-0.5 rounded bg-emerald-100 text-emerald-700 font-extrabold text-[10px]">Selesai</span>
                                </div>
                            @empty
                                <div class="text-center py-4 text-slate-400 text-xs">
                                    Belum ada tugas diselesaikan.
                                </div>
                            @endforelse
                        </div>
                    </div>

                </div>

                <!-- RIGHT COLUMN: Active Task Map & Details (8 Cols) -->
                <div class="lg:col-span-8">
                    @if(count($activeTasks) > 0)
                        @php $activeTask = $activeTasks[0]; @endphp
                        <div class="bg-white/85 backdrop-blur-md rounded-3xl p-6 shadow-[0_8px_32px_0_rgba(0,0,0,0.04)] border border-slate-200/50 space-y-6">
                            
                            <!-- Header Info -->
                            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3 pb-4 border-b border-slate-100">
                                <div>
                                    <span class="text-slate-400 text-xs font-bold uppercase tracking-wider block">Tugas Aktif Berjalan</span>
                                    <div class="flex items-center gap-2 mt-1">
                                        <h2 class="text-xl font-black text-slate-800">{{ $activeTask->order_id }}</h2>
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-700">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-ping"></span>
                                            Sedang Dikirim
                                        </span>
                                    </div>
                                </div>

                                <!-- Status Dropdown Control -->
                                <div class="flex items-center gap-2 shrink-0">
                                    <label for="taskStatus" class="text-xs font-extrabold text-slate-500">Status:</label>
                                    <select id="taskStatus" onchange="updateTaskStatus('{{ $activeTask->order_id }}', this.value)" class="bg-slate-50 border border-slate-200/60 rounded-xl px-3 py-2 text-xs font-bold text-slate-700 focus:outline-none focus:border-emerald-500 transition duration-200 cursor-pointer">
                                        <option value="Driver Terpilih - Bersiap Meluncur" {{ $activeTask->tracking_status == 'Driver Terpilih - Bersiap Meluncur' ? 'selected' : '' }}>Bersiap Meluncur</option>
                                        <option value="Diperjalanan - Transit Hub" {{ $activeTask->tracking_status == 'Diperjalanan - Transit Hub' ? 'selected' : '' }}>Transit Hub / Diperjalanan</option>
                                        <option value="Kurir Menuju Lokasi Penerima" {{ $activeTask->tracking_status == 'Kurir Menuju Lokasi Penerima' ? 'selected' : '' }}>Menuju Lokasi Penerima</option>
                                        <option value="Pesanan Diterima - Selesai" {{ $activeTask->tracking_status == 'Pesanan Diterima - Selesai' ? 'selected' : '' }}>Selesai / Diterima</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Shipment Detailed Specs -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 bg-slate-50/70 p-5 rounded-2xl border border-slate-100 text-xs">
                                <div>
                                    <h4 class="font-black text-slate-700 mb-2 uppercase tracking-wide text-[10px]">Detail Pengiriman</h4>
                                    <div class="space-y-1.5">
                                        <p><span class="text-slate-400 font-medium">Pengirim:</span> <span class="font-bold text-slate-800">{{ $activeTask->sender_name }} ({{ $activeTask->sender_phone }})</span></p>
                                        <p><span class="text-slate-400 font-medium">Lokasi Asal:</span> <span class="font-medium text-slate-700">{{ $activeTask->sender_address }}</span></p>
                                        <p class="pt-1.5 border-t border-slate-200/50 mt-1.5"><span class="text-slate-400 font-medium">Penerima:</span> <span class="font-bold text-slate-800">{{ $activeTask->receiver_name }} ({{ $activeTask->receiver_phone }})</span></p>
                                        <p><span class="text-slate-400 font-medium">Lokasi Tujuan:</span> <span class="font-bold text-slate-700">{{ $activeTask->receiver_address }}</span></p>
                                    </div>
                                </div>
                                <div>
                                    <h4 class="font-black text-slate-700 mb-2 uppercase tracking-wide text-[10px]">Spesifikasi Paket</h4>
                                    <div class="space-y-1.5">
                                        <p><span class="text-slate-400 font-medium">Deskripsi:</span> <span class="font-bold text-slate-800">{{ $activeTask->package_description }}</span></p>
                                        <p><span class="text-slate-400 font-medium">Berat Barang:</span> <span class="font-bold text-slate-800">{{ $activeTask->package_weight }} kg</span></p>
                                        <p><span class="text-slate-400 font-medium">Ongkos Kirim:</span> <span class="font-bold text-emerald-600">Rp {{ number_format($activeTask->price, 0, ',', '.') }}</span></p>
                                    </div>
                                </div>
                            </div>

                            <!-- Live Tracking Map Section -->
                            <div class="space-y-3">
                                <div class="flex justify-between items-center">
                                    <div class="flex items-center gap-1.5">
                                        <h3 class="text-sm font-black text-slate-800">Peta Rute & GPS Kurir</h3>
                                        <span class="w-2.5 h-2.5 bg-emerald-500 rounded-full animate-ping"></span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <!-- Journey Simulation Button -->
                                        <button id="simulateBtn" onclick="toggleRouteSimulation()" class="px-3.5 py-1.5 bg-gradient-to-tr from-emerald-500 to-teal-500 text-white rounded-xl text-xs font-black shadow-md shadow-emerald-500/20 hover:scale-103 active:scale-97 cursor-pointer transition flex items-center gap-1.5">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5.25 5.653c0-.856.917-1.398 1.667-.986l11.54 6.348a1.125 1.125 0 010 1.971l-11.54 6.347a1.125 1.125 0 01-1.667-.985V5.653z"/></svg>
                                            Simulasikan Perjalanan
                                        </button>
                                    </div>
                                </div>

                                <!-- Leaflet Container -->
                                <div id="map" class="w-full h-[350px] rounded-2xl border border-slate-200 overflow-hidden z-10 shadow-inner bg-slate-900"></div>
                                
                                <div class="flex items-start gap-2 bg-slate-50 p-3.5 rounded-xl border border-slate-100 text-[11px] text-slate-500 font-medium">
                                    <svg class="w-4.5 h-4.5 text-slate-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 111.063.852l-.708 2.836a.75.75 0 001.063.852l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z"/></svg>
                                    <div>
                                        <span class="font-extrabold text-slate-700 block mb-0.5">Petunjuk Update Posisi GPS Kurir:</span>
                                        Klik di area mana saja pada peta untuk memperbarui koordinat posisi paket secara manual, atau klik tombol <strong>"Simulasikan Perjalanan"</strong> untuk menjalankan visualisasi pengantaran secara otomatis mengikuti jalur jalanan OSRM langsung.
                                    </div>
                                </div>
                            </div>

                        </div>
                    @else
                        <!-- Active Task Empty State -->
                        <div class="bg-white/85 backdrop-blur-md rounded-3xl p-8 border border-slate-200/50 shadow-[0_8px_32px_0_rgba(0,0,0,0.04)] h-full min-h-[480px] flex flex-col justify-center items-center text-center">
                            <div class="w-24 h-24 rounded-3xl bg-gradient-to-tr from-emerald-500 to-teal-500 text-white flex items-center justify-center shadow-lg shadow-emerald-500/25 mb-6 animate-pulse">
                                <svg class="w-12 h-12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                            </div>
                            <h3 class="text-xl font-black text-slate-800 mb-2">Belum Ada Tugas Aktif</h3>
                            <p class="text-slate-500 text-sm max-w-sm leading-relaxed mb-6">Anda saat ini sedang beristirahat atau tidak memiliki paket untuk diantar. Silakan klaim salah satu kiriman aktif yang tersedia pada panel <strong>Tugas Tersedia</strong> sebelah kiri.</p>
                        </div>
                    @endif
                </div>

            </div>

        </main>

        <!-- Dynamic Toast Container -->
        <div id="toastContainer" class="fixed top-4 right-4 z-[9999] flex flex-col gap-3 pointer-events-none w-full max-w-sm"></div>

    </div>

    <!-- Map and updates logic -->
    @if(count($activeTasks) > 0)
        @php $activeTask = $activeTasks[0]; @endphp
        <script>
            // Active task details for map logic
            const orderId = '{{ $activeTask->order_id }}';
            const senderAddress = '{{ $activeTask->sender_address }}';
            const receiverAddress = '{{ $activeTask->receiver_address }}';

            // Geocoding Coordinates fallbacks for testing (Jakarta coordinates variations)
            // Ideally should fetch from a geocoding service, or use database values if available
            // Let's compute stable mock coordinates based on string hashes to keep locations matching
            function getStringHashCoords(str, baseLat, baseLng) {
                let hash = 0;
                for (let i = 0; i < str.length; i++) {
                    hash = str.charCodeAt(i) + ((hash << 5) - hash);
                }
                const latOffset = (hash % 100) / 1000;
                const lngOffset = ((hash >> 8) % 100) / 1000;
                return [baseLat + latOffset, baseLng + lngOffset];
            }

            const startCoords = getStringHashCoords(senderAddress, -6.18, 106.80);
            const endCoords = getStringHashCoords(receiverAddress, -6.23, 106.85);

            // Default driver coordinates starts at current stored coordinate, or defaults to start location (Titik A)
            let currentLat = {{ $activeTask->latitude ?? 'null' }} || startCoords[0];
            let currentLng = {{ $activeTask->longitude ?? 'null' }} || startCoords[1];

            let map = null;
            let routingControl = null;
            let packageMarker = null;

            // Route simulator status variables
            let routeCoordinates = [];
            let routeIndex = 0;
            let simulationInterval = null;

            document.addEventListener('DOMContentLoaded', () => {
                // Initialize map centered at current package position
                map = L.map('map', { zoomControl: false }).setView([currentLat, currentLng], 12);
                L.control.zoom({ position: 'topleft' }).addTo(map);

                // Load CartoDB Dark Matter tile layer for premium neon styling
                L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', {
                    attribution: '&copy; OpenStreetMap &copy; CARTO'
                }).addTo(map);

                // Custom Icons
                const startIcon = L.divIcon({
                    className: 'custom-start-icon',
                    html: `<div class="relative w-6 h-6 bg-indigo-500 rounded-full border-2 border-white flex items-center justify-center shadow-lg">
                            <span class="w-1.5 h-1.5 bg-white rounded-full"></span>
                           </div>`,
                    iconSize: [24, 24],
                    iconAnchor: [12, 12]
                });

                const endIcon = L.divIcon({
                    className: 'custom-end-icon',
                    html: `<div class="relative w-6 h-6 bg-red-500 rounded-full border-2 border-white flex items-center justify-center shadow-lg">
                            <span class="w-1.5 h-1.5 bg-white rounded-full"></span>
                           </div>`,
                    iconSize: [24, 24],
                    iconAnchor: [12, 12]
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

                // Place Package/Driver Position Marker
                packageMarker = L.marker([currentLat, currentLng], { icon: packageIcon }).addTo(map);

                // Leaflet Routing Machine for drawing path
                routingControl = L.Routing.control({
                    waypoints: [
                        L.latLng(startCoords[0], startCoords[1]),
                        L.latLng(endCoords[0], endCoords[1])
                    ],
                    routeWhileDragging: false,
                    addWaypoints: false,
                    draggableWaypoints: false,
                    fitSelectedRoutes: true,
                    show: false, // Sembunyikan instruksi teks
                    createMarker: function(i, waypoint, n) {
                        if (i === 0) {
                            return L.marker(waypoint.latLng, { icon: startIcon });
                        } else if (i === n - 1) {
                            return L.marker(waypoint.latLng, { icon: endIcon });
                        }
                        return null;
                    },
                    lineOptions: {
                        styles: [
                            { color: '#10b981', opacity: 0.8, weight: 4 } // Jalur rute hijau
                        ]
                    }
                }).addTo(map);

                // Intercept console warnings about OSRM demo server
                (function() {
                    const originalWarn = console.warn;
                    console.warn = function(...args) {
                        if (args[0] && typeof args[0] === 'string' && args[0].includes("OSRM's demo server")) {
                            return;
                        }
                        originalWarn.apply(console, args);
                    };
                })();

                // Capture route coordinates once loaded
                routingControl.on('routesfound', function(e) {
                    const routes = e.routes;
                    routeCoordinates = routes[0].coordinates;
                    routeIndex = 0;
                });

                // Manual location update by clicking on the map
                map.on('click', function(e) {
                    if (simulationInterval) {
                        showToast('Simulasi sedang berjalan. Berhentikan simulasi terlebih dahulu untuk memindahkan manual.', 'error');
                        return;
                    }
                    const clickedLat = e.latlng.lat;
                    const clickedLng = e.latlng.lng;

                    // Update local coordinates and marker
                    currentLat = clickedLat;
                    currentLng = clickedLng;
                    packageMarker.setLatLng([clickedLat, clickedLng]);

                    // Send update coordinates directly to server
                    saveLocationToServer(clickedLat, clickedLng);
                });
            });

            // Save location to server using Fetch AJAX
            function saveLocationToServer(lat, lng) {
                const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                fetch(`/driver/task/${orderId}/update-location`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token
                    },
                    body: JSON.stringify({
                        latitude: lat,
                        longitude: lng
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'Success') {
                        showToast('Lokasi GPS kurir berhasil diperbarui!', 'success');
                    } else {
                        showToast(data.message || 'Gagal memperbarui lokasi.', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error updating location:', error);
                    showToast('Koneksi terputus, gagal mengirim koordinat GPS.', 'error');
                });
            }

            // Route simulator toggle
            function toggleRouteSimulation() {
                const simulateBtn = document.getElementById('simulateBtn');

                if (simulationInterval) {
                    // STOP simulation
                    clearInterval(simulationInterval);
                    simulationInterval = null;
                    simulateBtn.innerHTML = `<svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5.25 5.653c0-.856.917-1.398 1.667-.986l11.54 6.348a1.125 1.125 0 010 1.971l-11.54 6.347a1.125 1.125 0 01-1.667-.985V5.653z"/></svg>Simulasikan Perjalanan`;
                    simulateBtn.className = 'px-3.5 py-1.5 bg-gradient-to-tr from-emerald-500 to-teal-500 text-white rounded-xl text-xs font-black shadow-md shadow-emerald-500/20 cursor-pointer transition';
                    showToast('Simulasi perjalanan dihentikan.', 'info');
                } else {
                    // START simulation
                    if (routeCoordinates.length === 0) {
                        showToast('Rute pengiriman belum terhitung oleh OSRM. Mohon tunggu sejenak.', 'error');
                        return;
                    }
                    routeIndex = 0;
                    simulateBtn.innerHTML = `<svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 5.25v13.5m-7.5-13.5v13.5"/></svg>Hentikan Simulasi`;
                    simulateBtn.className = 'px-3.5 py-1.5 bg-gradient-to-tr from-red-500 to-rose-600 text-white rounded-xl text-xs font-black shadow-md shadow-red-500/20 cursor-pointer transition';
                    showToast('Memulai simulasi pengiriman paket...', 'success');

                    // Set status to Transit when journey starts
                    document.getElementById('taskStatus').value = 'Diperjalanan - Transit Hub';
                    updateTaskStatus(orderId, 'Diperjalanan - Transit Hub');

                    simulationInterval = setInterval(() => {
                        if (routeIndex >= routeCoordinates.length) {
                            // Arrived at destination
                            clearInterval(simulationInterval);
                            simulationInterval = null;
                            simulateBtn.innerHTML = `<svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5.25 5.653c0-.856.917-1.398 1.667-.986l11.54 6.348a1.125 1.125 0 010 1.971l-11.54 6.347a1.125 1.125 0 01-1.667-.985V5.653z"/></svg>Simulasikan Perjalanan`;
                            simulateBtn.className = 'px-3.5 py-1.5 bg-gradient-to-tr from-emerald-500 to-teal-500 text-white rounded-xl text-xs font-black shadow-md shadow-emerald-500/20 cursor-pointer transition';
                            
                            // Move marker to end coordinates exactly
                            packageMarker.setLatLng([endCoords[0], endCoords[1]]);
                            saveLocationToServer(endCoords[0], endCoords[1]);
                            
                            // Automatically update status to completed / arrived
                            document.getElementById('taskStatus').value = 'Pesanan Diterima - Selesai';
                            updateTaskStatus(orderId, 'Pesanan Diterima - Selesai');
                            return;
                        }

                        const currentSimCoords = routeCoordinates[routeIndex];
                        packageMarker.setLatLng(currentSimCoords);
                        saveLocationToServer(currentSimCoords.lat, currentSimCoords.lng);

                        // Move by steps along route points
                        routeIndex += Math.ceil(routeCoordinates.length / 30); // 30 steps
                        if (routeIndex >= routeCoordinates.length) {
                            routeIndex = routeCoordinates.length; // Will trigger arrival on next cycle
                        }
                    }, 3000);
                }
            }

            // Update Task Status on Server
            function updateTaskStatus(id, newStatus) {
                const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                fetch(`/driver/task/${id}/update-status`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token
                    },
                    body: JSON.stringify({
                        status: newStatus
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'Success') {
                        showToast(`Status berhasil diperbarui: "${newStatus}"`, 'success');
                        
                        // If status is completed/arrived, reload page after a short delay to update tasks lists
                        if (newStatus.includes('Selesai') || newStatus.includes('Diterima')) {
                            setTimeout(() => {
                                window.location.reload();
                            }, 2500);
                        }
                    } else {
                        showToast(data.message || 'Gagal memperbarui status.', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error updating status:', error);
                    showToast('Koneksi terputus, gagal memperbarui status pengiriman.', 'error');
                });
            }

            // Toast feedback mechanism
            function showToast(message, type = 'info') {
                const container = document.getElementById('toastContainer');
                const toast = document.createElement('div');
                
                let iconClass = 'text-blue-500';
                let iconSvg = '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>';
                let bgBorder = 'bg-white border-slate-200';

                if (type === 'success') {
                    iconClass = 'text-emerald-500';
                    iconSvg = '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>';
                    bgBorder = 'bg-emerald-50/95 border-emerald-200/50 text-emerald-800';
                } else if (type === 'error') {
                    iconClass = 'text-red-500';
                    iconSvg = '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>';
                    bgBorder = 'bg-red-50/95 border-red-200/50 text-red-800';
                }

                toast.className = `p-4 rounded-2xl shadow-xl border flex items-center gap-3 backdrop-blur-md transition-all duration-350 transform translate-x-12 opacity-0 pointer-events-auto ${bgBorder}`;
                toast.innerHTML = `<div class="shrink-0 ${iconClass}">${iconSvg}</div><span class="text-xs font-black">${message}</span>`;
                
                container.appendChild(toast);
                
                // Animate entrance
                setTimeout(() => {
                    toast.classList.remove('translate-x-12', 'opacity-0');
                }, 10);

                // Auto remove
                setTimeout(() => {
                    toast.classList.add('translate-x-12', 'opacity-0');
                    setTimeout(() => {
                        toast.remove();
                    }, 400);
                }, 3500);
            }
        </script>
    @endif
</x-app-layout>
