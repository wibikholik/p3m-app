@extends('admin.layouts.app')

@section('title', 'Edit Pengumuman - Admin P3M')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Edit Pengumuman</h2>
        <a href="{{ route('admin.pengumuman.index') }}" 
           class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded transition duration-300">
            Kembali
        </a>
    </div>

    {{-- Notifikasi Error --}}
    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <strong>Terjadi kesalahan!</strong>
            <ul class="mt-2 list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Form Edit --}}
    <div class="bg-white shadow rounded-lg p-6">
        <form action="{{ route('admin.pengumuman.update', $pengumuman->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            {{-- Judul --}}
            <div class="mb-4">
                <label for="judul" class="block text-gray-700 text-sm font-bold mb-2">Judul:</label>
                <input type="text" name="judul" id="judul" 
                       value="{{ old('judul', $pengumuman->judul) }}" 
                       class="shadow border rounded w-full py-2 px-3 text-gray-700 focus:outline-none focus:ring focus:ring-blue-200" required>
            </div>

            {{-- Kategori --}}
            <div class="mb-4">
                <label for="kategori_id" class="block text-gray-700 text-sm font-bold mb-2">Kategori:</label>
                <select name="kategori_id" id="kategori_id" 
                        class="shadow border rounded w-full py-2 px-3 text-gray-700 focus:outline-none focus:ring focus:ring-blue-200">
                    <option value="">Pilih Kategori</option>
                    @foreach($kategori as $item)
                        <option value="{{ $item->id }}" 
                            {{ old('kategori_id', $pengumuman->kategori_id) == $item->id ? 'selected' : '' }}>
                            {{ $item->nama_kategori }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Isi --}}
            <div class="mb-4">
                <label for="isi" class="block text-gray-700 text-sm font-bold mb-2">Isi Pengumuman:</label>
                <textarea name="isi" id="isi" rows="10" 
                          class="shadow border rounded w-full py-2 px-3 text-gray-700 focus:outline-none focus:ring focus:ring-blue-200" required>{{ old('isi', $pengumuman->isi) }}</textarea>
            </div>

            {{-- Tanggal --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label for="tanggal_mulai" class="block text-gray-700 text-sm font-bold mb-2">Tanggal Mulai:</label>
                    <input type="date" name="tanggal_mulai" id="tanggal_mulai" 
                           value="{{ old('tanggal_mulai', $pengumuman->tanggal_mulai) }}" 
                           class="shadow border rounded w-full py-2 px-3 text-gray-700 focus:outline-none focus:ring focus:ring-blue-200">
                </div>
                <div>
                    <label for="tanggal_akhir" class="block text-gray-700 text-sm font-bold mb-2">Tanggal Akhir:</label>
                    <input type="date" name="tanggal_akhir" id="tanggal_akhir" 
                           value="{{ old('tanggal_akhir', $pengumuman->tanggal_akhir) }}" 
                           class="shadow border rounded w-full py-2 px-3 text-gray-700 focus:outline-none focus:ring focus:ring-blue-200">
                </div>
            </div>

            {{-- Upload Gambar --}}
            <div class="mb-4">
                <label for="gambar" class="block text-gray-700 text-sm font-bold mb-2">Gambar (Opsional):</label>
                @if ($pengumuman->gambar)
                    <img src="{{ asset('storage/' . $pengumuman->gambar) }}" 
                         alt="Gambar Pengumuman" 
                         class="h-24 mb-2 rounded">
                @endif
                <input type="file" name="gambar" id="gambar" 
                       class="shadow border rounded w-full py-2 px-3 text-gray-700 focus:outline-none focus:ring focus:ring-blue-200">
            </div>

            {{-- Tombol Simpan --}}
            <div class="flex justify-end">
                <button type="submit" 
                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:ring focus:ring-blue-300">
                    Perbarui Pengumuman
                </button>
            </div>
        </form>
    </div>
@endsection
