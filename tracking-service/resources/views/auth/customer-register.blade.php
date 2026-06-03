<x-app-layout>
    <div class="min-h-screen flex flex-col md:flex-row-reverse bg-[#FFC107] md:bg-white w-full">
        
        <!-- Left Panel (Top on Mobile, Right on Desktop - reverse flow) -->
        <div class="md:w-1/2 bg-[#FFC107] flex flex-col items-center justify-center pt-16 pb-20 md:py-12 px-8 relative overflow-hidden">
            
            <!-- Back Button -->
            <a href="{{ route('customer.login') }}" class="absolute top-6 left-6 md:top-8 md:left-8 text-white hover:text-gray-100 transition-colors z-20">
                <svg class="w-8 h-8 md:w-10 md:h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>

            <!-- Decorative SVG for Desktop -->
            <svg class="absolute top-0 left-0 w-3/4 h-auto opacity-10 hidden md:block" viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg" transform="scale(-1, 1)">
                <path fill="#ffffff" d="M45.7,-76.1C58.9,-69.3,69.1,-55.3,77.3,-40.5C85.5,-25.7,91.7,-10.1,90.2,4.8C88.6,19.8,79.2,34.2,68.7,46.2C58.1,58.2,46.5,67.8,32.7,75.2C18.9,82.5,2.9,87.6,-12.3,86.2C-27.5,84.7,-41.8,76.6,-53.4,66.3C-64.9,56,-73.6,43.5,-79.8,29.3C-86,15.1,-89.7,-0.7,-86.3,-15.1C-82.9,-29.4,-72.4,-42.2,-59.8,-50.2C-47.1,-58.1,-32.4,-61.2,-18.8,-66.3C-5.1,-71.3,7.5,-78.4,22,-80.7C36.4,-83,50.7,-80.5,45.7,-76.1Z" transform="translate(100 100)" />
            </svg>

            <!-- The Logo -->
            <div class="logo-s mb-6 relative z-10 scale-90 md:scale-110">
                <svg class="w-[120px] h-[120px] pin-icon drop-shadow-md" viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <!-- Semicircles Group -->
                    <g transform="translate(50, 50) rotate(-45)">
                        <!-- Top-Left Semicircle -->
                        <path d="M -40 0 A 40 40 0 0 1 40 0 Z" fill="#FFFFFF" transform="translate(0, -5)" />
                        <!-- Bottom-Right Semicircle -->
                        <path d="M -40 0 A 40 40 0 0 0 40 0 Z" fill="#FFFFFF" transform="translate(0, 5)" />
                    </g>
                    <!-- Map Pin Group -->
                    <g transform="translate(50, 50)">
                        <circle cx="0" cy="0" r="16" fill="#FFC107" />
                        <polygon points="-13.8,8 13.8,8 0,32" fill="#FFC107" />
                        <circle cx="0" cy="0" r="6.5" fill="#FF0000" />
                    </g>
                </svg>
            </div>

            <div class="hidden md:block text-center text-white mt-8 z-10">
                <h1 class="text-4xl font-bold mb-4">Bergabung Bersama Kami!</h1>
                <p class="text-lg text-yellow-100 max-w-sm mx-auto">Buat akun Anda sekarang untuk menikmati pengalaman pelacakan logistik yang modern, cepat, dan transparan.</p>
            </div>
        </div>

        <!-- Right Panel (Bottom on Mobile, Left on Desktop) -->
        <div class="md:w-1/2 bg-white flex flex-col justify-center px-8 py-10 md:px-16 lg:px-24 rounded-t-[3rem] md:rounded-none -mt-10 md:mt-0 relative z-20 flex-1 shadow-[0_-10px_40px_-15px_rgba(0,0,0,0.1)] md:shadow-none">
            
            <div class="max-w-md w-full mx-auto">
                <div class="text-center md:text-left mb-10">
                    <h2 class="text-3xl font-extrabold text-gray-900 mb-2">Customer Register</h2>
                    <p class="text-gray-500">Lengkapi data diri Anda di bawah ini</p>
                </div>

                @if ($errors->any())
                    <div class="bg-red-50 text-red-600 p-4 rounded-xl mb-6 text-sm border border-red-100">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('customer.register.post') }}" method="POST" class="space-y-6">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2 ml-4">Nama Lengkap</label>
                        <input type="text" name="name" required placeholder="Cth. John Doe" value="{{ old('name') }}"
                            class="w-full px-6 py-4 rounded-full bg-gray-50 text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#FFC107] focus:bg-white border border-gray-200 transition-all font-medium">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2 ml-4">Email Address</label>
                        <input type="email" name="email" required placeholder="Cth. john@example.com" value="{{ old('email') }}"
                            class="w-full px-6 py-4 rounded-full bg-gray-50 text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#FFC107] focus:bg-white border border-gray-200 transition-all font-medium">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2 ml-4">Nomor Telepon</label>
                        <input type="text" name="phone" required placeholder="Cth. 08123456789" value="{{ old('phone') }}"
                            class="w-full px-6 py-4 rounded-full bg-gray-50 text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#FFC107] focus:bg-white border border-gray-200 transition-all font-medium">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2 ml-4">Password</label>
                        <input type="password" name="password" required placeholder="••••••••"
                            class="w-full px-6 py-4 rounded-full bg-gray-50 text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#FFC107] focus:bg-white border border-gray-200 transition-all font-medium">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2 ml-4">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" required placeholder="••••••••"
                            class="w-full px-6 py-4 rounded-full bg-gray-50 text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#FFC107] focus:bg-white border border-gray-200 transition-all font-medium">
                    </div>

                    <div class="pt-2">
                        <button type="submit" class="w-full py-4 rounded-full bg-[#FFB703] border-2 border-transparent text-white text-lg font-bold shadow-lg hover:shadow-xl hover:bg-[#ffb000] transition-all transform hover:-translate-y-0.5">
                            Daftar Sekarang
                        </button>
                    </div>
                </form>

                <div class="mt-8 text-center pb-8 md:pb-0">
                    <p class="text-gray-600">
                        Sudah punya akun? 
                        <a href="{{ route('customer.login') }}" class="font-bold text-[#FFB703] hover:text-[#e5a403] transition-colors">Masuk di sini</a>
                    </p>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
