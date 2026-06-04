@extends('layouts.vendor')

@section('header_title', 'Dashboard Vendor')

@section('content')

<!-- Profil Vendor Summary -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-8 relative overflow-hidden">
    <div class="absolute top-0 right-0 w-40 h-40 bg-vendor-500 rounded-full mix-blend-multiply filter blur-[60px] opacity-10"></div>
    
    <div class="flex items-center justify-between relative z-10">
        <div class="flex items-center">
            <div class="w-16 h-16 bg-vendor-100 text-vendor-600 rounded-full flex items-center justify-center text-2xl font-bold mr-6">
                {{ substr($vendor->name, 0, 1) }}
            </div>
            <div>
                <h2 class="text-2xl font-bold text-gray-900">{{ $vendor->name }}</h2>
                <p class="text-gray-500">{{ $vendor->email }}</p>
            </div>
        </div>
        <div class="text-right">
            <p class="text-xs font-bold uppercase tracking-wider text-gray-400 mb-1">Status Dokumen</p>
            <span class="px-4 py-2 rounded-full text-sm font-bold bg-yellow-100 text-yellow-700 border border-yellow-200">
                {{ $documentStatus }}
            </span>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    
    <!-- Tabel Supplier -->
    <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-lg font-bold text-gray-900">Daftar Supplier</h3>
            <button class="px-4 py-2 bg-vendor-500 hover:bg-vendor-600 text-white rounded-lg text-sm font-bold transition shadow-sm">
                + Tambah Supplier
            </button>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="text-xs text-gray-500 uppercase tracking-wider border-b border-gray-100">
                    <tr>
                        <th class="pb-3 font-semibold">Nama Supplier</th>
                        <th class="pb-3 font-semibold">Kontak</th>
                        <th class="pb-3 font-semibold text-right">Status</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700">
                    @foreach($suppliers as $supplier)
                    <tr class="border-b border-gray-50">
                        <td class="py-4 font-semibold text-gray-900">{{ $supplier['name'] }}</td>
                        <td class="py-4">{{ $supplier['contact'] }}</td>
                        <td class="py-4 text-right">
                            @if($supplier['status'] == 'Active')
                                <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-green-100 text-green-700">Active</span>
                            @else
                                <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-gray-100 text-gray-600">Inactive</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Cepat Akses Panel -->
    <div class="lg:col-span-1 space-y-4">
        <div class="bg-gradient-to-br from-vendor-500 to-vendor-600 rounded-2xl p-6 text-white shadow-lg">
            <h3 class="font-bold text-lg mb-2">Perlu Bantuan?</h3>
            <p class="text-vendor-100 text-sm mb-4">Hubungi tim Admin Logistik jika dokumen Anda belum diverifikasi lebih dari 24 jam.</p>
            <button class="w-full py-2 bg-white text-vendor-600 font-bold rounded-xl text-sm shadow-sm hover:bg-gray-50 transition">
                Hubungi Admin
            </button>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h3 class="font-bold text-gray-900 mb-4">Aktivitas Terakhir</h3>
            <div class="space-y-4">
                <div class="flex items-start">
                    <div class="w-2 h-2 rounded-full bg-vendor-500 mt-1.5 mr-3"></div>
                    <div>
                        <p class="text-sm font-medium text-gray-800">Dokumen SIUP Diunggah</p>
                        <p class="text-xs text-gray-400">2 Hari yang lalu</p>
                    </div>
                </div>
                <div class="flex items-start">
                    <div class="w-2 h-2 rounded-full bg-gray-300 mt-1.5 mr-3"></div>
                    <div>
                        <p class="text-sm font-medium text-gray-800">Pendaftaran Akun Berhasil</p>
                        <p class="text-xs text-gray-400">3 Hari yang lalu</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
