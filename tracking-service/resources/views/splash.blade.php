<x-app-layout>
    <div class="flex-1 flex flex-col items-center justify-center min-h-screen relative bg-[#FFC107]">
        <!-- The Logo -->
        <div class="logo-s mb-6 relative">
            <!-- Inner Red Pin (Simplified using SVG) -->
            <svg class="w-12 h-12 text-[#E63946] absolute z-10 drop-shadow-md" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5a2.5 2.5 0 010-5 2.5 2.5 0 010 5z"/>
            </svg>
        </div>
        
        <!-- App Name -->
        <h1 class="text-white text-2xl font-bold tracking-wide mt-4 drop-shadow-sm">
            My Tracking apps
        </h1>

        <!-- Loading spinner -->
        <div class="absolute bottom-16">
            <svg class="animate-spin h-6 w-6 text-white opacity-70" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
        </div>
    </div>

    <script>
        setTimeout(() => {
            @auth
                @if(Auth::user()->role === 'CUSTOMER')
                    window.location.href = "{{ route('customer.dashboard') }}";
                @elseif(Auth::user()->role === 'DRIVER')
                    window.location.href = "{{ route('driver.dashboard') }}";
                @else
                    window.location.href = "/choice";
                @endif
            @else
                window.location.href = "/choice";
            @endauth
        }, 2500);
    </script>
</x-app-layout>
