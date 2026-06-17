@extends('layouts.admin')

@section('header_title', 'Validasi Dokumen Vendor')

@section('content')

@if(session('success'))
    <div class="mb-6 p-4 rounded-xl text-sm font-semibold border bg-green-500/10 border-green-500/30 text-green-400">
        {{ session('success') }}
    </div>
@endif

<div class="glass-card rounded-2xl p-6 relative overflow-hidden">
    <div class="absolute top-0 right-0 w-32 h-32 bg-[#4F7CFF] rounded-full mix-blend-screen filter blur-[50px] opacity-10 pointer-events-none"></div>

    <h2 class="text-xl font-bold text-white mb-2">Persetujuan Dokumen Vendor</h2>
    <p class="text-sm text-gray-400 mb-6">Daftar vendor baru yang menunggu verifikasi dokumen NIB/SIUP untuk dapat mengelola supplier.</p>
    
    <div class="bg-brand-900/50 rounded-xl border border-brand-800 p-4">
        @if(count($pendingVendors) > 0)
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="text-xs text-brand-400 uppercase tracking-wider border-b border-brand-800">
                    <tr>
                        <th class="pb-3 font-semibold">Nama Vendor & Profil</th>
                        <th class="pb-3 font-semibold text-right">Aksi Tindakan</th>
                    </tr>
                </thead>
                <tbody class="text-gray-300">
                    @foreach($pendingVendors as $v)
                    <tr class="border-b border-brand-800/50">
                        <td class="py-4 pr-4">
                            <p class="font-bold text-white text-base">{{ $v->vendorProfile->company_name ?? $v->name }}</p>
                            <p class="text-xs text-gray-400">Penanggung Jawab: {{ $v->name }} | Email: {{ $v->email }}</p>
                            <p class="text-xs text-gray-400 mt-1">Alamat Kantor: {{ $v->vendorProfile->company_address ?? '-' }}</p>
                            @if($v->vendorProfile && $v->vendorProfile->document_path)
                                <p class="mt-2 text-xs">
                                    <a href="{{ asset('storage/' . $v->vendorProfile->document_path) }}" target="_blank" class="text-[#4F7CFF] hover:underline flex items-center inline-flex font-semibold">
                                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                                        Unduh / Lihat Dokumen SIUP/NIB
                                    </a>
                                </p>
                            @endif
                        </td>
                        <td class="py-4 text-right">
                            <div class="flex justify-end gap-2">
                                <form action="{{ route('admin.vendors.verify', $v->id) }}" method="POST" class="inline">
                                    @csrf
                                    <input type="hidden" name="status" value="Verified">
                                    <button type="submit" class="px-4 py-2 rounded-xl bg-green-500 hover:bg-green-600 text-white text-xs font-bold transition shadow-lg shadow-green-500/20">Setujui</button>
                                </form>
                                <form action="{{ route('admin.vendors.verify', $v->id) }}" method="POST" class="inline">
                                    @csrf
                                    <input type="hidden" name="status" value="Rejected">
                                    <button type="submit" class="px-4 py-2 rounded-xl bg-red-500 hover:bg-red-600 text-white text-xs font-bold transition shadow-lg shadow-red-500/20">Tolak</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="flex flex-col items-center justify-center py-16 text-gray-500">
            <svg class="w-16 h-16 mb-4 opacity-25 text-brand-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <p class="text-sm font-semibold">Semua bersih! Tidak ada pendaftaran vendor yang perlu diverifikasi saat ini.</p>
        </div>
        @endif
    </div>
</div>

@endsection
