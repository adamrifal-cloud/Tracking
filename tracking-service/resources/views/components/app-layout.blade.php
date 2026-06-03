<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>{{ $title ?? 'My Tracking apps' }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- AlpineJS for smooth transitions -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f3f4f6; /* Gray background for laptop view */
        }
        /* Mobile container style for laptop screens */
        .mobile-container {
            max-w-md;
            margin-left: auto;
            margin-right: auto;
            min-height: 100vh;
            background-color: #FFC107; /* Primary Yellow */
            position: relative;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }
        /* Style for the logo container */
        .logo-s {
            width: 120px;
            height: 120px;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 4px;
        }
        ::-webkit-scrollbar-thumb {
            background: rgba(0,0,0,0.1);
            border-radius: 4px;
        }
        /* Premium Loader & Animation like Gojek */
        #global-loader {
            position: fixed;
            inset: 0;
            background-color: #FFC107;
            z-index: 9999;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: opacity 0.6s cubic-bezier(0.4, 0, 0.2, 1), visibility 0.6s;
        }
        .loader-ring {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            border: 6px solid rgba(255, 255, 255, 0.3);
            border-top-color: #ffffff;
            animation: spin 1s ease-in-out infinite;
        }
        .loader-center {
            position: absolute;
            width: 24px;
            height: 24px;
            background-color: #ffffff;
            border-radius: 50%;
            animation: pulse-dot 1.5s ease-in-out infinite;
        }
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        @keyframes pulse-dot {
            0%, 100% { transform: scale(0.8); opacity: 0.8; }
            50% { transform: scale(1.2); opacity: 1; }
        }
        .fade-out {
            opacity: 0 !important;
            visibility: hidden !important;
        }
        /* Premium Logo Entrance */
        .logo-s {
            animation: popIn 0.8s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards;
            opacity: 0;
            transform: scale(0.5);
        }
        .loading-logo {
            animation: popIn 0.8s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards, pulse-glow 2s infinite ease-in-out 0.8s !important;
        }
        .logo-s .pin-icon {
            z-index: 20;
            position: relative;
            filter: drop-shadow(0 4px 6px rgba(0,0,0,0.15));
            animation: bounce-in-top 1s cubic-bezier(0.175, 0.885, 0.32, 1.275) 0.5s both;
        }
        @keyframes popIn {
            to { opacity: 1; transform: scale(1); }
        }
        @keyframes bounce-in-top {
            0% { transform: translateY(-50px); opacity: 0; }
            100% { transform: translateY(0); opacity: 1; }
        }
        @keyframes pulse-glow {
            0%, 100% { transform: scale(1); box-shadow: 0 0 0 0 rgba(255, 255, 255, 0.7); }
            50% { transform: scale(1.05); box-shadow: 0 0 0 20px rgba(255, 255, 255, 0); }
        }
    </style>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased text-gray-800 bg-gray-50">
    <!-- Premium Preloader -->
    <div id="global-loader">
        <div class="logo-s loading-logo">
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
    </div>

    <div id="app-container" class="w-full min-h-screen flex flex-col relative overflow-x-hidden opacity-0 transition-opacity duration-700">
        {{ $slot }}
    </div>

    <script>
        // Premium Splash Screen Logic
        window.addEventListener('load', function() {
            const loader = document.getElementById('global-loader');
            const app = document.getElementById('app-container');
            
            // Artificial delay to show off the premium animation
            setTimeout(() => {
                loader.classList.add('fade-out');
                app.classList.remove('opacity-0');
                app.classList.add('opacity-100');
            }, 800);
        });
    </script>
</body>
</html>