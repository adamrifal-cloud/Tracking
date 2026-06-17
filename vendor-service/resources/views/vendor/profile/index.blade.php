@extends('layouts.vendor')

@section('header_title', 'Kelola Profil Vendor')

@section('content')

<div class="max-w-3xl mx-auto">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
        
        <div class="mb-8">
            <h3 class="text-2xl font-bold text-gray-900 mb-2">Profil Perusahaan</h3>
            <p class="text-gray-500">Perbarui informasi perusahaan dan unggah dokumen legalitas (SIUP/NIB).</p>
        </div>

        @if(session('success'))
            <div class="mb-6 bg-green-50 text-green-700 p-4 rounded-xl border border-green-200 font-medium">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="mb-6 bg-red-50 text-red-600 p-4 rounded-xl border border-red-200">
                <ul class="list-disc list-inside text-sm font-medium">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('vendor.profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            
            <div class="grid grid-cols-1 gap-6">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Nama Akun</label>
                    <input type="text" value="{{ $vendor->name }}" disabled class="w-full px-4 py-3 rounded-xl bg-gray-100 border border-gray-200 text-gray-500 cursor-not-allowed">
                    <p class="text-xs text-gray-400 mt-1">Nama akun tidak dapat diubah. Hubungi admin untuk perubahan nama utama.</p>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Nama Perusahaan (Resmi)</label>
                    <input type="text" name="company_name" value="{{ old('company_name', $profile->company_name) }}" placeholder="Contoh: PT. Logistik Nusantara" required class="w-full px-4 py-3 rounded-xl bg-gray-50 border border-gray-200 focus:ring-2 focus:ring-vendor-500 focus:border-vendor-500 transition-all">
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Alamat Lengkap Perusahaan</label>
                    <textarea name="company_address" rows="3" required class="w-full px-4 py-3 rounded-xl bg-gray-50 border border-gray-200 focus:ring-2 focus:ring-vendor-500 focus:border-vendor-500 transition-all">{{ old('company_address', $profile->company_address) }}</textarea>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Dokumen Legalitas (SIUP/NIB)</label>
                    
                    @if($profile->document_path)
                        <div class="mb-3 flex items-center justify-between p-3 bg-vendor-50 border border-vendor-100 rounded-lg">
                            <span class="text-sm font-medium text-vendor-700">Dokumen saat ini telah diunggah.</span>
                            <span class="px-2.5 py-1 text-xs font-bold rounded-full bg-white text-vendor-600 border border-vendor-200">{{ $profile->document_status }}</span>
                        </div>
                    @endif

                    <input type="file" name="document" accept=".pdf,.jpg,.jpeg,.png" class="w-full px-4 py-3 rounded-xl bg-gray-50 border border-gray-200 focus:ring-2 focus:ring-vendor-500 focus:border-vendor-500 transition-all file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-bold file:bg-vendor-100 file:text-vendor-700 hover:file:bg-vendor-200">
                    <p class="text-xs text-gray-400 mt-2">Maksimal ukuran file: 2MB. Format: PDF, JPG, PNG. Mengunggah dokumen baru akan mengubah status menjadi <strong>Pending Verification</strong>.</p>
                </div>
            </div>

            <div class="pt-4 border-t border-gray-100 flex justify-end">
                <a href="{{ route('vendor.dashboard') }}" class="px-6 py-3 text-gray-600 font-bold hover:bg-gray-100 rounded-xl transition-colors mr-3">Batal</a>
                <button type="submit" class="px-8 py-3 bg-vendor-500 hover:bg-vendor-600 text-white font-bold rounded-xl shadow-md transition-all">Simpan Perubahan</button>
            </div>
        </form>

    </div>
</div>

@endsection
