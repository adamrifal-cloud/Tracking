<x-app-layout>
    <div class="flex-1 flex flex-col min-h-screen bg-gray-50 relative overflow-x-hidden">
        
        <!-- Top Navigation Bar (Emerald Theme for Driver) -->
        <div class="w-full bg-[#10b981] px-6 md:px-12 py-4 shadow-md z-20 flex justify-between items-center relative">
            <!-- Decorative wave -->
            <svg class="absolute bottom-0 left-0 right-0 w-full h-12 text-gray-50 translate-y-full" fill="currentColor" viewBox="0 0 1440 120" preserveAspectRatio="none">
                <path d="M0,0 C320,120 420,120 720,60 C1020,0 1120,0 1440,60 L1440,0 L0,0 Z"></path>
            </svg>

            <div class="flex items-center gap-3">
                <div class="bg-white text-[#10b981] w-10 h-10 rounded-full flex items-center justify-center shadow-sm">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                </div>
                <span class="font-extrabold text-white text-xl tracking-wide">Driver Portal</span>
            </div>

            <div class="flex items-center gap-4">
                <div class="hidden md:flex items-center gap-3 bg-white/20 rounded-full py-1.5 px-4 border border-white/30 text-white">
                    <span class="font-bold text-sm">{{ Auth::user()->name ?? 'Driver' }}</span>
                </div>
                
                <form method="POST" action="{{ route('logout') }}" class="m-0 p-0">
                    @csrf
                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white p-2 rounded-full shadow-lg transition-transform transform hover:scale-105" title="Logout">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    </button>
                </form>
            </div>
        </div>

        <!-- Main Content Area -->
        <div class="flex-1 p-6 md:p-12 z-10 w-full max-w-7xl mx-auto mt-6 md:mt-12">
            
            <!-- Dashboard Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 md:gap-8">
                
                <!-- Left Column (Profile & Stats) -->
                <div class="md:col-span-1 space-y-6">
                    <div class="bg-white rounded-3xl p-6 shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-gray-100 flex flex-col items-center text-center">
                        <div class="w-24 h-24 bg-emerald-100 rounded-full flex items-center justify-center text-emerald-500 mb-4 shadow-inner border-4 border-white">
                            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        </div>
                        <h2 class="text-xl font-bold text-gray-800">{{ Auth::user()->name ?? 'Driver' }}</h2>
                        <p class="text-emerald-600 font-medium text-sm mb-6">Kurir Aktif</p>

                        <div class="w-full grid grid-cols-2 gap-4 border-t border-gray-100 pt-6">
                            <div>
                                <p class="text-gray-400 text-xs uppercase tracking-wider mb-1">Total Kiriman</p>
                                <p class="text-2xl font-black text-gray-800">0</p>
                            </div>
                            <div>
                                <p class="text-gray-400 text-xs uppercase tracking-wider mb-1">Selesai</p>
                                <p class="text-2xl font-black text-emerald-500">0</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column (Tasks List) -->
                <div class="md:col-span-2">
                    <div class="bg-white rounded-3xl p-6 shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-gray-100 h-full min-h-[400px] flex flex-col">
                        <div class="flex justify-between items-center mb-6">
                            <h2 class="text-2xl font-bold text-gray-800">Tugas Hari Ini</h2>
                            <span class="bg-emerald-100 text-emerald-700 px-3 py-1 rounded-full text-xs font-bold">0 Tugas</span>
                        </div>
                        
                        <!-- Empty State -->
                        <div class="flex-1 bg-gray-50 rounded-2xl border-2 border-dashed border-gray-200 flex flex-col items-center justify-center p-8 text-center">
                            <div class="w-20 h-20 bg-white rounded-full flex items-center justify-center shadow-sm mb-4">
                                <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                            </div>
                            <h3 class="text-lg font-bold text-gray-700 mb-1">Belum Ada Tugas</h3>
                            <p class="text-gray-500 text-sm max-w-xs mx-auto">Anda belum dialokasikan untuk pengiriman apa pun. Silakan beristirahat atau tunggu instruksi Vendor.</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>
</x-app-layout>
