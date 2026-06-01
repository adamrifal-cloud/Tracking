<x-app-layout>
    <div class="min-h-screen flex flex-col md:flex-row bg-[#FFC107] md:bg-white w-full">
        
        <!-- Left Panel (Top on Mobile, Left on Desktop) -->
        <div class="md:w-1/2 bg-[#FFC107] flex flex-col items-center justify-center pt-16 pb-20 md:py-12 px-8 relative overflow-hidden">
            
            <!-- Back Button -->
            <a href="{{ route('choice') }}" class="absolute top-6 left-6 md:top-8 md:left-8 text-white hover:text-gray-100 transition-colors z-20">
                <svg class="w-8 h-8 md:w-10 md:h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>

            <!-- Decorative SVG for Desktop -->
            <svg class="absolute top-0 right-0 w-3/4 h-auto opacity-10 hidden md:block" viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
                <path fill="#ffffff" d="M45.7,-76.1C58.9,-69.3,69.1,-55.3,77.3,-40.5C85.5,-25.7,91.7,-10.1,90.2,4.8C88.6,19.8,79.2,34.2,68.7,46.2C58.1,58.2,46.5,67.8,32.7,75.2C18.9,82.5,2.9,87.6,-12.3,86.2C-27.5,84.7,-41.8,76.6,-53.4,66.3C-64.9,56,-73.6,43.5,-79.8,29.3C-86,15.1,-89.7,-0.7,-86.3,-15.1C-82.9,-29.4,-72.4,-42.2,-59.8,-50.2C-47.1,-58.1,-32.4,-61.2,-18.8,-66.3C-5.1,-71.3,7.5,-78.4,22,-80.7C36.4,-83,50.7,-80.5,45.7,-76.1Z" transform="translate(100 100)" />
            </svg>

            <!-- The Logo -->
            <div class="logo-s mb-6 relative z-10 scale-90 md:scale-110">
                <div class="absolute -top-3 -right-3 w-8 h-8 md:w-10 md:h-10 text-[#E63946] flex items-center justify-center animate-bounce">
                    <svg class="w-full h-full drop-shadow-md" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5a2.5 2.5 0 010-5 2.5 2.5 0 010 5z"/></svg>
                </div>
            </div>

            <div class="hidden md:block text-center text-white mt-8 z-10">
                <h1 class="text-4xl font-bold mb-4">Selamat Datang Kembali!</h1>
                <p class="text-lg text-yellow-100 max-w-sm mx-auto">Masuk ke akun Customer Anda untuk melacak paket dan mengelola pengiriman dengan mudah.</p>
            </div>
        </div>

        <!-- Right Panel (Bottom on Mobile, Right on Desktop) -->
        <div class="md:w-1/2 bg-white flex flex-col justify-center px-8 py-10 md:px-16 lg:px-24 rounded-t-[3rem] md:rounded-none -mt-10 md:mt-0 relative z-20 flex-1 shadow-[0_-10px_40px_-15px_rgba(0,0,0,0.1)] md:shadow-none">
            
            <div class="max-w-md w-full mx-auto">
                <div class="text-center md:text-left mb-10">
                    <h2 class="text-3xl font-extrabold text-gray-900 mb-2">Customer Login</h2>
                    <p class="text-gray-500">Silakan masukkan kredensial Anda</p>
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

                <form action="{{ route('customer.login.post') }}" method="POST" class="space-y-6">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2 ml-4">Email Address</label>
                        <input type="email" name="email" required placeholder="Masukkan email Anda" value="{{ old('email') }}"
                            class="w-full px-6 py-4 rounded-full bg-gray-50 text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#FFC107] focus:bg-white border border-gray-200 transition-all font-medium">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2 ml-4">Password</label>
                        <input type="password" name="password" required placeholder="••••••••"
                            class="w-full px-6 py-4 rounded-full bg-gray-50 text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#FFC107] focus:bg-white border border-gray-200 transition-all font-medium">
                    </div>

                    <div class="pt-2">
                        <button type="submit" class="w-full py-4 rounded-full bg-[#FFB703] border-2 border-transparent text-white text-lg font-bold shadow-lg hover:shadow-xl hover:bg-[#ffb000] transition-all transform hover:-translate-y-0.5">
                            Masuk
                        </button>
                    </div>
                </form>

                <div class="mt-8 text-center">
                    <p class="text-gray-600">
                        Belum punya akun? 
                        <a href="{{ route('customer.register') }}" class="font-bold text-[#FFB703] hover:text-[#e5a403] transition-colors">Daftar sekarang</a>
                    </p>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
