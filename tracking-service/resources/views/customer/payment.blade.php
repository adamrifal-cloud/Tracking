<x-app-layout>

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            -webkit-font-smoothing: antialiased;
        }
    </style>

    <!-- Top Navigation Bar -->
    <header class="fixed top-0 left-0 w-full h-16 bg-white/70 backdrop-blur-xl flex items-center justify-between px-4 sm:px-6 md:px-8 lg:px-12 z-50 border-b border-slate-200/40 shadow-sm">
        <div class="flex items-center gap-3">
            <a href="{{ route('track') }}" class="flex items-center gap-3 hover:opacity-90 transition cursor-pointer group">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-[#FFB703] to-[#FB8500] flex items-center justify-center shadow-lg shadow-orange-500/20 shrink-0 transform group-hover:scale-105 transition duration-350">
                    <svg class="w-5 h-5 text-white" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
                </div>
                <span class="text-lg font-extrabold tracking-tight text-slate-900">Track<span class="text-transparent bg-clip-text bg-gradient-to-r from-[#FFB703] to-[#FB8500]">IT</span></span>
            </a>
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ route('track') }}" class="group rounded-full bg-white border border-slate-200/60 shadow-sm flex items-center justify-center text-slate-500 hover:text-slate-800 hover:shadow-md transition duration-300 shrink-0 cursor-pointer" style="width: 40px; height: 40px; min-width: 40px; min-height: 40px; max-width: 40px; max-height: 40px;" title="Kembali">
                <svg class="w-5 h-5" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
            </a>
        </div>
    </header>

    <!-- Main Container -->
    <div class="min-h-screen w-full relative flex flex-col items-center justify-center bg-slate-50 pt-24 pb-12 px-4 sm:px-6 lg:px-8">
        
        <!-- Ambient Background Glowing Orbs -->
        <div class="absolute top-[10%] left-[5%] w-[300px] h-[300px] rounded-full bg-amber-300/10 blur-[100px] pointer-events-none"></div>
        <div class="absolute bottom-[10%] right-[5%] w-[400px] h-[400px] rounded-full bg-orange-400/10 blur-[125px] pointer-events-none"></div>

        <div class="w-full max-w-lg bg-white rounded-3xl shadow-xl border border-slate-200/50 overflow-hidden relative z-10">
            <!-- Header Banner -->
            <div class="bg-slate-900 px-8 py-8 text-white relative flex justify-between items-center">
                <div>
                    <span class="text-[9px] font-black text-orange-400 uppercase tracking-widest bg-orange-950/40 px-2 py-0.5 rounded border border-orange-500/20">Gerbang Pembayaran</span>
                    <h2 class="text-2xl font-black tracking-tight mt-2.5">Simulasi Pembayaran</h2>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-white/10 flex items-center justify-center border border-white/10 shrink-0">
                    <svg class="w-6 h-6 text-orange-400" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z"/></svg>
                </div>
            </div>

            <!-- Content -->
            <div class="p-8 space-y-6">
                
                <!-- Order ID Alert -->
                @if (session('success'))
                    <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl text-xs font-semibold">
                        {{ session('success') }}
                    </div>
                @endif

                <!-- Billing Summary Card -->
                <div class="p-5 bg-slate-50 border border-slate-200/50 rounded-2xl space-y-4">
                    <div class="flex justify-between items-center border-b border-slate-200/60 pb-3">
                        <span class="text-[10px] font-bold text-slate-450 uppercase tracking-widest">ID Pembayaran</span>
                        <span class="text-xs font-black text-slate-700">#{{ $order->order_id }}</span>
                    </div>

                    <div class="space-y-2.5 text-xs text-slate-600">
                        <div class="flex justify-between">
                            <span class="font-medium">Deskripsi Barang:</span>
                            <span class="font-bold text-slate-800">{{ $order->package_description }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="font-medium">Berat Paket:</span>
                            <span class="font-bold text-slate-800">{{ $order->package_weight }} kg</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="font-medium">Rute:</span>
                            <span class="font-bold text-slate-800 truncate max-w-[200px]" title="{{ $order->sender_address }} to {{ $order->receiver_address }}">
                                {{ explode(',', $order->sender_address)[0] }} &rarr; {{ explode(',', $order->receiver_address)[0] }}
                            </span>
                        </div>
                    </div>

                    <div class="flex justify-between items-end border-t border-slate-200/60 pt-3">
                        <span class="text-[10px] font-black text-slate-500 uppercase tracking-widest leading-none">Total Tagihan</span>
                        <span class="text-xl font-black text-slate-900 leading-none">Rp {{ number_format($order->price, 0, ',', '.') }}</span>
                    </div>
                </div>

                <!-- Payment Methods Form -->
                <form action="{{ route('customer.payment.post', $order->order_id) }}" method="POST" class="space-y-6">
                    @csrf
                    
                    <div>
                        <label class="block text-[11px] font-extrabold text-slate-400 uppercase tracking-widest mb-3.5">Metode Pembayaran</label>
                        
                        <div class="grid grid-cols-1 gap-3.5">
                            <!-- Option 1: Virtual Account -->
                            <label class="relative flex items-center p-4 bg-white border border-slate-200 rounded-2xl cursor-pointer hover:border-orange-350 focus-within:ring-4 focus-within:ring-orange-100 transition duration-300">
                                <input type="radio" name="payment_method" value="va" checked class="h-4 w-4 text-orange-550 border-slate-300 focus:ring-orange-550 shrink-0">
                                <div class="ml-4 flex justify-between items-center w-full">
                                    <div>
                                        <span class="block text-xs font-black text-slate-800">Virtual Account (Bank Transfer)</span>
                                        <span class="block text-[10px] text-slate-450 mt-0.5">Mandiri, BCA, BNI, BRI</span>
                                    </div>
                                    <span class="text-[9px] bg-slate-100 text-slate-500 px-2 py-0.5 rounded font-extrabold uppercase">Otomatis</span>
                                </div>
                            </label>

                            <!-- Option 2: E-Wallet -->
                            <label class="relative flex items-center p-4 bg-white border border-slate-200 rounded-2xl cursor-pointer hover:border-orange-350 focus-within:ring-4 focus-within:ring-orange-100 transition duration-300">
                                <input type="radio" name="payment_method" value="wallet" class="h-4 w-4 text-orange-550 border-slate-300 focus:ring-orange-550 shrink-0">
                                <div class="ml-4 flex justify-between items-center w-full">
                                    <div>
                                        <span class="block text-xs font-black text-slate-800">E-Wallet / QRIS</span>
                                        <span class="block text-[10px] text-slate-450 mt-0.5">GoPay, OVO, Dana, LinkAja</span>
                                    </div>
                                    <span class="text-[9px] bg-emerald-50 text-emerald-600 px-2 py-0.5 rounded font-extrabold uppercase">Instan</span>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Payment Button -->
                    <button type="submit" class="w-full bg-slate-900 text-white hover:bg-orange-550 active:scale-[0.98] py-4 rounded-2xl font-bold shadow-md shadow-slate-900/10 hover:shadow-orange-500/20 transition-all duration-300 flex items-center justify-center gap-2 text-sm">
                        Simulasikan Bayar Sekarang
                        <svg class="w-4 h-4" width="16" height="16" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 01-1.043 3.296 3.745 3.745 0 01-3.296 1.043A3.745 3.745 0 0112 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 01-3.296-1.043 3.745 3.745 0 01-1.043-3.296A3.745 3.745 0 013 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 011.043-3.296 3.746 3.746 0 013.296-1.043A3.746 3.746 0 0112 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 013.296 1.043 3.746 3.746 0 011.043 3.296A3.745 3.745 0 0121 12z"/></svg>
                    </button>
                </form>

                <!-- Cancel Order Action -->
                <form action="{{ route('customer.order.cancel', $order->order_id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan pesanan ini?')" class="mt-2.5">
                    @csrf
                    <button type="submit" class="w-full bg-slate-50 hover:bg-rose-50 text-slate-500 hover:text-rose-600 border border-slate-200/60 py-3.5 rounded-2xl text-xs font-extrabold transition duration-200 cursor-pointer">
                        Batalkan Pesanan Ini
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Custom Leaving Warning Modal -->
    <div id="leavingWarningModal" class="hidden fixed inset-0 z-[9999] flex items-center justify-center p-4">
        <!-- Backdrop -->
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-md transition-opacity duration-300"></div>
        
        <!-- Modal Card -->
        <div class="relative bg-white rounded-3xl shadow-2xl border border-slate-200/50 w-full max-w-md p-6 overflow-hidden transform scale-95 opacity-0 transition-all duration-300">
            <!-- Glowing accent -->
            <div class="absolute -top-12 -left-12 w-24 h-24 rounded-full bg-amber-400/20 blur-xl"></div>
            
            <div class="flex flex-col items-center text-center space-y-4 relative z-10">
                <div class="w-14 h-14 rounded-2xl bg-amber-50 border border-amber-200 flex items-center justify-center text-amber-500 shrink-0 shadow-sm">
                    <svg class="w-7 h-7 animate-bounce-slow" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                
                <div>
                    <h3 class="text-lg font-black text-slate-900 leading-tight">Pembayaran Belum Selesai!</h3>
                    <p class="text-xs text-slate-550 mt-2 leading-relaxed">
                        Pesanan Anda <strong class="text-slate-800">#{{ $order->order_id }}</strong> telah dibuat, tetapi pembayaran belum diselesaikan.
                    </p>
                    <p class="text-xs text-slate-455 mt-1 leading-relaxed">
                        Anda dapat menyelesaikan pembayaran nanti dari Dashboard. Apakah Anda yakin ingin keluar?
                    </p>
                </div>
                
                <div class="flex flex-col sm:flex-row gap-2.5 w-full pt-2">
                    <button id="btnStay" class="w-full bg-slate-900 text-white hover:bg-slate-800 active:scale-95 py-3 rounded-xl text-xs font-bold transition duration-200 cursor-pointer">
                        Lanjutkan Pembayaran
                    </button>
                    <button id="btnLeave" class="w-full bg-slate-100 text-rose-600 hover:bg-rose-50 hover:text-rose-700 active:scale-95 py-3 rounded-xl text-xs font-extrabold border border-rose-200/40 transition duration-200 cursor-pointer">
                        Kembali ke Dashboard
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const modal = document.getElementById('leavingWarningModal');
            const modalContainer = modal.querySelector('.relative.bg-white');
            const btnStay = document.getElementById('btnStay');
            const btnLeave = document.getElementById('btnLeave');
            let isFormSubmitting = false;

            // Mark all forms as submitting so we don't intercept form submissions
            const forms = document.querySelectorAll('form');
            forms.forEach(f => {
                f.addEventListener('submit', () => {
                    isFormSubmitting = true;
                });
            });

            function showWarningModal(onConfirm) {
                modal.classList.remove('hidden');
                // Force layout reflow
                modal.offsetHeight;
                modalContainer.classList.remove('scale-95', 'opacity-0');
                modalContainer.classList.add('scale-100', 'opacity-100');

                // Button actions
                btnStay.onclick = () => {
                    hideWarningModal();
                    // Re-push state so user can press back again
                    history.pushState(null, null, window.location.href);
                };

                btnLeave.onclick = () => {
                    hideWarningModal();
                    isFormSubmitting = true; // prevent double intercept
                    onConfirm();
                };
            }

            function hideWarningModal() {
                modalContainer.classList.remove('scale-100', 'opacity-100');
                modalContainer.classList.add('scale-95', 'opacity-0');
                setTimeout(() => {
                    modal.classList.add('hidden');
                }, 300);
            }

            // 1. Intercept UI back/logo links
            const navLinks = document.querySelectorAll('a[href*="track-order"], a[href*="track"]');
            navLinks.forEach(link => {
                link.addEventListener('click', (e) => {
                    if (isFormSubmitting) return;
                    e.preventDefault();
                    showWarningModal(() => {
                        window.location.href = link.href;
                    });
                });
            });

            // 2. Intercept browser back button (using History API)
            history.pushState(null, null, window.location.href);
            
            window.addEventListener('popstate', (e) => {
                if (isFormSubmitting) return;
                
                showWarningModal(() => {
                    window.location.href = "{{ route('track') }}";
                });
            });

            // 3. Intercept direct reload / tab close (beforeunload)
            window.addEventListener('beforeunload', (e) => {
                if (isFormSubmitting) return;
                e.preventDefault();
                e.returnValue = '';
            });
        });
    </script>
</x-app-layout>
