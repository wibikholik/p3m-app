@extends('admin.layouts.app')

@section('title', 'Edit Kategori - Admin P3M')

@section('content')
    <!-- Header Halaman -->
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Edit Kategori</h2>
        <a href="{{ route('admin.kategori-pengumuman.index') }}" 
           class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded transition duration-300">
            Kembali
        </a>
    </div>

    <!-- Notifikasi Error Validasi -->
    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <strong class="font-bold">Whoops!</strong>
            <span class="block sm:inline">Ada beberapa masalah dengan input Anda.</span>
            <ul class="list-disc list-inside mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Form Edit -->
    <div class="bg-white shadow rounded-lg p-6">
        <form action="{{ route('admin.kategori-pengumuman.update', $kategoriPengumuman->id) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Input Nama Kategori -->
            <div class="mb-4">
                <label for="nama_kategori" class="block text-gray-700 text-sm font-bold mb-2">
                    Nama Kategori:
                </label>
                <input type="text" name="nama_kategori" id="nama_kategori"
                       value="{{ old('nama_kategori', $kategoriPengumuman->nama_kategori) }}"
                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight 
                              focus:outline-none focus:shadow-outline"
                       placeholder="Masukkan Nama Kategori">
            </div>

            <!-- Tombol Aksi -->
            <div class="flex items-center justify-end">
                <button type="submit" 
                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded 
                               focus:outline-none focus:shadow-outline transition duration-300">
                    Update Kategori
                </button>
            </div>
        </form>
    </div>
@endsection
