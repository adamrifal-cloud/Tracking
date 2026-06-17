@extends('layouts.vendor')

@section('header_title', 'Kelola Driver')

@section('content')

@if(session('success'))
    <div class="mb-6 p-4 rounded-xl text-sm font-semibold border bg-green-500/10 border-green-500/30 text-green-600">
        {{ session('success') }}
    </div>
@endif

@if($errors->has('api_error'))
    <div class="mb-6 p-4 rounded-xl text-sm font-semibold border bg-red-500/10 border-red-500/30 text-red-600">
        {{ $errors->first('api_error') }}
    </div>
@endif

<div class="flex flex-col gap-8">
    
    <!-- Top Action Bar -->
    <div class="flex justify-between items-center bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
        <div>
            <h2 class="text-xl font-extrabold text-gray-900">Kelola Driver Anda</h2>
            <p class="text-sm text-gray-500">Tambahkan driver baru atau verifikasi pelamar mandiri untuk masuk ke armada Anda.</p>
        </div>
        <button onclick="openAddModal()" class="px-5 py-2.5 bg-vendor-500 hover:bg-vendor-600 text-white rounded-xl font-bold transition-all shadow-sm flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Tambah Driver Baru
        </button>
    </div>

    <!-- Main Content Container with Tabs -->
    <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm relative overflow-hidden">
        
        <!-- Tabs Header -->
        <div class="flex border-b border-gray-150 mb-6">
            <button onclick="switchTab('fleet')" id="tab-btn-fleet" class="px-6 py-3 border-b-2 border-vendor-500 text-vendor-600 font-bold text-sm focus:outline-none transition-all cursor-pointer">
                Armada Driver Anda ({{ count($drivers) }})
            </button>
            <button onclick="switchTab('applicants')" id="tab-btn-applicants" class="px-6 py-3 border-b-2 border-transparent text-gray-400 hover:text-gray-600 font-semibold text-sm focus:outline-none transition-all cursor-pointer">
                Pelamar Baru / Driver Mandiri ({{ count($applicants) }})
            </button>
        </div>

        <!-- Tab Content 1: Fleet -->
        <div id="tab-content-fleet" class="block">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="text-xs text-gray-400 uppercase tracking-wider border-b border-gray-100 pb-3">
                        <tr>
                            <th class="pb-3 font-semibold text-gray-500">Driver & Kontak</th>
                            <th class="pb-3 font-semibold text-gray-500">Identitas Kendaraan</th>
                            <th class="pb-3 font-semibold text-center text-gray-500">Status</th>
                            <th class="pb-3 font-semibold text-right text-gray-500">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-700 divide-y divide-gray-100">
                        @forelse($drivers as $d)
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="py-4">
                                    <p class="font-bold text-gray-900 text-base">{{ $d['name'] }}</p>
                                    <p class="text-xs text-gray-500 mt-0.5">Email: {{ $d['email'] }}</p>
                                    <p class="text-xs text-gray-500">HP: {{ $d['phone'] ?? '-' }}</p>
                                </td>
                                <td class="py-4 text-xs">
                                    <p class="font-semibold text-vendor-600">Kendaraan: <span class="text-gray-700">{{ $d['vehicle_number'] ?? '-' }}</span></p>
                                    <p class="font-semibold text-vendor-600 mt-0.5">No. SIM: <span class="text-gray-700">{{ $d['license_number'] ?? '-' }}</span></p>
                                </td>
                                <td class="py-4 text-center">
                                    @php
                                        $status = $d['status'] ?? 'active';
                                        $badgeClass = 'bg-gray-100 text-gray-500 border-gray-200';
                                        if ($status === 'active') {
                                            $badgeClass = 'bg-green-50 text-green-600 border-green-200';
                                        } elseif ($status === 'inactive') {
                                            $badgeClass = 'bg-yellow-50 text-yellow-600 border-yellow-200';
                                        } elseif ($status === 'suspended') {
                                            $badgeClass = 'bg-red-50 text-red-600 border-red-200';
                                        }
                                    @endphp
                                    <span class="px-2.5 py-1 rounded-full text-xs font-bold border {{ $badgeClass }}">
                                        {{ ucfirst($status) }}
                                    </span>
                                </td>
                                <td class="py-4 text-right">
                                    <div class="flex justify-end items-center gap-2">
                                        <!-- Edit Status Button -->
                                        <button onclick="openEditModal({{ json_encode($d) }})" class="p-2 bg-gray-50 hover:bg-gray-100 text-gray-500 hover:text-vendor-600 rounded-lg transition-colors border border-gray-100" title="Ubah Status">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                        </button>
                                        
                                        <!-- Delete Form -->
                                        <form action="{{ route('vendor.drivers.destroy', $d['id']) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun driver ini?')" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 bg-red-50 hover:bg-red-100 text-red-500 rounded-lg transition-colors border border-red-100" title="Hapus Driver">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-4v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-12 text-center text-gray-400">
                                    <svg class="w-12 h-12 mx-auto mb-3 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                    <p>Tidak ada driver terdaftar untuk Vendor Anda.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Tab Content 2: Applicants -->
        <div id="tab-content-applicants" class="hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="text-xs text-gray-400 uppercase tracking-wider border-b border-gray-100 pb-3">
                        <tr>
                            <th class="pb-3 font-semibold text-gray-500">Nama Pelamar</th>
                            <th class="pb-3 font-semibold text-gray-500">Dokumen Kendaraan / SIM</th>
                            <th class="pb-3 font-semibold text-center text-gray-500">Kelengkapan</th>
                            <th class="pb-3 font-semibold text-right text-gray-500">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-700 divide-y divide-gray-100">
                        @forelse($applicants as $app)
                            @php
                                $hasDocs = !empty($app['vehicle_number']) && !empty($app['license_number']);
                            @endphp
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="py-4">
                                    <p class="font-bold text-gray-900 text-base">{{ $app['name'] }}</p>
                                    <p class="text-xs text-gray-500 mt-0.5">Email: {{ $app['email'] }}</p>
                                    <p class="text-xs text-gray-500">HP: {{ $app['phone'] ?? '-' }}</p>
                                </td>
                                <td class="py-4 text-xs">
                                    <p class="font-semibold text-gray-500">Plat Kendaraan: <span class="text-gray-800">{{ $app['vehicle_number'] ?? 'Belum Diisi' }}</span></p>
                                    <p class="font-semibold text-gray-500 mt-0.5">Nomor SIM: <span class="text-gray-800">{{ $app['license_number'] ?? 'Belum Diisi' }}</span></p>
                                </td>
                                <td class="py-4 text-center">
                                    @if($hasDocs)
                                        <span class="px-2.5 py-1 rounded-full text-xs font-bold border bg-green-50 text-green-600 border-green-200">
                                            Dokumen Lengkap
                                        </span>
                                    @else
                                        <span class="px-2.5 py-1 rounded-full text-xs font-bold border bg-amber-50 text-amber-600 border-amber-200">
                                            Menunggu Profil Lengkap
                                        </span>
                                    @endif
                                </td>
                                <td class="py-4 text-right">
                                    <button onclick="openRecruitModal({{ json_encode($app) }})" class="px-4 py-2 bg-vendor-500 hover:bg-vendor-600 text-white rounded-xl text-xs font-bold shadow-sm transition-all">
                                        Rekrut & Verifikasi
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-12 text-center text-gray-400">
                                    <svg class="w-12 h-12 mx-auto mb-3 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                                    <p>Tidak ada pendaftar driver mandiri saat ini.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

</div>

<!-- CRUD Modals -->
<!-- 1. Add Driver Modal -->
<div id="addDriverModal" class="fixed inset-0 z-50 flex items-center justify-center hidden">
    <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" onclick="closeAddModal()"></div>
    <div class="bg-white rounded-2xl p-6 max-w-md w-full mx-4 relative z-10 border border-gray-100 shadow-xl">
        <div class="flex justify-between items-center mb-4 pb-2 border-b border-gray-100">
            <h3 class="text-lg font-bold text-gray-900">Tambah Driver Baru</h3>
            <button onclick="closeAddModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        
        <form action="{{ route('vendor.drivers.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-gray-500 mb-1">Nama Lengkap</label>
                <input type="text" name="name" required class="w-full px-3 py-2 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-vendor-500">
            </div>
            
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-gray-500 mb-1">Alamat Email</label>
                <input type="email" name="email" required class="w-full px-3 py-2 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-vendor-500">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-500 mb-1">No. Telepon</label>
                    <input type="text" name="phone" class="w-full px-3 py-2 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-vendor-500">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-500 mb-1">Password</label>
                    <input type="password" name="password" required class="w-full px-3 py-2 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-vendor-500">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-500 mb-1">Plat Nomor</label>
                    <input type="text" name="vehicle_number" placeholder="B 1234 XYZ" class="w-full px-3 py-2 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-vendor-500">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-500 mb-1">Nomor SIM</label>
                    <input type="text" name="license_number" placeholder="SIM-12345" class="w-full px-3 py-2 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-vendor-500">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-gray-500 mb-1">Status Awal</label>
                <select name="status" required class="w-full px-3 py-2 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-vendor-500">
                    <option value="active">Active (Aktif)</option>
                    <option value="inactive">Inactive (Tidak Aktif)</option>
                </select>
            </div>
            
            <div class="pt-3 flex gap-2">
                <button type="button" onclick="closeAddModal()" class="w-1/3 py-2.5 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-600 font-bold transition text-sm">
                    Batal
                </button>
                <button type="submit" class="flex-1 py-2.5 rounded-xl bg-vendor-500 hover:bg-vendor-600 text-white font-bold transition text-sm flex justify-center items-center">
                    Simpan Driver
                </button>
            </div>
        </form>
    </div>
</div>

<!-- 2. Edit Driver Modal (Mainly Status update) -->
<div id="editDriverModal" class="fixed inset-0 z-50 flex items-center justify-center hidden">
    <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" onclick="closeEditModal()"></div>
    <div class="bg-white rounded-2xl p-6 max-w-md w-full mx-4 relative z-10 border border-gray-100 shadow-xl">
        <div class="flex justify-between items-center mb-4 pb-2 border-b border-gray-100">
            <h3 class="text-lg font-bold text-gray-900">Ubah Status Driver</h3>
            <button onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        
        <!-- Driver Info Summary -->
        <div class="mb-4 p-4 bg-gray-50 rounded-xl border border-gray-100 text-sm space-y-1">
            <p class="font-bold text-gray-800" id="editDriverName">-</p>
            <p class="text-gray-500 text-xs" id="editDriverEmail">-</p>
            <p class="text-gray-500 text-xs" id="editDriverVehicle">-</p>
        </div>

        <form id="editDriverForm" method="POST" class="space-y-4">
            @csrf
            @method('PUT')
            
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-gray-500 mb-1">Status Verifikasi / Akun</label>
                <select name="status" id="editDriverStatus" required class="w-full px-3 py-2 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-vendor-500">
                    <option value="active">Active (Aktif)</option>
                    <option value="inactive">Inactive (Tidak Aktif)</option>
                    <option value="suspended">Suspended (Ditangguhkan)</option>
                </select>
            </div>
            
            <div class="pt-3 flex gap-2">
                <button type="button" onclick="closeEditModal()" class="w-1/3 py-2.5 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-600 font-bold transition text-sm">
                    Batal
                </button>
                <button type="submit" class="flex-1 py-2.5 rounded-xl bg-vendor-500 hover:bg-vendor-600 text-white font-bold transition text-sm flex justify-center items-center">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- 3. Recruit Driver Modal (Enter vehicle details) -->
<div id="recruitDriverModal" class="fixed inset-0 z-50 flex items-center justify-center hidden">
    <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" onclick="closeRecruitModal()"></div>
    <div class="bg-white rounded-2xl p-6 max-w-md w-full mx-4 relative z-10 border border-gray-100 shadow-xl">
        <div class="flex justify-between items-center mb-4 pb-2 border-b border-gray-100">
            <h3 class="text-lg font-bold text-gray-900">Rekrut & Aktifkan Driver</h3>
            <button onclick="closeRecruitModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>

        <form id="recruitDriverForm" method="POST" class="space-y-4">
            @csrf
            @method('PUT')
            <input type="hidden" name="recruit" value="true">
            <input type="hidden" name="status" value="active">

            <div class="mb-2 p-4 bg-vendor-50 text-vendor-600 rounded-xl border border-vendor-200 text-xs">
                Verifikasi dokumen kelayakan berkendara driver di bawah sebelum menyetujui rekrutmen.
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-gray-500 mb-1">Nama Driver</label>
                <input type="text" id="recruitDriverNameInput" readonly class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm text-gray-500 cursor-not-allowed">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-gray-500 mb-1">Nomor SIM</label>
                <input type="text" name="license_number" id="recruitDriverLicenseInput" required class="w-full px-3 py-2 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-vendor-500">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-gray-500 mb-1">Plat Nomor Kendaraan</label>
                <input type="text" name="vehicle_number" id="recruitDriverVehicleInput" required class="w-full px-3 py-2 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-vendor-500">
            </div>

            <div class="pt-3 flex gap-2">
                <button type="button" onclick="closeRecruitModal()" class="w-1/3 py-2.5 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-600 font-bold transition text-sm">
                    Batal
                </button>
                <button type="submit" class="flex-1 py-2.5 rounded-xl bg-vendor-500 hover:bg-vendor-600 text-white font-bold transition text-sm flex justify-center items-center">
                    Terima Bergabung
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
    function switchTab(tab) {
        const btnFleet = document.getElementById('tab-btn-fleet');
        const btnApplicants = document.getElementById('tab-btn-applicants');
        const contentFleet = document.getElementById('tab-content-fleet');
        const contentApplicants = document.getElementById('tab-content-applicants');

        if (tab === 'fleet') {
            btnFleet.className = "px-6 py-3 border-b-2 border-vendor-500 text-vendor-600 font-bold text-sm focus:outline-none transition-all cursor-pointer";
            btnApplicants.className = "px-6 py-3 border-b-2 border-transparent text-gray-400 hover:text-gray-600 font-semibold text-sm focus:outline-none transition-all cursor-pointer";
            contentFleet.classList.remove('hidden');
            contentApplicants.classList.add('hidden');
        } else {
            btnFleet.className = "px-6 py-3 border-b-2 border-transparent text-gray-400 hover:text-gray-600 font-semibold text-sm focus:outline-none transition-all cursor-pointer";
            btnApplicants.className = "px-6 py-3 border-b-2 border-vendor-500 text-vendor-600 font-bold text-sm focus:outline-none transition-all cursor-pointer";
            contentFleet.classList.add('hidden');
            contentApplicants.classList.remove('hidden');
        }
    }

    function openAddModal() {
        document.getElementById('addDriverModal').classList.remove('hidden');
    }
    function closeAddModal() {
        document.getElementById('addDriverModal').classList.add('hidden');
    }
    
    function openEditModal(driver) {
        document.getElementById('editDriverName').innerText = driver.name;
        document.getElementById('editDriverEmail').innerText = "Email: " + driver.email;
        document.getElementById('editDriverVehicle').innerText = "Kendaraan: " + (driver.vehicle_number || '-');
        
        document.getElementById('editDriverStatus').value = driver.status;
        document.getElementById('editDriverForm').action = "/vendor/drivers/" + driver.id;
        
        document.getElementById('editDriverModal').classList.remove('hidden');
    }
    function closeEditModal() {
        document.getElementById('editDriverModal').classList.add('hidden');
    }

    function openRecruitModal(app) {
        document.getElementById('recruitDriverNameInput').value = app.name;
        document.getElementById('recruitDriverLicenseInput').value = app.license_number || '';
        document.getElementById('recruitDriverVehicleInput').value = app.vehicle_number || '';
        
        document.getElementById('recruitDriverForm').action = "/vendor/drivers/" + app.id;
        document.getElementById('recruitDriverModal').classList.remove('hidden');
    }
    function closeRecruitModal() {
        document.getElementById('recruitDriverModal').classList.add('hidden');
    }
</script>
@endpush
