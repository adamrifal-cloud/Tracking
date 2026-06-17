@extends('layouts.vendor')

@section('header_title', 'Dashboard Vendor')

@section('content')

@if(session('success'))
    <div class="mb-6 bg-green-50 text-green-700 p-4 rounded-xl border border-green-200 font-medium flex justify-between items-center">
        {{ session('success') }}
        <button onclick="this.parentElement.remove()" class="text-green-700 hover:text-green-900">&times;</button>
    </div>
@endif

<!-- Profil Vendor Summary -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-8 relative overflow-hidden">
    <div class="absolute top-0 right-0 w-40 h-40 bg-vendor-500 rounded-full mix-blend-multiply filter blur-[60px] opacity-10"></div>
    
    <div class="flex flex-col md:flex-row items-start md:items-center justify-between relative z-10 gap-4">
        <div class="flex items-center">
            <div class="w-16 h-16 bg-vendor-100 text-vendor-600 rounded-full flex items-center justify-center text-2xl font-bold mr-6 shadow-sm">
                {{ strtoupper(substr($vendor->name, 0, 1)) }}
            </div>
            <div>
                <h2 class="text-2xl font-bold text-gray-900">{{ $vendor->vendorProfile->company_name ?? $vendor->name }}</h2>
                <p class="text-gray-500">{{ $vendor->email }}</p>
                @if(!$vendor->vendorProfile || !$vendor->vendorProfile->company_name)
                    <p class="text-xs text-red-500 mt-1 font-medium bg-red-50 px-2 py-0.5 rounded inline-block">Profil perusahaan belum lengkap</p>
                @endif
            </div>
        </div>
        <div class="text-left md:text-right flex items-center md:flex-col gap-4 md:gap-1">
            <div>
                <p class="text-xs font-bold uppercase tracking-wider text-gray-400 mb-1">Status Dokumen</p>
                @if($documentStatus === 'Verified')
                    <span class="px-4 py-2 rounded-full text-sm font-bold bg-green-100 text-green-700 border border-green-200 inline-block">
                        Verified
                    </span>
                @elseif($documentStatus === 'Pending Verification')
                    <span class="px-4 py-2 rounded-full text-sm font-bold bg-yellow-100 text-yellow-700 border border-yellow-200 inline-block">
                        Pending Verification
                    </span>
                @elseif($documentStatus === 'Rejected')
                    <span class="px-4 py-2 rounded-full text-sm font-bold bg-red-100 text-red-700 border border-red-200 inline-block">
                        Rejected
                    </span>
                @else
                    <span class="px-4 py-2 rounded-full text-sm font-bold bg-gray-100 text-gray-600 border border-gray-200 inline-block">
                        Not Submitted
                    </span>
                @endif
            </div>
            <a href="{{ route('vendor.profile') }}" class="mt-2 text-xs font-bold text-vendor-600 hover:text-vendor-700 hidden md:block">Update Profil &rarr;</a>
        </div>
    </div>
</div>

<!-- Warning Notice Status Dokumen -->
@if($documentStatus !== 'Verified')
    <div class="mb-8 p-6 rounded-2xl border flex items-start gap-4 {{ $documentStatus === 'Pending Verification' ? 'bg-yellow-50 border-yellow-200 text-yellow-800' : ($documentStatus === 'Rejected' ? 'bg-red-50 border-red-200 text-red-800' : 'bg-gray-50 border-gray-200 text-gray-700') }}">
        <div class="p-2.5 rounded-xl {{ $documentStatus === 'Pending Verification' ? 'bg-yellow-100 text-yellow-600' : ($documentStatus === 'Rejected' ? 'bg-red-100 text-red-600' : 'bg-gray-200 text-gray-500') }} shrink-0">
            @if($documentStatus === 'Pending Verification')
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            @elseif($documentStatus === 'Rejected')
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            @else
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            @endif
        </div>
        <div>
            <h4 class="font-bold text-base mb-1">
                @if($documentStatus === 'Pending Verification')
                    Dokumen Legalitas Sedang Ditinjau
                @elseif($documentStatus === 'Rejected')
                    Dokumen Legalitas Ditolak
                @else
                    Unggah Dokumen Legalitas Anda
                @endif
            </h4>
            <p class="text-sm opacity-90 leading-relaxed">
                @if($documentStatus === 'Pending Verification')
                    Pihak Admin Logistik sedang memverifikasi dokumen SIUP/NIB Anda. Anda tidak dapat mengelola driver sebelum status akun Anda disetujui.
                @elseif($documentStatus === 'Rejected')
                    Dokumen legalitas perusahaan Anda ditolak oleh Admin. Harap buka halaman <a href="{{ route('vendor.profile') }}" class="underline font-semibold hover:text-red-950">Update Profil</a> untuk mengunggah ulang dokumen yang valid.
                @else
                    Anda belum mengunggah dokumen legalitas (SIUP/NIB). Silakan buka halaman <a href="{{ route('vendor.profile') }}" class="underline font-semibold hover:text-gray-950">Update Profil</a> dan unggah dokumen legalitas agar dapat disetujui oleh Admin untuk mulai mengelola driver.
                @endif
            </p>
        </div>
    </div>
@endif

<!-- Stats & Quick Actions Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
    
    <!-- Driver Summary Card -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex flex-col justify-between hover:shadow-md transition duration-300">
        <div>
            <div class="flex justify-between items-start mb-4">
                <span class="text-sm font-bold text-gray-400 uppercase tracking-wider">Total Driver</span>
                <div class="w-10 h-10 rounded-xl bg-vendor-50 text-vendor-600 flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                </div>
            </div>
            <h3 class="text-4xl font-extrabold text-gray-900 mb-2">{{ $totalDrivers }}</h3>
            <p class="text-xs text-gray-500 mb-6">Driver aktif & nonaktif yang terhubung ke akun Anda.</p>
        </div>
        <a href="{{ route('vendor.drivers.index') }}" class="inline-flex items-center text-sm font-bold text-vendor-600 hover:text-vendor-700 group">
            Kelola Driver 
            <span class="ml-1 group-hover:translate-x-1 transition-transform">&rarr;</span>
        </a>
    </div>

    <!-- Notification Summary Card -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex flex-col justify-between hover:shadow-md transition duration-300">
        <div>
            <div class="flex justify-between items-start mb-4">
                <span class="text-sm font-bold text-gray-400 uppercase tracking-wider">Notifikasi Belum Dibaca</span>
                <div class="w-10 h-10 rounded-xl bg-orange-50 text-orange-500 flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                </div>
            </div>
            <h3 class="text-4xl font-extrabold text-gray-900 mb-2">{{ $unreadNotificationsCount }}</h3>
            <p class="text-xs text-gray-500 mb-6">Notifikasi pesanan masuk yang menunggu respon Anda.</p>
        </div>
        <a href="{{ route('vendor.notifications.index') }}" class="inline-flex items-center text-sm font-bold text-vendor-600 hover:text-vendor-700 group">
            Lihat Semua Notifikasi 
            <span class="ml-1 group-hover:translate-x-1 transition-transform">&rarr;</span>
        </a>
    </div>

    <!-- Profile Summary Info Card -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex flex-col justify-between hover:shadow-md transition duration-300">
        <div>
            <div class="flex justify-between items-start mb-4">
                <span class="text-sm font-bold text-gray-400 uppercase tracking-wider">Informasi Profil</span>
                <div class="w-10 h-10 rounded-xl bg-gray-50 text-gray-600 flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                </div>
            </div>
            <div class="space-y-3 mb-6">
                <div>
                    <span class="text-[10px] font-bold text-gray-400 uppercase">Perusahaan</span>
                    <p class="text-sm font-semibold text-gray-800 truncate">{{ $vendor->vendorProfile->company_name ?? 'Belum diisi' }}</p>
                </div>
                <div>
                    <span class="text-[10px] font-bold text-gray-400 uppercase">Alamat</span>
                    <p class="text-sm font-semibold text-gray-800 truncate">{{ $vendor->vendorProfile->company_address ?? 'Belum diisi' }}</p>
                </div>
            </div>
        </div>
        <a href="{{ route('vendor.profile') }}" class="inline-flex items-center text-sm font-bold text-vendor-600 hover:text-vendor-700 group">
            Edit Profil Lengkap 
            <span class="ml-1 group-hover:translate-x-1 transition-transform">&rarr;</span>
        </a>
    </div>

</div>

<!-- Perlu Bantuan Card -->
<div class="mt-8 bg-gradient-to-r from-vendor-500 to-vendor-600 rounded-2xl p-8 text-white shadow-md relative overflow-hidden flex flex-col md:flex-row md:items-center md:justify-between gap-6">
    <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-white opacity-10 rounded-full"></div>
    <div class="relative z-10 max-w-xl">
        <h3 class="font-bold text-xl mb-2">Butuh bantuan terkait akun atau operasional?</h3>
        <p class="text-vendor-100 text-sm leading-relaxed">Hubungi Tim Layanan Hubungan Mitra Logistik jika Anda memiliki pertanyaan seputar verifikasi dokumen, penambahan driver, atau masalah teknis lainnya.</p>
    </div>
    <div class="relative z-10">
        <button class="px-6 py-3 bg-white text-vendor-600 font-extrabold rounded-xl text-sm shadow-md hover:bg-gray-50 transition-all active:scale-95 duration-200">
            Hubungi Admin Support
        </button>
    </div>
</div>

@endsection
