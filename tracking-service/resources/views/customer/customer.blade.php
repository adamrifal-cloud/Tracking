<x-app-layout>
    <div class="flex-1 flex flex-col min-h-screen bg-[#FFC107] relative overflow-hidden">
        
        <!-- Decorative Background for Desktop -->
        <svg class="absolute top-0 right-0 w-1/2 h-auto opacity-10 pointer-events-none hidden md:block" viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
            <path fill="#ffffff" d="M45.7,-76.1C58.9,-69.3,69.1,-55.3,77.3,-40.5C85.5,-25.7,91.7,-10.1,90.2,4.8C88.6,19.8,79.2,34.2,68.7,46.2C58.1,58.2,46.5,67.8,32.7,75.2C18.9,82.5,2.9,87.6,-12.3,86.2C-27.5,84.7,-41.8,76.6,-53.4,66.3C-64.9,56,-73.6,43.5,-79.8,29.3C-86,15.1,-89.7,-0.7,-86.3,-15.1C-82.9,-29.4,-72.4,-42.2,-59.8,-50.2C-47.1,-58.1,-32.4,-61.2,-18.8,-66.3C-5.1,-71.3,7.5,-78.4,22,-80.7C36.4,-83,50.7,-80.5,45.7,-76.1Z" transform="translate(100 100)" />
        </svg>

        <!-- Top Navigation Bar -->
        <div class="w-full bg-white/10 backdrop-blur-md border-b border-white/20 px-6 md:px-12 py-4 shadow-sm z-20 flex justify-between items-center">
            
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 text-[#E63946]">
                    <svg fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5a2.5 2.5 0 010-5 2.5 2.5 0 010 5z"/></svg>
                </div>
                <span class="font-extrabold text-white text-xl tracking-wide hidden md:block">Logistik-App</span>
            </div>

           
        </div>

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col md:flex-row items-center justify-center p-6 md:p-12 z-10 w-full max-w-7xl mx-auto gap-8 md:gap-16">
            
            <!-- Left Side / Top Text -->
            <div class="w-full md:w-1/2 text-center md:text-left flex flex-col justify-center">
                <h1 class="text-white text-4xl md:text-6xl font-black mb-4 md:mb-6 leading-tight drop-shadow-md">
                    Welcome <br class="hidden md:block" />to Customer Portal
                </h1>
                <p class="text-yellow-50 text-lg md:text-xl mb-8 max-w-md mx-auto md:mx-0">
                    Lacak setiap kiriman Anda dengan mudah, cepat, dan transparan dalam satu tempat.
                </p>
                
                <!-- Call to Action Button for Desktop -->
                <div class="hidden md:block">
                    <a href="/track-order" class="group relative inline-flex items-center justify-center px-8 py-4 font-bold text-gray-900 bg-white rounded-full overflow-hidden shadow-xl hover:shadow-2xl transition-all hover:scale-105">
                        <span class="relative z-10 mr-3 text-lg">Mulailah melacak</span>
                        <div class="bg-[#E63946] w-10 h-10 rounded-full flex items-center justify-center shadow-lg relative z-10">
                            <svg class="w-5 h-5 text-white ml-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                    </a>
                </div>
            </div>

            <!-- Right Side / Illustration -->
            <div class="w-full md:w-1/2 flex flex-col relative max-w-md mx-auto">
                <!-- Map Illustration Box -->
                <div class="bg-white rounded-[3rem] relative overflow-hidden shadow-2xl flex items-center justify-center min-h-[300px] md:min-h-[400px] border-8 border-white/50 w-full transform md:rotate-3 transition-transform hover:rotate-0 duration-500">
                    
                    <!-- Decorative SVG background for map -->
                    <svg class="absolute inset-0 w-full h-full text-gray-50" fill="currentColor" viewBox="0 0 100 100" preserveAspectRatio="none">
                        <path d="M0,50 Q25,25 50,50 T100,50 L100,100 L0,100 Z" opacity="0.8"/>
                        <path d="M0,70 Q35,40 70,70 T100,60 L100,100 L0,100 Z" opacity="0.4"/>
                        <!-- Roadmap lines -->
                        <path d="M-10,80 Q50,90 110,40" stroke="#f3f4f6" stroke-width="8" fill="none" />
                        <path d="M-10,20 Q50,0 110,60" stroke="#e5e7eb" stroke-width="4" fill="none" stroke-dasharray="8,4" />
                    </svg>
                    
                    <div class="relative z-10 flex flex-col items-center group cursor-pointer">
                        <!-- Location Pin -->
                        <svg class="w-20 h-20 text-[#E63946] mb-3 drop-shadow-xl transform group-hover:-translate-y-4 transition-transform duration-300" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5a2.5 2.5 0 010-5 2.5 2.5 0 010 5z"/>
                        </svg>
                        <!-- Package Box -->
                        <svg class="w-20 h-20 text-yellow-500 drop-shadow-2xl -mt-8 transform group-hover:scale-110 transition-transform duration-300" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M19 8l-7 4-7-4 7-4 7 4z"/>
                            <path d="M19 10l-7 4v9l7-4v-9z"/>
                            <path d="M5 10l7 4v9l-7-4v-9z"/>
                        </svg>
                    </div>
                </div>

                <!-- Call to Action Button for Mobile -->
                <div class="mt-8 md:hidden w-full">
                    <a href="/track-order" class="group relative w-full flex items-center justify-between py-4 pl-8 pr-2 bg-transparent border-t-2 border-white/50 hover:bg-white/10 transition-colors">
                        <span class="text-white text-lg font-bold tracking-wide">Mulailah melacak</span>
                        <div class="bg-[#E63946] w-12 h-12 rounded-full flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6 text-white ml-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                    </a>
                    <div class="w-full h-1 bg-white/50"></div>
                </div>
            </div>

        </div>

    </div>
</x-app-layout>
