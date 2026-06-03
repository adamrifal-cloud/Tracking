<x-app-layout>
    <!-- Background Yellow Full Screen -->
    <div class="min-h-screen flex flex-col pt-12 pb-8 px-6 text-white bg-[#FFC107] items-center justify-center relative w-full overflow-hidden">
        
        <!-- Decorative SVG background elements for Desktop -->
        <svg class="absolute top-0 right-0 w-1/3 h-auto opacity-10 hidden md:block" viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
            <path fill="#ffffff" d="M45.7,-76.1C58.9,-69.3,69.1,-55.3,77.3,-40.5C85.5,-25.7,91.7,-10.1,90.2,4.8C88.6,19.8,79.2,34.2,68.7,46.2C58.1,58.2,46.5,67.8,32.7,75.2C18.9,82.5,2.9,87.6,-12.3,86.2C-27.5,84.7,-41.8,76.6,-53.4,66.3C-64.9,56,-73.6,43.5,-79.8,29.3C-86,15.1,-89.7,-0.7,-86.3,-15.1C-82.9,-29.4,-72.4,-42.2,-59.8,-50.2C-47.1,-58.1,-32.4,-61.2,-18.8,-66.3C-5.1,-71.3,7.5,-78.4,22,-80.7C36.4,-83,50.7,-80.5,45.7,-76.1Z" transform="translate(100 100)" />
        </svg>

        <!-- Title -->
        <div class="mb-10 md:mb-16 text-center relative z-10">
            <h2 class="text-3xl md:text-5xl font-bold tracking-tight inline-block relative drop-shadow-md">
                Masuk Sebagai
                <div class="absolute -bottom-3 md:-bottom-4 left-0 right-0 h-1.5 md:h-2 bg-white opacity-80 rounded-full"></div>
            </h2>
            <p class="mt-4 md:mt-6 text-yellow-100 md:text-lg">Silakan pilih peran Anda untuk melanjutkan</p>
        </div>

        <!-- Role Choices (Responsive Grid) -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 md:gap-8 relative z-10 w-full max-w-sm md:max-w-4xl mx-auto pb-10">
            
            <!-- Customer -->
            <a href="{{ route('customer.login') }}" class="group flex flex-row md:flex-col items-center bg-white rounded-full md:rounded-3xl p-2 md:p-8 shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 relative overflow-hidden">
                <div class="absolute inset-0 bg-indigo-50/50 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                <div class="relative w-14 h-14 md:w-24 md:h-24 flex items-center justify-center bg-indigo-100 text-indigo-600 rounded-full shrink-0 z-10 shadow-inner md:mb-6">
                    <svg class="w-6 h-6 md:w-12 md:h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                </div>
                <div class="relative z-10 pl-4 md:pl-0 text-left md:text-center flex-1">
                    <span class="text-lg md:text-2xl font-bold text-gray-800 block">Customer</span>
                    <span class="text-sm text-gray-500 hidden md:block mt-2">Lacak & Kelola Pengiriman Pribadi Anda</span>
                </div>
                <div class="pr-4 md:hidden text-gray-400 group-hover:text-indigo-500 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </div>
            </a>

            <!-- Driver/Kurir -->
            <a href="{{ route('driver.login') }}" class="group flex flex-row md:flex-col items-center bg-white rounded-full md:rounded-3xl p-2 md:p-8 shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 relative overflow-hidden">
                <div class="absolute inset-0 bg-emerald-50/50 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                <div class="relative w-14 h-14 md:w-24 md:h-24 flex items-center justify-center bg-emerald-100 text-emerald-600 rounded-full shrink-0 z-10 shadow-inner md:mb-6">
                    <svg class="w-6 h-6 md:w-12 md:h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                </div>
                <div class="relative z-10 pl-4 md:pl-0 text-left md:text-center flex-1">
                    <span class="text-lg md:text-2xl font-bold text-gray-800 block">Driver / Kurir</span>
                    <span class="text-sm text-gray-500 hidden md:block mt-2">Kelola Tugas Kurir & Rute Pengiriman</span>
                </div>
                <div class="pr-4 md:hidden text-gray-400 group-hover:text-emerald-500 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </div>
            </a>

            <!-- Vendor -->
            <a href="http://localhost:8002/login" class="group flex flex-row md:flex-col items-center bg-white rounded-full md:rounded-3xl p-2 md:p-8 shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 relative overflow-hidden">
                <div class="absolute inset-0 bg-orange-50/50 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                <div class="relative w-14 h-14 md:w-24 md:h-24 flex items-center justify-center bg-orange-100 text-orange-600 rounded-full shrink-0 z-10 shadow-inner md:mb-6">
                    <svg class="w-6 h-6 md:w-12 md:h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                </div>
                <div class="relative z-10 pl-4 md:pl-0 text-left md:text-center flex-1">
                    <span class="text-lg md:text-2xl font-bold text-gray-800 block">Vendor</span>
                    <span class="text-sm text-gray-500 hidden md:block mt-2">Manajemen Alokasi & Mitra Logistik</span>
                </div>
                <div class="pr-4 md:hidden text-gray-400 group-hover:text-orange-500 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </div>
            </a>

            <!-- Admin -->
            <a href="http://localhost:8002/login" class="group flex flex-row md:flex-col items-center bg-white rounded-full md:rounded-3xl p-2 md:p-8 shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 relative overflow-hidden">
                <div class="absolute inset-0 bg-red-50/50 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                <div class="relative w-14 h-14 md:w-24 md:h-24 flex items-center justify-center bg-red-100 text-red-600 rounded-full shrink-0 z-10 shadow-inner md:mb-6">
                    <svg class="w-6 h-6 md:w-12 md:h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                </div>
                <div class="relative z-10 pl-4 md:pl-0 text-left md:text-center flex-1">
                    <span class="text-lg md:text-2xl font-bold text-gray-800 block">Admin</span>
                    <span class="text-sm text-gray-500 hidden md:block mt-2">Pusat Manajemen Sistem Seluruh Platform</span>
                </div>
                <div class="pr-4 md:hidden text-gray-400 group-hover:text-red-500 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </div>
            </a>
            
        </div>
        
    </div>
</x-app-layout>
