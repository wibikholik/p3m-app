<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Pengumuman - Admin P3M</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex">

    @include('admin.layouts.sidebar')

    <div class="flex-1 flex flex-col ml-64">
        @include('admin.layouts.navbar')

        <main class="flex-1 p-6">
            <!-- Header Halaman -->
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-800">Edit Pengumuman</h2>
                <a href="{{ route('admin.pengumuman.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded transition duration-300">
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

            <!-- Konten Form -->
            <div class="bg-white shadow rounded-lg p-6">
                <form action="{{ route('admin.pengumuman.update', $pengumuman->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT') {{-- Metode untuk update --}}
                    
                    <!-- Judul -->
                    <div class="mb-4">
                        <label for="judul" class="block text-gray-700 text-sm font-bold mb-2">Judul:</label>
                        <input type="text" name="judul" id="judul" value="{{ $pengumuman->judul }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                    </div>

                    <!-- Kategori -->
                    <div class="mb-4">
                        <label for="kategori_id" class="block text-gray-700 text-sm font-bold mb-2">Kategori:</label>
                        <select name="kategori_id" id="kategori_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            <option value="">Pilih Kategori</option>
                            @foreach($kategori as $item)
                                <option value="{{ $item->id }}" {{ $pengumuman->kategori_id == $item->id ? 'selected' : '' }}>
                                    {{ $item->nama_kategori }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Isi Pengumuman -->
                    <div class="mb-4">
                        <label for="isi" class="block text-gray-700 text-sm font-bold mb-2">Isi Pengumuman:</label>
                        <textarea name="isi" id="isi" rows="10" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>{{ $pengumuman->isi }}</textarea>
                    </div>

                    <!-- Upload Gambar -->
                    <div class="mb-4">
                        <label for="gambar" class="block text-gray-700 text-sm font-bold mb-2">Ganti Gambar (Opsional):</label>
                        @if($pengumuman->gambar)
                            <div class="mb-2">
                                <img src="{{ asset('storage/' . $pengumuman->gambar) }}" alt="Gambar saat ini" class="h-32 w-auto object-cover rounded">
                                <p class="text-xs text-gray-500 mt-1">Gambar saat ini.</p>
                            </div>
                        @endif
                        <input type="file" name="gambar" id="gambar" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    </div>

                    <!-- Tombol Aksi -->
                    <div class="flex items-center justify-end">
                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline transition duration-300">
                            Update Pengumuman
                        </button>
                    </div>
                </form>
            </div>
        </main>
    </div>

</body>
</html>
