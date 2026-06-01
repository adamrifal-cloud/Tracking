<x-app-layout>
    <!-- Premium Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

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

        <div class="w-full max-w-2xl bg-white rounded-3xl shadow-xl border border-slate-200/50 overflow-hidden relative z-10">
            <!-- Header Banner -->
            <div class="bg-slate-900 px-8 py-10 text-white relative">
                <div class="absolute top-0 right-0 p-6 opacity-10 pointer-events-none">
                    <svg class="w-24 h-24" fill="currentColor" viewBox="0 0 24 24"><path d="M19 8l-7 4-7-4 7-4 7 4zm0 2l-7 4v9l7-4v-9zm-14 0l7 4v9l-7-4v-9z"/></svg>
                </div>
                <h2 class="text-3xl font-black tracking-tight mb-2">Buat Pengiriman Baru</h2>
                <p class="text-slate-400 text-sm font-medium">Lengkapi detail pengirim, penerima, dan deskripsi paket Anda di bawah ini.</p>
            </div>

            <!-- Form -->
            <form id="orderForm" action="{{ route('customer.order.store') }}" method="POST" class="p-8 sm:p-10 space-y-8">
                @csrf

                @if ($errors->any())
                    <div class="p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-2xl text-xs font-semibold">
                        <ul class="list-disc pl-5 space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Grid Layout: Pengirim & Penerima -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Kolom Kiri: Detail Pengirim -->
                    <div class="space-y-5">
                        <h3 class="text-sm font-black text-slate-800 tracking-wider uppercase border-b border-slate-100 pb-2">Detail Pengirim</h3>
                        
                        <div>
                            <label class="block text-[11px] font-extrabold text-slate-400 uppercase tracking-widest mb-1.5">Nama Pengirim</label>
                            <input type="text" name="sender_name" value="{{ old('sender_name', Auth::user()->name) }}" required
                                   class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:border-orange-500 focus:ring-4 focus:ring-orange-100 transition font-semibold text-slate-800 text-sm bg-slate-50/50">
                        </div>

                        <div>
                            <label class="block text-[11px] font-extrabold text-slate-400 uppercase tracking-widest mb-1.5">Telepon Pengirim</label>
                            <input type="text" name="sender_phone" value="{{ old('sender_phone', Auth::user()->phone) }}" required
                                   class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:border-orange-500 focus:ring-4 focus:ring-orange-100 transition font-semibold text-slate-800 text-sm bg-slate-50/50">
                        </div>

                        <div>
                            <label class="block text-[11px] font-extrabold text-slate-400 uppercase tracking-widest mb-1.5">Alamat Lengkap Pengirim</label>
                            <textarea name="sender_address" rows="3" required
                                      class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:border-orange-500 focus:ring-4 focus:ring-orange-100 transition font-semibold text-slate-800 text-sm bg-slate-50/50">{{ old('sender_address', Auth::user()->address) }}</textarea>
                        </div>
                    </div>

                    <!-- Kolom Kanan: Detail Penerima -->
                    <div class="space-y-5">
                        <h3 class="text-sm font-black text-slate-800 tracking-wider uppercase border-b border-slate-100 pb-2">Detail Penerima</h3>

                        <div>
                            <label class="block text-[11px] font-extrabold text-slate-400 uppercase tracking-widest mb-1.5">Nama Penerima</label>
                            <input type="text" name="receiver_name" value="{{ old('receiver_name') }}" required
                                   class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:border-orange-500 focus:ring-4 focus:ring-orange-100 transition font-semibold text-slate-800 text-sm bg-slate-50/50">
                        </div>

                        <div>
                            <label class="block text-[11px] font-extrabold text-slate-400 uppercase tracking-widest mb-1.5">Telepon Penerima</label>
                            <input type="text" name="receiver_phone" value="{{ old('receiver_phone') }}" required
                                   class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:border-orange-500 focus:ring-4 focus:ring-orange-100 transition font-semibold text-slate-800 text-sm bg-slate-50/50">
                        </div>

                        <!-- Dynamic Regional Selectors -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[10px] font-extrabold text-slate-400 uppercase tracking-widest mb-1">Provinsi</label>
                                <select id="receiver_province" required
                                        class="w-full px-3 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:border-orange-500 focus:ring-4 focus:ring-orange-100 transition font-bold text-slate-800 text-xs bg-slate-50/50">
                                    <option value="" disabled selected>Pilih Provinsi</option>
                                </select>
                            </div>
                            
                            <div>
                                <label class="block text-[10px] font-extrabold text-slate-400 uppercase tracking-widest mb-1">Kabupaten/Kota</label>
                                <select id="receiver_regency" required disabled
                                        class="w-full px-3 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:border-orange-500 focus:ring-4 focus:ring-orange-100 transition font-bold text-slate-800 text-xs bg-slate-50/50 disabled:opacity-50">
                                    <option value="" disabled selected>Pilih Kota</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-[10px] font-extrabold text-slate-400 uppercase tracking-widest mb-1">Kecamatan</label>
                                <select id="receiver_district" required disabled
                                        class="w-full px-3 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:border-orange-500 focus:ring-4 focus:ring-orange-100 transition font-bold text-slate-800 text-xs bg-slate-50/50 disabled:opacity-50">
                                    <option value="" disabled selected>Pilih Kecamatan</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-[10px] font-extrabold text-slate-400 uppercase tracking-widest mb-1">Kelurahan/Desa</label>
                                <select id="receiver_village" required disabled
                                        class="w-full px-3 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:border-orange-500 focus:ring-4 focus:ring-orange-100 transition font-bold text-slate-800 text-xs bg-slate-50/50 disabled:opacity-50">
                                    <option value="" disabled selected>Pilih Kelurahan</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="block text-[11px] font-extrabold text-slate-400 uppercase tracking-widest mb-1.5">Alamat Lengkap & Detail Jalan/Rumah</label>
                            <textarea id="receiver_detail_address" rows="3" required placeholder="Contoh: Jl. Diponegoro No. 12, RT 03/RW 04, Perumahan Asri Blok B/5"
                                      class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:border-orange-500 focus:ring-4 focus:ring-orange-100 transition font-semibold text-slate-800 text-sm bg-slate-50/50"></textarea>
                        </div>
                        
                        <!-- Hidden input to submit concatenated full address -->
                        <input type="hidden" name="receiver_address" id="receiver_address_hidden">
                    </div>
                </div>

                <!-- Bagian Bawah: Detail Barang / Paket -->
                <div class="space-y-5 border-t border-slate-100 pt-6">
                    <h3 class="text-sm font-black text-slate-800 tracking-wider uppercase border-b border-slate-100 pb-2">Informasi Paket</h3>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                        <div class="sm:col-span-2">
                            <label class="block text-[11px] font-extrabold text-slate-400 uppercase tracking-widest mb-1.5">Deskripsi Barang</label>
                            <input type="text" name="package_description" placeholder="Contoh: Dokumen, Pakaian, Sepatu" value="{{ old('package_description') }}" required
                                   class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:border-orange-500 focus:ring-4 focus:ring-orange-100 transition font-semibold text-slate-800 text-sm bg-slate-50/50">
                        </div>

                        <div>
                            <label class="block text-[11px] font-extrabold text-slate-400 uppercase tracking-widest mb-1.5">Berat Paket (kg)</label>
                            <div class="relative">
                                <input type="number" step="0.1" min="0.1" name="package_weight" placeholder="1.0" value="{{ old('package_weight') }}" required
                                       class="w-full pl-4 pr-12 py-3 rounded-xl border border-slate-200 focus:outline-none focus:border-orange-500 focus:ring-4 focus:ring-orange-100 transition font-semibold text-slate-800 text-sm bg-slate-50/50">
                                <span class="absolute right-4 top-1/2 -translate-y-1/2 text-xs font-black text-slate-400 uppercase">Kg</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Buttons -->
                <div class="flex items-center justify-between border-t border-slate-100 pt-8 gap-4">
                    <a href="{{ route('track') }}" class="text-xs font-bold text-slate-500 hover:text-slate-800 transition">
                        Batal
                    </a>
                    <button type="submit" class="bg-slate-900 text-white hover:bg-orange-550 active:scale-95 px-8 py-3.5 rounded-2xl font-bold shadow-md shadow-slate-900/10 hover:shadow-orange-500/20 transition-all duration-300 flex items-center justify-center gap-2 text-sm">
                        Buat Pesanan & Bayar
                        <svg class="w-4 h-4" width="16" height="16" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- EMSIFA API Dynamic Address Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const provSelect = document.getElementById('receiver_province');
            const regSelect = document.getElementById('receiver_regency');
            const distSelect = document.getElementById('receiver_district');
            const villSelect = document.getElementById('receiver_village');
            const form = document.getElementById('orderForm');
            const hiddenAddress = document.getElementById('receiver_address_hidden');
            const detailAddress = document.getElementById('receiver_detail_address');

            // 1. Fetch Provinces on load
            fetch('https://www.emsifa.com/api-wilayah-indonesia/api/provinces.json')
                .then(res => res.json())
                .then(data => {
                    data.forEach(prov => {
                        const opt = document.createElement('option');
                        opt.value = prov.id;
                        opt.textContent = prov.name;
                        provSelect.appendChild(opt);
                    });
                })
                .catch(err => console.error('Gagal memuat provinsi:', err));

            // 2. Province change listener
            provSelect.addEventListener('change', function() {
                const provId = this.value;
                
                // Reset child selects
                resetSelect(regSelect, 'Pilih Kota');
                resetSelect(distSelect, 'Pilih Kecamatan');
                resetSelect(villSelect, 'Pilih Kelurahan');

                if (provId) {
                    fetch(`https://www.emsifa.com/api-wilayah-indonesia/api/regencies/${provId}.json`)
                        .then(res => res.json())
                        .then(data => {
                            regSelect.disabled = false;
                            data.forEach(reg => {
                                const opt = document.createElement('option');
                                opt.value = reg.id;
                                opt.textContent = reg.name;
                                regSelect.appendChild(opt);
                            });
                        })
                        .catch(err => console.error('Gagal memuat kota:', err));
                }
            });

            // 3. Regency change listener
            regSelect.addEventListener('change', function() {
                const regId = this.value;
                
                // Reset child selects
                resetSelect(distSelect, 'Pilih Kecamatan');
                resetSelect(villSelect, 'Pilih Kelurahan');

                if (regId) {
                    fetch(`https://www.emsifa.com/api-wilayah-indonesia/api/districts/${regId}.json`)
                        .then(res => res.json())
                        .then(data => {
                            distSelect.disabled = false;
                            data.forEach(dist => {
                                const opt = document.createElement('option');
                                opt.value = dist.id;
                                opt.textContent = dist.name;
                                distSelect.appendChild(opt);
                            });
                        })
                        .catch(err => console.error('Gagal memuat kecamatan:', err));
                }
            });

            // 4. District change listener
            distSelect.addEventListener('change', function() {
                const distId = this.value;
                
                // Reset child selects
                resetSelect(villSelect, 'Pilih Kelurahan');

                if (distId) {
                    fetch(`https://www.emsifa.com/api-wilayah-indonesia/api/villages/${distId}.json`)
                        .then(res => res.json())
                        .then(data => {
                            villSelect.disabled = false;
                            data.forEach(vill => {
                                const opt = document.createElement('option');
                                opt.value = vill.id;
                                opt.textContent = vill.name;
                                villSelect.appendChild(opt);
                            });
                        })
                        .catch(err => console.error('Gagal memuat kelurahan:', err));
                }
            });

            function resetSelect(selectEl, defaultText) {
                selectEl.innerHTML = `<option value="" disabled selected>${defaultText}</option>`;
                selectEl.disabled = true;
            }

            // 5. Concatenate full address before submitting
            form.addEventListener('submit', function(e) {
                const detail = detailAddress.value.trim();
                const provName = provSelect.options[provSelect.selectedIndex].text;
                const regName = regSelect.options[regSelect.selectedIndex].text;
                const distName = distSelect.options[distSelect.selectedIndex].text;
                const villName = villSelect.options[villSelect.selectedIndex].text;

                // Format: Detailed Address, Kelurahan, Kecamatan, Kabupaten/Kota, Provinsi
                const fullAddress = `${detail}, Kel. ${villName}, Kec. ${distName}, ${regName}, Prov. ${provName}`;
                hiddenAddress.value = fullAddress;
            });
        });
    </script>
</x-app-layout>
