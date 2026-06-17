@extends('layouts.admin')

@section('header_title', 'Overview & Monitoring')

@section('content')

@if(session('success'))
    <div class="mb-6 p-4 rounded-xl text-sm font-semibold border bg-green-500/10 border-green-500/30 text-green-400">
        {{ session('success') }}
    </div>
@endif

<!-- Stats Row -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="glass-card rounded-2xl p-6">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-brand-400 text-xs font-bold uppercase tracking-wider mb-1">Total Allocations</p>
                <h3 class="text-3xl font-bold text-white">{{ number_format($totalAllocations) }}</h3>
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
                <h3 class="text-3xl font-bold text-white">{{ $activeDrivers }}</h3>
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
                <h3 class="text-3xl font-bold text-green-400">{{ $queueStatus }}</h3>
            </div>
            <div class="p-3 bg-brand-800 rounded-lg text-green-400">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
        </div>
    </div>
</div>

<!-- Shipment Monitoring Section -->
<div class="glass-card rounded-2xl p-6 relative overflow-hidden">
    <!-- Subtle glow -->
    <div class="absolute top-0 right-0 w-48 h-48 bg-brand-500 rounded-full mix-blend-screen filter blur-[70px] opacity-10 pointer-events-none"></div>
    
    <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-4 mb-6">
        <div>
            <h2 class="text-xl font-bold text-white">Shipment Monitoring</h2>
            <p class="text-sm text-gray-400">Daftar pemantauan pengiriman logistik secara real-time.</p>
        </div>
        <div>
            <span class="px-3.5 py-1 text-xs font-bold bg-brand-800 text-brand-400 border border-brand-700 rounded-full">
                Total: {{ count($shipments) }} Pengiriman
            </span>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead class="text-xs text-brand-400 uppercase tracking-wider border-b border-brand-800 pb-3">
                <tr>
                    <th class="pb-3 font-semibold">Order ID</th>
                    <th class="pb-3 font-semibold">Pengirim (Customer)</th>
                    <th class="pb-3 font-semibold">Penerima</th>
                    <th class="pb-3 font-semibold">Barang</th>
                    <th class="pb-3 font-semibold">Kurir (Driver)</th>
                    <th class="pb-3 font-semibold text-center">Status</th>
                    <th class="pb-3 font-semibold text-right">Terakhir Diupdate</th>
                    <th class="pb-3 font-semibold text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-gray-300 divide-y divide-brand-800/30">
                @forelse($shipments as $shipment)
                    <tr class="hover:bg-brand-800/10 transition-colors">
                        <td class="py-4 font-bold text-white">{{ $shipment['order_id'] }}</td>
                        <td class="py-4 font-semibold">{{ $shipment['customer_name'] }}</td>
                        <td class="py-4 text-gray-400">{{ $shipment['receiver_name'] }}</td>
                        <td class="py-4">
                            <span class="text-gray-200">{{ $shipment['package_description'] }}</span>
                            <span class="text-xs text-gray-500 block">Berat: {{ $shipment['package_weight'] }} kg</span>
                        </td>
                        <td class="py-4">
                            @if($shipment['driver_id'])
                                <span class="font-semibold text-brand-400">{{ $shipment['driver_name'] }}</span>
                                <span class="text-xs text-gray-500 block">ID: {{ $shipment['driver_id'] }}</span>
                            @else
                                <span class="text-gray-500 italic">Belum Ditunjuk</span>
                            @endif
                        </td>
                        <td class="py-4 text-center">
                            @php
                                $status = $shipment['status'];
                                $badgeClass = 'bg-brand-900 text-gray-400 border-brand-800';
                                if (str_contains(strtolower($status), 'selesai') || str_contains(strtolower($status), 'diterima')) {
                                    $badgeClass = 'bg-green-500/10 text-green-400 border-green-500/25';
                                } elseif (str_contains(strtolower($status), 'perjalanan') || str_contains(strtolower($status), 'terpilih')) {
                                    $badgeClass = 'bg-brand-500/10 text-brand-400 border-brand-500/25';
                                } elseif (str_contains(strtolower($status), 'kemas')) {
                                    $badgeClass = 'bg-yellow-500/10 text-yellow-400 border-yellow-500/25';
                                } elseif (str_contains(strtolower($status), 'batal')) {
                                    $badgeClass = 'bg-red-500/10 text-red-400 border-red-500/25';
                                }
                            @endphp
                            <span class="px-3 py-1 rounded-full text-xs font-bold border {{ $badgeClass }}">
                                {{ $status }}
                            </span>
                        </td>
                        <td class="py-4 text-right text-xs text-gray-400">
                            {{ \Carbon\Carbon::parse($shipment['last_update'])->diffForHumans() }}
                        </td>
                        <td class="py-4 text-right" id="action-cell-{{ $shipment['order_id'] }}">
                            @php
                                $isCompleted = false;
                                $statusLower = strtolower($shipment['status']);
                                if (strpos($statusLower, 'selesai') !== false || 
                                    strpos($statusLower, 'diterima') !== false || 
                                    strpos($statusLower, 'delivered') !== false ||
                                    strpos($statusLower, 'batal') !== false) {
                                    $isCompleted = true;
                                }
                            @endphp

                            @if($isCompleted)
                                <form action="{{ route('admin.orders.destroy', $shipment['order_id']) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data pengiriman ini?')" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-3 py-1.5 rounded-lg bg-red-500/10 hover:bg-red-500 text-red-400 hover:text-white text-xs font-bold transition-all shadow-sm">
                                        Hapus
                                    </button>
                                </form>
                            @elseif(is_null($shipment['driver_id']) || $shipment['driver_id'] === '')
                                <button onclick="openAllocateModal('{{ $shipment['order_id'] }}')" class="px-3 py-1.5 rounded-lg bg-brand-500 hover:bg-brand-400 text-white text-xs font-bold transition-all shadow-[0_0_10px_rgba(58,96,200,0.2)]">
                                    Alokasikan
                                </button>
                            @else
                                <span class="text-xs text-gray-500 italic">Sudah Dialokasi</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="py-12 text-center text-gray-500">
                            <svg class="w-12 h-12 mx-auto mb-3 opacity-20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                            <p>Belum ada data pengiriman terdaftar.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Quick Allocation Modal -->
<div id="quickAllocateModal" class="fixed inset-0 z-50 flex items-center justify-center hidden">
    <!-- Backdrop -->
    <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" onclick="closeAllocateModal()"></div>
    
    <!-- Modal Card -->
    <div class="glass-card rounded-2xl p-6 max-w-md w-full mx-4 relative z-10 border border-brand-500/30 shadow-2xl">
        <div class="flex justify-between items-center mb-4 pb-2 border-b border-brand-800">
            <h3 class="text-lg font-bold text-white">Alokasi Driver Cepat</h3>
            <button onclick="closeAllocateModal()" class="text-gray-400 hover:text-white transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        
        <div id="modalAlertBox" class="hidden mb-4 p-3 rounded-lg text-xs font-semibold border bg-red-500/10 border-red-500/20 text-red-400"></div>
        
        <form id="quickAllocationForm" class="space-y-4">
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-brand-400 mb-1">Order ID</label>
                <input type="text" id="modal_order_id" readonly class="w-full px-3 py-2 rounded-lg input-dark bg-brand-900 border-brand-800 text-gray-400 font-semibold text-sm cursor-not-allowed">
            </div>
            
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-brand-400 mb-1.5">Vendor Name</label>
                <select id="modal_vendor_name" required class="w-full px-3 py-2.5 rounded-lg input-dark bg-brand-800 border-brand-700 text-white text-sm" onchange="filterDriversByVendor(this.value)">
                    <option value="" class="bg-brand-900">-- Pilih Vendor --</option>
                    @forelse($verifiedVendors as $vendor)
                        @php
                            $compName = $vendor->vendorProfile->company_name ?? $vendor->name;
                        @endphp
                        <option value="{{ $compName }}" class="bg-brand-900">{{ $compName }}</option>
                    @empty
                        <option value="" disabled class="bg-brand-900">Tidak ada vendor terverifikasi ditemukan</option>
                    @endforelse
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-brand-400 mb-1.5">Pilih Driver</label>
                <select id="modal_driver_select" required class="w-full px-3 py-2.5 rounded-lg input-dark bg-brand-800 border-brand-700 text-white text-sm" onchange="syncModalDriverData(this.value)">
                    <option value="" class="bg-brand-900">-- Pilih Vendor Terlebih Dahulu --</option>
                </select>
                <input type="hidden" id="modal_driver_id">
                <input type="hidden" id="modal_driver_name">
            </div>
            
            <div class="pt-3 flex gap-2">
                <button type="button" onclick="closeAllocateModal()" class="w-1/3 py-2 rounded-lg bg-brand-800 hover:bg-brand-700 text-gray-300 font-bold transition text-sm">
                    Batal
                  </button>
                <button type="submit" id="modalSubmitBtn" class="flex-1 py-2 rounded-lg bg-brand-500 hover:bg-brand-400 text-white font-bold transition text-sm flex justify-center items-center">
                    <span>Kirim ke Antrean</span>
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
    const allDrivers = @json($drivers);

    function filterDriversByVendor(vendorName) {
        const driverSelect = document.getElementById('modal_driver_select');
        driverSelect.innerHTML = '<option value="" class="bg-brand-900">-- Pilih Driver --</option>';
        
        const idInput = document.getElementById('modal_driver_id');
        const nameInput = document.getElementById('modal_driver_name');
        idInput.value = '';
        nameInput.value = '';

        if (!vendorName) {
            driverSelect.innerHTML = '<option value="" class="bg-brand-900">-- Pilih Vendor Terlebih Dahulu --</option>';
            return;
        }

        const filtered = allDrivers.filter(d => d.vendor_name === vendorName);
        
        if (filtered.length === 0) {
            const option = document.createElement('option');
            option.value = "";
            option.text = "Tidak ada driver aktif untuk vendor ini";
            option.disabled = true;
            option.className = "bg-brand-900";
            driverSelect.appendChild(option);
        } else {
            filtered.forEach(d => {
                const option = document.createElement('option');
                option.value = d.id + '|' + d.name;
                option.text = d.name + ' (ID: ' + d.id + ')';
                option.className = "bg-brand-900";
                driverSelect.appendChild(option);
            });
        }
    }

    function openAllocateModal(orderId) {
        document.getElementById('quickAllocationForm').reset();
        document.getElementById('modal_order_id').value = orderId;
        document.getElementById('quickAllocateModal').classList.remove('hidden');
        document.getElementById('modalAlertBox').classList.add('hidden');
        document.getElementById('modal_driver_id').value = '';
        document.getElementById('modal_driver_name').value = '';
        
        const driverSelect = document.getElementById('modal_driver_select');
        driverSelect.innerHTML = '<option value="" class="bg-brand-900">-- Pilih Vendor Terlebih Dahulu --</option>';
    }

    function closeAllocateModal() {
        document.getElementById('quickAllocateModal').classList.add('hidden');
    }

    function syncModalDriverData(val) {
        const idInput = document.getElementById('modal_driver_id');
        const nameInput = document.getElementById('modal_driver_name');
        if (val) {
            const parts = val.split('|');
            idInput.value = parts[0];
            nameInput.value = parts[1];
        } else {
            idInput.value = '';
            nameInput.value = '';
        }
    }

    document.getElementById('quickAllocationForm').addEventListener('submit', async (e) => {
        e.preventDefault();
        const orderId = document.getElementById('modal_order_id').value;
        const driverId = parseInt(document.getElementById('modal_driver_id').value);
        const driverName = document.getElementById('modal_driver_name').value;
        const vendorName = document.getElementById('modal_vendor_name').value;
        const submitBtn = document.getElementById('modalSubmitBtn');
        const alertBox = document.getElementById('modalAlertBox');

        if (!driverId) {
            alertBox.innerText = 'Harap pilih driver yang valid.';
            alertBox.classList.remove('hidden');
            return;
        }

        submitBtn.querySelector('span').innerText = 'Mengirim...';
        submitBtn.disabled = true;

        try {
            const response = await fetch('{{ route('allocate.post') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    order_id: orderId,
                    driver_id: driverId,
                    driver_name: driverName,
                    vendor_name: vendorName
                })
            });

            const result = await response.json();

            if (response.ok) {
                closeAllocateModal();
                
                // Show layout success toast if available
                if (typeof showToast === 'function') {
                    showToast('✅ Berhasil!', result.message, 'success');
                } else {
                    alert('Berhasil: ' + result.message);
                }

                // Update row content inline
                const actionCell = document.getElementById(`action-cell-${orderId}`);
                if (actionCell) {
                    const row = actionCell.closest('tr');
                    if (row) {
                        // Update Driver column (usually the 5th td)
                        const driverTd = row.cells[4];
                        if (driverTd) {
                            driverTd.innerHTML = `
                                <span class="font-semibold text-brand-400">${driverName}</span>
                                <span class="text-xs text-gray-500 block">ID: Driver-${driverId}</span>
                            `;
                        }

                        // Update Status column (usually the 6th td)
                        const statusTd = row.cells[5];
                        if (statusTd) {
                            statusTd.innerHTML = `
                                <span class="px-3 py-1 rounded-full text-xs font-bold border bg-brand-500/10 text-brand-400 border-brand-500/25">
                                    Driver Terpilih - Bersiap Meluncur
                                </span>
                            `;
                        }

                        // Update Action cell
                        actionCell.innerHTML = `<span class="text-xs text-gray-500 italic">Sudah Dialokasi</span>`;
                    }
                }
            } else {
                alertBox.innerText = result.message || 'Gagal mengirim tugas. Silakan coba lagi.';
                alertBox.classList.remove('hidden');
            }
        } catch (error) {
            console.error(error);
            alertBox.innerText = 'Terjadi kesalahan sistem. Silakan coba lagi.';
            alertBox.classList.remove('hidden');
        } finally {
            submitBtn.querySelector('span').innerText = 'Kirim ke Antrean';
            submitBtn.disabled = false;
        }
    });
</script>
@endpush
