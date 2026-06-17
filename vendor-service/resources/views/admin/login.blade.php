<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Logistik App</title>
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
                            500: '#3A60C8', // Accent Blue
                            400: '#4F7CFF', // Light Blue
                        }
                    }
                }
            }
        }
    </script>
</head>
<body class="font-sans antialiased text-gray-900 bg-gray-100">
    <div class="min-h-screen flex flex-col md:flex-row bg-brand-900 md:bg-white w-full">
        
        <!-- Left Panel -->
        <div class="md:w-1/2 bg-brand-900 flex flex-col items-center justify-center pt-16 pb-20 md:py-12 px-8 relative overflow-hidden">
            
            <a href="http://localhost:8001/choice" class="absolute top-6 left-6 md:top-8 md:left-8 text-white hover:text-gray-300 transition-colors z-20">
                <svg class="w-8 h-8 md:w-10 md:h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>

            <div class="hidden md:block text-center text-white mt-8 z-10">
                <h1 class="text-4xl font-bold mb-4">Portal Admin Pusat</h1>
                <p class="text-lg text-brand-400 max-w-sm mx-auto">Sistem Manajemen Logistik dan Validasi Dokumen.</p>
            </div>
        </div>

        <!-- Right Panel -->
        <div class="md:w-1/2 bg-white flex flex-col justify-center px-8 py-10 md:px-16 lg:px-24 rounded-t-[3rem] md:rounded-none -mt-10 md:mt-0 relative z-20 flex-1 shadow-[0_-10px_40px_-15px_rgba(0,0,0,0.1)] md:shadow-none">
            
            <div class="max-w-md w-full mx-auto">
                <div class="text-center md:text-left mb-10">
                    <h2 class="text-3xl font-extrabold text-gray-900 mb-2">Admin Login</h2>
                    <p class="text-gray-500">Silakan masukkan kredensial admin Anda</p>
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

                <form action="{{ route('admin.login.post') }}" method="POST" class="space-y-6">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2 ml-4">Corporate Email</label>
                        <input type="email" name="email" required placeholder="admin@logistik.com" value="{{ old('email') }}"
                            class="w-full px-6 py-4 rounded-full bg-gray-50 text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:bg-white border border-gray-200 transition-all font-medium">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2 ml-4">Secure Password</label>
                        <input type="password" name="password" required placeholder="••••••••"
                            class="w-full px-6 py-4 rounded-full bg-gray-50 text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:bg-white border border-gray-200 transition-all font-medium">
                    </div>

                    <div class="pt-2">
                        <button type="submit" class="w-full py-4 rounded-full bg-brand-900 border-2 border-transparent text-white text-lg font-bold shadow-lg hover:shadow-xl hover:bg-brand-800 transition-all transform hover:-translate-y-0.5">
                            Masuk sebagai Admin
                        </button>
                    </div>
                </form>

                <div class="mt-8 text-center">
                    <p class="text-gray-400 text-sm">
                        &copy; {{ date('Y') }} Logistik App - Admin Portal
                    </p>
                </div>
            </div>

        </div>
    </div>
</body>
</html>
