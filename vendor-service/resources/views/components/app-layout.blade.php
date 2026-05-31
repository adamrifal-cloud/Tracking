<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Sistem Logistik' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans min-h-screen flex flex-col">

    <header class="bg-slate-800 text-white shadow-md">
        <div class="container mx-auto px-6 py-4 flex justify-between items-center">
            <h1 class="text-xl font-bold tracking-wider flex items-center gap-2">
                📦 <span class="text-blue-400">Logistik</span>App
            </h1>
            <nav class="text-sm font-medium text-gray-300 space-x-4">
                <span class="bg-slate-700 px-3 py-1 rounded-full text-xs text-green-400 border border-green-500/30">
                    Microservice Mode Active
                </span>
            </nav>
        </div>
    </header>

    <main class="flex-grow container mx-auto px-4 py-8 flex items-center justify-center">
        {{ $slot }}
    </main>

    <footer class="bg-white border-t border-gray-200 py-4 text-center text-xs text-gray-500">
        &copy; {{ date('Y') }} Logistik-App Ecosystem • Powered by RabbitMQ & Docker
    </footer>

</body>
</html>