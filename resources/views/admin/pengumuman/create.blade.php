<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat Pengumuman Baru - Admin P3M</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex">

    @include('admin.layouts.sidebar')

    <div class="flex-1 flex flex-col ml-64">
        @include('admin.layouts.navbar')

        <main class="flex-1 p-6">
            <!-- Header Halaman -->
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-800">Buat Pengumuman Baru</h2>
                <a href="{{ route('admin.pengumuman.index') }}" 
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

            <!-- Form Tambah Pengumuman -->
            <div class="bg-white shadow rounded-lg p-6">
                <form action="{{ route('admin.pengumuman.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <!-- Judul -->
                    <div class="mb-4">
                        <label for="judul" class="block text-gray-700 text-sm font-bold mb-2">Judul Pengumuman:</label>
                        <input type="text" name="judul" id="judul" value="{{ old('judul') }}" 
                               class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight 
                               focus:outline-none focus:shadow-outline" required>
                    </div>

                    <!-- Kategori -->
                    <div class="mb-4">
                        <label for="kategori_id" class="block text-gray-700 text-sm font-bold mb-2">Kategori:</label>
                        <select name="kategori_id" id="kategori_id" 
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight 
                                focus:outline-none focus:shadow-outline">
                            <option value="">Pilih Kategori</option>
                            @foreach($kategori as $item)
                                <option value="{{ $item->id }}" {{ old('kategori_id') == $item->id ? 'selected' : '' }}>
                                    {{ $item->nama_kategori }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Isi Pengumuman -->
                    <div class="mb-4">
                        <label for="isi" class="block text-gray-700 text-sm font-bold mb-2">Isi Pengumuman:</label>
                        <textarea name="isi" id="isi" rows="8" 
                                  class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight 
                                  focus:outline-none focus:shadow-outline" required>{{ old('isi') }}</textarea>
                    </div>

                    <!-- Periode Pengumuman -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label for="tanggal_mulai" class="block text-gray-700 text-sm font-bold mb-2">Tanggal Mulai:</label>
                            <input type="date" name="tanggal_mulai" id="tanggal_mulai" value="{{ old('tanggal_mulai') }}"
                                   class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight 
                                   focus:outline-none focus:shadow-outline">
                        </div>
                        <div>
                            <label for="tanggal_akhir" class="block text-gray-700 text-sm font-bold mb-2">Tanggal Akhir:</label>
                            <input type="date" name="tanggal_akhir" id="tanggal_akhir" value="{{ old('tanggal_akhir') }}"
                                   class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight 
                                   focus:outline-none focus:shadow-outline">
                        </div>
                    </div>

                    <!-- Upload Gambar -->
                    <div class="mb-6">
                        <label for="gambar" class="block text-gray-700 text-sm font-bold mb-2">Upload Gambar (Opsional):</label>
                        <input type="file" name="gambar" id="gambar" 
                               class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight 
                               focus:outline-none focus:shadow-outline">
                    </div>

                    <!-- Tombol Aksi -->
                    <div class="flex justify-end">
                        <button type="submit" 
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded 
                                focus:outline-none focus:shadow-outline transition duration-300">
                            Simpan Pengumuman
                        </button>
                    </div>
                </form>
            </div>
        </main>
    </div>

</body>
</html>
