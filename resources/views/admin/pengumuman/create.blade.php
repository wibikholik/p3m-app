@extends('admin.layouts.app')

@section('title', 'Buat Pengumuman Baru - Admin P3M')

@section('content')
<!-- Header Halaman -->
<div class="flex justify-between items-center mb-8">
    <h2 class="text-3xl font-extrabold text-gray-900">Buat Pengumuman Baru</h2>
    <a href="{{ route('admin.pengumuman.index') }}" 
       class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-lg shadow-sm text-gray-700 bg-white hover:bg-gray-50 transition duration-300">
        &larr; Kembali
    </a>
</div>

<!-- Notifikasi Error Validasi -->
@if ($errors->any())
    <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 rounded-lg shadow-sm" role="alert">
        <p class="font-bold mb-1">Terjadi Kesalahan:</p>
        <ul class="list-disc pl-5 mt-1 text-sm">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<!-- Form Tambah Pengumuman -->
<div class="bg-white shadow-xl rounded-xl p-8 border border-gray-200">
    <form action="{{ route('admin.pengumuman.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <!-- Judul -->
        <div class="mb-5">
            <label for="judul" class="block text-sm font-medium text-gray-700 mb-2">Judul Pengumuman</label>
            <input type="text" name="judul" id="judul" value="{{ old('judul') }}" 
                   placeholder="Masukkan Judul Pengumuman"
                   class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 py-2 px-3 text-gray-800 transition duration-150" required>
        </div>

        <!-- Kategori -->
        <div class="mb-5">
            <label for="kategori_id" class="block text-sm font-medium text-gray-700 mb-2">Kategori</label>
            <select name="kategori_id" id="kategori_id" 
                    class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 py-2 px-3 text-gray-800 transition duration-150">
                <option value="">Pilih Kategori</option>
                @foreach($kategori as $item)
                    <option value="{{ $item->id }}" {{ old('kategori_id') == $item->id ? 'selected' : '' }}>
                        {{ $item->nama_kategori }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Isi Pengumuman -->
        <div class="mb-5">
            <label for="isi" class="block text-sm font-medium text-gray-700 mb-2">Isi Pengumuman</label>
            <textarea name="isi" id="isi" rows="8" 
                      placeholder="Tulis detail lengkap pengumuman di sini..."
                      class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 py-2 px-3 text-gray-800 transition duration-150" required>{{ old('isi') }}</textarea>
        </div>

        <!-- Periode Pengumuman -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-5">
            <div>
                <label for="tanggal_mulai" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Mulai Pendaftaran (Opsional)</label>
                <input type="date" name="tanggal_mulai" id="tanggal_mulai" value="{{ old('tanggal_mulai') }}"
                        class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 py-2 px-3 text-gray-800 transition duration-150">
            </div>
            <div>
                <label for="tanggal_akhir" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Akhir Pendaftaran (Opsional)</label>
                <input type="date" name="tanggal_akhir" id="tanggal_akhir" value="{{ old('tanggal_akhir') }}"
                        class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 py-2 px-3 text-gray-800 transition duration-150">
            </div>
        </div>

        <!-- Upload Gambar -->
        <div class="mb-6">
            <label for="gambar" class="block text-sm font-medium text-gray-700 mb-2">Upload Gambar (Opsional)</label>
            <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:bg-gray-50 transition">
                <div class="space-y-1 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    <div class="flex text-sm text-gray-600 justify-center">
                        <label for="gambar" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                            <span>Pilih file</span>
                            <input id="gambar" name="gambar" type="file" accept="image/*" class="sr-only">
                        </label>
                        <p class="pl-1">atau drag and drop</p>
                    </div>
                    <p class="text-xs text-gray-500">PNG, JPG, GIF hingga 1MB</p>
                </div>
            </div>
        </div>

        <!-- Tombol Aksi -->
        <div class="pt-6 border-t border-gray-200 flex justify-end">
            <button type="submit" 
                    class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-lg shadow-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-300">
                <svg class="w-5 h-5 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                Simpan Pengumuman
            </button>
        </div>
    </form>
</div>
@endsection