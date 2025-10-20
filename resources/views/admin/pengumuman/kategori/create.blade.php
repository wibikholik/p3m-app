<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Kategori Baru - Admin P3M</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex">

    {{-- Sidebar disertakan dari layout Anda --}}
    @include('admin.layouts.sidebar')

    <div class="flex-1 flex flex-col ml-64">
        {{-- Navbar disertakan dari layout Anda --}}
        @include('admin.layouts.navbar')

        <main class="flex-1 p-6">
            <!-- Header Halaman -->
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-800">Tambah Kategori Baru</h2>
                <a href="{{ route('admin.kategori-pengumuman.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded transition duration-300">
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
                <form action="{{ route('admin.kategori-pengumuman.store') }}" method="POST">
                    @csrf
                    
                    <!-- Input Nama Kategori -->
                    <div class="mb-4">
                        <label for="nama_kategori" class="block text-gray-700 text-sm font-bold mb-2">Nama Kategori:</label>
                        <input type="text" name="nama_kategori" id="nama_kategori" value="{{ old('nama_kategori') }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Masukkan Nama Kategori">
                    </div>

                    <!-- Tombol Aksi -->
                    <div class="flex items-center justify-end">
                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline transition duration-300">
                            Simpan Kategori
                        </button>
                    </div>
                </form>
            </div>
        </main>
    </div>

</body>
</html>

