@extends('admin.layouts.app')

@section('title', 'Tambah Kategori Baru - Admin P3M')

@section('content')
    <!-- Header Halaman -->
    <div class="flex justify-between items-center mb-8">
        <h2 class="text-3xl font-extrabold text-gray-900">Tambah Kategori Baru</h2>
        <a href="{{ route('admin.kategori-pengumuman.index') }}" 
           class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-lg shadow-sm text-gray-700 bg-white hover:bg-gray-50 transition duration-300">
            &larr; Kembali
        </a>
    </div>

    {{-- Notifikasi Error Validasi --}}
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

    <!-- Konten Form -->
    <div class="bg-white shadow-xl rounded-xl p-8 border border-gray-200">
        <form action="{{ route('admin.kategori-pengumuman.store') }}" method="POST">
            @csrf

            <!-- Input Nama Kategori -->
            <div class="mb-6">
                <label for="nama_kategori" class="block text-sm font-medium text-gray-700 mb-2">
                    Nama Kategori
                </label>
                <input type="text" name="nama_kategori" id="nama_kategori" 
                        value="{{ old('nama_kategori') }}"
                        class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 py-2 px-3 text-gray-800 transition duration-150"
                        placeholder="Masukkan Nama Kategori" required>
            </div>

            <!-- Tombol Aksi -->
            <div class="flex items-center justify-end pt-4 border-t border-gray-100">
                <button type="submit" 
                        class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-lg shadow-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-300">
                    <svg class="w-5 h-5 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    Simpan Kategori
                </button>
            </div>
        </form>
    </div>
@endsection