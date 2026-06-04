@extends('layouts.admin')

@section('header_title', 'Overview & Validasi')

@section('content')
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

<!-- Main Content Grid -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    
    <!-- Validasi Vendor Panel -->
    <div class="glass-card rounded-2xl p-6 h-full flex flex-col">
        <h2 class="text-xl font-bold text-white mb-2">Validasi Vendor</h2>
        <p class="text-sm text-gray-400 mb-6">Daftar vendor yang menunggu persetujuan dokumen.</p>
        
        <div class="flex-1 bg-brand-900/50 rounded-xl border border-brand-800 p-4 overflow-y-auto">
            @if(count($pendingVendors) > 0)
            <table class="w-full text-left text-sm">
                <thead class="text-xs text-brand-400 uppercase tracking-wider border-b border-brand-800">
                    <tr>
                        <th class="pb-3 font-semibold">Vendor Name</th>
                        <th class="pb-3 font-semibold text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="text-gray-300">
                    @foreach($pendingVendors as $v)
                    <tr class="border-b border-brand-800/50">
                        <td class="py-4">
                            <p class="font-bold text-white">{{ $v->name }}</p>
                            <p class="text-xs text-gray-400">{{ $v->email }}</p>
                        </td>
                        <td class="py-4 text-right">
                            <button class="px-3 py-1.5 rounded-lg bg-green-500 hover:bg-green-600 text-white text-xs font-bold transition">Approve</button>
                            <button class="px-3 py-1.5 rounded-lg bg-red-500 hover:bg-red-600 text-white text-xs font-bold transition ml-1">Reject</button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <div class="flex flex-col items-center justify-center py-12 text-gray-500">
                <svg class="w-12 h-12 mb-3 opacity-20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <p>Tidak ada vendor yang pending.</p>
            </div>
            @endif
        </div>
    </div>

    <!-- Allocation Form Panel -->
    <div class="glass-card rounded-2xl p-6 h-full relative overflow-hidden">
        <!-- Glow effect -->
        <div class="absolute top-0 right-0 w-32 h-32 bg-brand-500 rounded-full mix-blend-screen filter blur-[50px] opacity-20"></div>
        
        <h2 class="text-xl font-bold text-white mb-2">Driver Allocation</h2>
        <p class="text-sm text-gray-400 mb-6">Assign new delivery tasks to the distribution queue.</p>

        <div id="alertBox" class="hidden mb-6 p-4 rounded-xl text-sm font-semibold border backdrop-blur-sm"></div>

        <form id="allocationForm" class="space-y-5 relative z-10">
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-brand-400 mb-2">Order ID</label>
                <input type="text" id="order_id" placeholder="e.g., ORD-1002" required class="w-full px-4 py-3 rounded-xl input-dark">
            </div>
            
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-brand-400 mb-2">Driver ID</label>
                    <input type="number" id="driver_id" placeholder="e.g., 15" required class="w-full px-4 py-3 rounded-xl input-dark">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-brand-400 mb-2">Driver Name</label>
                    <input type="text" id="driver_name" placeholder="Full Name" required class="w-full px-4 py-3 rounded-xl input-dark">
                </div>
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

</div>
@endsection

@push('scripts')
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
                alertBox.className = "mb-6 p-4 rounded-xl text-sm font-semibold border bg-green-500/10 border-green-500/30 text-green-400";
                alertBox.innerHTML = `<div class="flex items-center"><svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> ${result.message}</div>`;
                document.getElementById('allocationForm').reset();
            } else {
                alertBox.className = "mb-6 p-4 rounded-xl text-sm font-semibold border bg-red-500/10 border-red-500/30 text-red-400";
                alertBox.innerHTML = `<div class="flex items-center"><svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg> Failed to dispatch task. Please try again.</div>`;
            }
            alertBox.classList.remove('hidden');
            
            setTimeout(() => { alertBox.classList.add('hidden'); }, 5000);
        } catch (error) {
            console.error(error);
        } finally {
            btnText.innerText = 'Dispatch to Queue';
            submitBtn.classList.remove('opacity-75', 'cursor-not-allowed');
        }
    });
</script>
@endpush
