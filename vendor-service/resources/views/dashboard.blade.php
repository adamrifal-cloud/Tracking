<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vendor Dashboard</title>
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
                            600: '#263F7A', 
                            500: '#3A60C8', // Accent Blue
                            400: '#4F7CFF', // Light Blue
                        }
                    }
                }
            }
        }
    </script>
    <style>
        .glass-header {
            background: rgba(18, 31, 64, 0.85);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }
        .glass-card {
            background: rgba(18, 31, 64, 0.6);
            backdrop-filter: blur(8px);
            border: 1px solid rgba(79, 124, 255, 0.15);
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .glass-card:hover {
            border-color: rgba(79, 124, 255, 0.4);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.5), 0 0 15px rgba(79, 124, 255, 0.2);
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
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }
        ::-webkit-scrollbar-track {
            background: #0A1128;
        }
        ::-webkit-scrollbar-thumb {
            background: #1A2D5C;
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #263F7A;
        }
    </style>
</head>
<body class="bg-brand-900 text-gray-200 min-h-screen font-sans selection:bg-brand-500 selection:text-white">

    <!-- Top Navigation -->
    <header class="glass-header sticky top-0 z-50 px-6 py-4 flex justify-between items-center">
        <div class="flex items-center space-x-3">
            <svg class="w-8 h-8 text-brand-400" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M12 2L2 7L12 12L22 7L12 2Z" fill="currentColor" opacity="0.8"/>
                <path d="M2 17L12 22L22 17" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M2 12L12 17L22 12" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <div>
                <h1 class="text-xl font-bold text-white leading-tight">Vendor Portal</h1>
                <p class="text-brand-400 text-[10px] tracking-widest uppercase font-semibold">Management System</p>
            </div>
        </div>
        
        <div class="flex items-center space-x-6">
            <div class="hidden md:flex items-center space-x-2 text-sm">
                <div class="w-2 h-2 rounded-full bg-green-400 animate-pulse"></div>
                <span class="text-gray-300">System Online</span>
            </div>
            <div class="h-6 w-px bg-gray-700"></div>
            <div class="flex items-center space-x-3">
                <div class="text-right hidden sm:block">
                    <p class="text-sm font-semibold text-white">{{ Auth::user()->name }}</p>
                    <p class="text-xs text-brand-400 uppercase tracking-wider">{{ Auth::user()->role }}</p>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="p-2 text-gray-400 hover:text-white hover:bg-brand-800 rounded-lg transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                        </svg>
                    </button>
                </form>
            </div>
        </div>
    </header>

    <main class="container mx-auto px-6 py-8">
        
        <!-- Stats Row -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="glass-card rounded-2xl p-6">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-brand-400 text-xs font-bold uppercase tracking-wider mb-1">Total Allocations</p>
                        <h3 class="text-3xl font-bold text-white">1,284</h3>
                    </div>
                    <div class="p-3 bg-brand-800 rounded-lg text-brand-400">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                    </div>
                </div>
            </div>
            
            <div class="glass-card rounded-2xl p-6">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-brand-400 text-xs font-bold uppercase tracking-wider mb-1">Active Drivers</p>
                        <h3 class="text-3xl font-bold text-white">42</h3>
                    </div>
                    <div class="p-3 bg-brand-800 rounded-lg text-brand-400">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    </div>
                </div>
            </div>

            <div class="glass-card rounded-2xl p-6">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-brand-400 text-xs font-bold uppercase tracking-wider mb-1">Queue Status</p>
                        <h3 class="text-3xl font-bold text-green-400">Healthy</h3>
                    </div>
                    <div class="p-3 bg-brand-800 rounded-lg text-green-400">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- Dynamic Role-Based Panel -->
            <div class="lg:col-span-1">
                @if(Auth::user()->isAdmin())
                <!-- Allocation Form (Admin Logistik Only) -->
                <div class="glass-card rounded-2xl p-6 h-full relative overflow-hidden">
                    <!-- Glow effect -->
                    <div class="absolute top-0 right-0 w-32 h-32 bg-brand-500 rounded-full mix-blend-screen filter blur-[50px] opacity-20"></div>
                    
                    <h2 class="text-xl font-bold text-white mb-2">Driver Allocation</h2>
                    <p class="text-sm text-gray-400 mb-6">Assign new delivery tasks to the distribution queue (RabbitMQ).</p>

                    <div id="alertBox" class="hidden mb-6 p-4 rounded-xl text-sm font-semibold border backdrop-blur-sm"></div>

                    <form id="allocationForm" class="space-y-5 relative z-10">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-brand-400 mb-2">Order ID</label>
                            <input type="text" id="order_id" placeholder="e.g., ORD-1002" required class="w-full px-4 py-3 rounded-xl input-dark">
                        </div>
                        
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-brand-400 mb-2">Driver ID</label>
                            <input type="number" id="driver_id" placeholder="e.g., 15" required class="w-full px-4 py-3 rounded-xl input-dark">
                        </div>
                        
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-brand-400 mb-2">Driver Name</label>
                            <input type="text" id="driver_name" placeholder="Full Name" required class="w-full px-4 py-3 rounded-xl input-dark">
                        </div>
                        
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-brand-400 mb-2">Vendor Name</label>
                            <input type="text" id="vendor_name" placeholder="Company Name" required class="w-full px-4 py-3 rounded-xl input-dark">
                        </div>
                        
                        <div class="pt-4">
                            <button type="submit" id="submitBtn" class="w-full py-3.5 px-4 rounded-xl bg-brand-500 hover:bg-brand-400 text-white font-bold shadow-[0_0_15px_rgba(58,96,200,0.4)] transition-all flex justify-center items-center">
                                <span>Dispatch to Queue</span>
                                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                            </button>
                        </div>
                    </form>
                </div>
                @else
                <!-- Vendor Management Panel (Vendor Only) -->
                <div class="glass-card rounded-2xl p-6 h-full relative overflow-hidden">
                    <!-- Glow effect -->
                    <div class="absolute top-0 right-0 w-32 h-32 bg-orange-500 rounded-full mix-blend-screen filter blur-[50px] opacity-10"></div>
                    
                    <h2 class="text-xl font-bold text-white mb-2">Vendor Management</h2>
                    <p class="text-sm text-gray-400 mb-6">Kelola Profil, Supplier, dan Verifikasi Dokumen Perusahaan Anda.</p>
                    
                    <div class="space-y-4 relative z-10">
                        <button class="w-full text-left px-5 py-4 rounded-xl bg-brand-800/50 hover:bg-brand-700/50 border border-brand-700 transition-all group flex items-center justify-between">
                            <div>
                                <h3 class="text-white font-semibold group-hover:text-brand-400 transition-colors">Kelola Profil Vendor</h3>
                                <p class="text-xs text-gray-400 mt-1">Perbarui detail perusahaan dan kontak</p>
                            </div>
                            <svg class="w-5 h-5 text-gray-500 group-hover:text-brand-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </button>

                        <button class="w-full text-left px-5 py-4 rounded-xl bg-brand-800/50 hover:bg-brand-700/50 border border-brand-700 transition-all group flex items-center justify-between">
                            <div>
                                <h3 class="text-white font-semibold group-hover:text-brand-400 transition-colors">Kelola Data Supplier</h3>
                                <p class="text-xs text-gray-400 mt-1">Daftar mitra pasokan logistik</p>
                            </div>
                            <svg class="w-5 h-5 text-gray-500 group-hover:text-brand-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </button>

                        <button class="w-full text-left px-5 py-4 rounded-xl bg-brand-800/50 hover:bg-brand-700/50 border border-brand-700 transition-all group flex items-center justify-between">
                            <div>
                                <h3 class="text-white font-semibold group-hover:text-brand-400 transition-colors">Verifikasi Dokumen</h3>
                                <p class="text-xs text-gray-400 mt-1">Unggah SIUP, TDP, & Dokumen Legal</p>
                            </div>
                            <svg class="w-5 h-5 text-gray-500 group-hover:text-brand-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </button>
                    </div>
                </div>
                @endif
            </div>

            <!-- Recent Activity / Logs -->
            <div class="lg:col-span-2">
                <div class="glass-card rounded-2xl p-6 h-full flex flex-col">
                    <h2 class="text-xl font-bold text-white mb-2">System Logs</h2>
                    <p class="text-sm text-gray-400 mb-6">Recent allocations dispatched to RabbitMQ.</p>
                    
                    <div class="flex-1 bg-brand-900/50 rounded-xl border border-brand-800 p-4 overflow-y-auto max-h-[500px]">
                        <table class="w-full text-left text-sm">
                            <thead class="text-xs text-brand-400 uppercase tracking-wider border-b border-brand-800">
                                <tr>
                                    <th class="pb-3 font-semibold">Timestamp</th>
                                    <th class="pb-3 font-semibold">Order ID</th>
                                    <th class="pb-3 font-semibold">Driver</th>
                                    <th class="pb-3 font-semibold text-right">Status</th>
                                </tr>
                            </thead>
                            <tbody id="logTableBody" class="text-gray-300">
                                <!-- Logs will appear here dynamically -->
                                <tr class="border-b border-brand-800/50">
                                    <td class="py-4">Today, 08:30 AM</td>
                                    <td class="py-4 font-mono text-white">ORD-9982</td>
                                    <td class="py-4">Budi Kurir</td>
                                    <td class="py-4 text-right"><span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-green-500/20 text-green-400 border border-green-500/30">Dispatched</span></td>
                                </tr>
                                <tr class="border-b border-brand-800/50">
                                    <td class="py-4">Today, 08:15 AM</td>
                                    <td class="py-4 font-mono text-white">ORD-9981</td>
                                    <td class="py-4">Asep Logistik</td>
                                    <td class="py-4 text-right"><span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-green-500/20 text-green-400 border border-green-500/30">Dispatched</span></td>
                                </tr>
                            </tbody>
                        </table>
                        
                        <div id="emptyLog" class="hidden flex flex-col items-center justify-center py-12 text-gray-500">
                            <svg class="w-12 h-12 mb-3 opacity-20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                            <p>No recent allocations</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </main>

    <script>
        document.getElementById('allocationForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            const alertBox = document.getElementById('alertBox');
            const submitBtn = document.getElementById('submitBtn');
            const btnText = submitBtn.querySelector('span');
            
            // UI Loading state
            btnText.innerText = 'Dispatching...';
            submitBtn.classList.add('opacity-75', 'cursor-not-allowed');
            
            const data = {
                order_id: document.getElementById('order_id').value,
                driver_id: parseInt(document.getElementById('driver_id').value),
                driver_name: document.getElementById('driver_name').value,
                vendor_name: document.getElementById('vendor_name').value,
            };

            try {
                const response = await fetch('{{ route('allocate.post') }}', {
                    method: 'POST',
                    headers: { 
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify(data)
                });
                
                const result = await response.json();

                if (response.ok) {
                    // Show success
                    alertBox.className = "mb-6 p-4 rounded-xl text-sm font-semibold border bg-green-500/10 border-green-500/30 text-green-400";
                    alertBox.innerHTML = `<div class="flex items-center"><svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> ${result.message}</div>`;
                    
                    // Add to log table
                    addLogEntry(data.order_id, data.driver_name);
                    
                    document.getElementById('allocationForm').reset();
                } else {
                    // Show error
                    alertBox.className = "mb-6 p-4 rounded-xl text-sm font-semibold border bg-red-500/10 border-red-500/30 text-red-400";
                    alertBox.innerHTML = `<div class="flex items-center"><svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg> Failed to dispatch task. Please try again.</div>`;
                }
                alertBox.classList.remove('hidden');
                
                // Hide alert after 5s
                setTimeout(() => { alertBox.classList.add('hidden'); }, 5000);
            } catch (error) {
                console.error(error);
            } finally {
                // Restore button state
                btnText.innerText = 'Dispatch to Queue';
                submitBtn.classList.remove('opacity-75', 'cursor-not-allowed');
            }
        });

        function addLogEntry(orderId, driverName) {
            const tbody = document.getElementById('logTableBody');
            const now = new Date();
            const timeString = now.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
            
            const tr = document.createElement('tr');
            tr.className = 'border-b border-brand-800/50 bg-brand-800/20'; // Highlight new entry
            tr.innerHTML = `
                <td class="py-4">Just now</td>
                <td class="py-4 font-mono text-white">${orderId}</td>
                <td class="py-4">${driverName}</td>
                <td class="py-4 text-right"><span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-green-500/20 text-green-400 border border-green-500/30">Dispatched</span></td>
            `;
            
            // Insert at top
            tbody.insertBefore(tr, tbody.firstChild);
            
            // Remove highlight after 2s
            setTimeout(() => {
                tr.classList.remove('bg-brand-800/20');
            }, 2000);
        }
    </script>
</body>
</html>
