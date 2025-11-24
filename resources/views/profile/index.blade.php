@extends('profile.partials.app') {{-- Pastikan path layout sudah benar --}}

@section('title', 'Halaman Profil')

@section('content')
<div class="max-w-4xl mx-auto mt-8 px-4 sm:px-6 lg:px-8">

    {{-- Notifikasi Sukses --}}
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-md mb-6 relative" role="alert">
            <strong class="font-bold">Berhasil!</strong>
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif
    
    <div class="bg-white shadow-xl rounded-lg overflow-hidden">
        
        <div class="px-6 py-4 bg-blue-600 text-white border-b border-gray-200">
            <h2 class="text-xl font-semibold">👤 Informasi dan Pembaruan Akun Anda</h2>
        </div>
        
        <div class="p-6">
            
            <form method="POST" action="{{ route('profile.update') }}">
                @csrf 
                @method('PUT') 
                
                {{-- Bagian 1: Data Pribadi (Universal) --}}
                <h3 class="text-lg font-bold text-gray-700 mb-4 border-b pb-2">Data Pribadi Dasar</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Input Nama --}}
                    <div class="col-span-1">
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                        <input type="text" name="name" id="name" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('name') border-red-500 @enderror" value="{{ old('name', $user->name) }}" required>
                        @error('name')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Input Email (Disabled) --}}
                    <div class="col-span-1">
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email (Username)</label>
                        <input type="email" id="email" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm bg-gray-100 text-gray-500 cursor-not-allowed sm:text-sm" value="{{ $user->email }}" >
                       
                    </div>
                </div>
                
                {{-- Bagian 2: Data Spesifik Peran --}}
                <h3 class="text-lg font-bold text-gray-700 mt-8 mb-4 border-b pb-2">Data Peran Khusus</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    @if ($user->hasRole('admin'))
                        
                        {{-- KONTEN KHUSUS ADMIN --}}
                        <div class="col-span-full bg-gray-50 p-4 rounded-md border border-gray-200">
                            <i class="fas fa-shield-alt text-blue-600 mr-2"></i> 
                            <span class="text-sm font-medium text-gray-700">Hak Akses: Administrator Penuh. Tidak ada data spesifik yang perlu diubah di halaman ini.</span>
                        </div>

                    @elseif ($user->hasRole('dosen'))
                        
                        {{-- FIELD KHUSUS DOSEN (NIDN dan Jabatan Akademik) --}}
                        <div class="col-span-1">
                            <label for="nidn" class="block text-sm font-medium text-gray-700 mb-1">NIDN/NIDK</label>
                            <input type="text" name="nidn" id="nidn" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" value="{{ old('nidn', $user->nidn ?? '') }}">
                        </div>
                        <div class="col-span-1">
                            <label for="pangkat" class="block text-sm font-medium text-gray-700 mb-1">Jabatan Akademik</label>
                            <input type="text" name="pangkat" id="pangkat" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" value="{{ old('jabatan_akademik', $user->jabatan_akademik ?? '') }}">
                        </div>
                        
                    @elseif ($user->hasRole('reviewer'))
                        
                        {{-- FIELD KHUSUS REVIEWER (Bidang Keahlian) --}}
                        <div class="col-span-full">
                            <label for="expertise" class="block text-sm font-medium text-gray-700 mb-1">Bidang Keahlian (Untuk Penugasan Review)</label>
                            <textarea name="expertise" id="expertise" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" rows="3">{{ old('expertise', $user->expertise ?? '') }}</textarea>
                            <p class="mt-1 text-xs text-gray-500">Pisahkan dengan koma (Contoh: Jaringan, AI, Basis Data).</p>
                        </div>
                        
                    @endif
                </div>
                
                {{-- Tombol Aksi --}}
                <div class="mt-10 pt-4 border-t border-gray-200 flex justify-between items-center">
                    
                    {{-- Tombol Submit --}}
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                        <i class="fas fa-save mr-2"></i>
                        Simpan Perubahan Profil
                    </button>
                    
                    {{-- Tombol Ubah Password --}}
                    <a href="" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-yellow-500 hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 transition-colors">
                        <i class="fas fa-lock mr-2"></i>
                        Ubah Kata Sandi
                    </a>
                </div>
            </form>
            
        </div>
    </div>
</div>
@endsection