<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vendor Portal - Secure Login</title>
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
                    },
                    animation: {
                        'pulse-glow': 'pulseGlow 3s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                        'float': 'float 6s ease-in-out infinite',
                    },
                    keyframes: {
                        pulseGlow: {
                            '0%, 100%': { opacity: 0.5, transform: 'scale(1)' },
                            '50%': { opacity: 1, transform: 'scale(1.05)', filter: 'drop-shadow(0 0 15px rgba(58,96,200,0.6))' },
                        },
                        float: {
                            '0%, 100%': { transform: 'translateY(0)' },
                            '50%': { transform: 'translateY(-10px)' },
                        }
                    }
                }
            }
        }
    </script>
    <style>
        .glass-panel {
            background: rgba(18, 31, 64, 0.7);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        .input-dark {
            background: rgba(10, 17, 40, 0.6);
            border: 1px solid rgba(79, 124, 255, 0.2);
            color: white;
            transition: all 0.3s ease;
        }
        .input-dark:focus {
            border-color: #4F7CFF;
            box-shadow: 0 0 0 2px rgba(79, 124, 255, 0.2);
            outline: none;
        }
    </style>
</head>
<body class="bg-brand-900 text-white min-h-screen flex items-center justify-center relative overflow-hidden font-sans">
    
    <!-- Background Decor -->
    <div class="absolute top-[-10%] left-[-10%] w-96 h-96 bg-brand-500 rounded-full mix-blend-screen filter blur-[100px] opacity-20 animate-float"></div>
    <div class="absolute bottom-[-10%] right-[-10%] w-[500px] h-[500px] bg-brand-400 rounded-full mix-blend-screen filter blur-[120px] opacity-10 animate-float" style="animation-delay: 2s;"></div>

    <div class="w-full max-w-md p-8 relative z-10">
        <!-- Logo Header -->
        <div class="flex flex-col items-center mb-10 animate-float">
            <!-- Animated SVG Logo -->
            <svg class="w-20 h-20 mb-4 text-brand-400 animate-pulse-glow" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M12 2L2 7L12 12L22 7L12 2Z" fill="currentColor" opacity="0.8"/>
                <path d="M2 17L12 22L22 17" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M2 12L12 17L22 12" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <h1 class="text-3xl font-bold tracking-tight text-white drop-shadow-md">Vendor Portal</h1>
            <p class="text-brand-400 text-sm tracking-widest uppercase mt-2 font-semibold">Management & Allocation</p>
        </div>

        <!-- Login Card -->
        <div class="glass-panel rounded-2xl p-8 shadow-2xl">
            @if ($errors->any())
                <div class="mb-6 p-4 rounded-lg bg-red-900/50 border border-red-500/50 text-red-200 text-sm">
                    <div class="font-semibold mb-1">Access Denied</div>
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('login.post') }}" class="space-y-6">
                @csrf
                <div>
                    <label for="email" class="block text-xs font-bold uppercase tracking-wider text-brand-400 mb-2">Corporate Email</label>
                    <input type="email" name="email" id="email" required placeholder="admin@vendor.com" 
                        class="w-full px-4 py-3 rounded-xl input-dark placeholder-gray-500">
                </div>

                <div>
                    <label for="password" class="block text-xs font-bold uppercase tracking-wider text-brand-400 mb-2">Secure Password</label>
                    <input type="password" name="password" id="password" required placeholder="••••••••" 
                        class="w-full px-4 py-3 rounded-xl input-dark placeholder-gray-500">
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full py-3.5 px-4 rounded-xl bg-gradient-to-r from-brand-500 to-brand-400 hover:from-brand-400 hover:to-brand-500 text-white font-bold text-lg shadow-[0_0_20px_rgba(79,124,255,0.4)] transition-all transform hover:scale-[1.02] active:scale-[0.98]">
                        AUTHORIZE ACCESS
                    </button>
                </div>
            </form>
        </div>

        <div class="text-center mt-8">
            <p class="text-xs text-brand-400/60 uppercase tracking-widest">
                Logistik App &copy; {{ date('Y') }}
            </p>
        </div>
    </div>
</body>
</html>
