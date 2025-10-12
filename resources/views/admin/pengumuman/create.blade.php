<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengumuman Admin P3M</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex">

    @include('admin.layouts.sidebar')

    <div class="flex-1 flex flex-col ml-64">
        @include('admin.layouts.navbar')
        <main class="flex-1 p-6">
            <!-- header pengumuman -->
            <div class="bg-white shadow rounded-lg p-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-4">Tambah Pengumuman</h2>
                <!-- //tombol pengumuman di samping kanan/ -->
                <div class="flex justify-end">
                    <a href="{{ route('admin.pengumuman.index') }}" class="bg-blue-500 text-white px-4 py-2 rounded">Kembali</a>
                </div>
            </div>
            <!-- Form Pengumuman -->
            <form action="{{ route('admin.pengumuman.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="bg-white shadow rounded-lg p-6 mt-6">
                    <div class="mb-4">
                        <label for="judul" class="block text-gray-700 font-bold mb-2">Judul:</label>
                        <input type="text" id="judul" name="judul" class="w-full border border-gray-300 p-2 rounded" required>
                    </div>
                    <div class="mb-4">
                        <label for="isi" class="block text-gray-700 font-bold mb-2">Isi:</label>
                        <textarea id="isi" name="isi" rows="5" class="w-full border border-gray-300 p-2 rounded" required></textarea>
                    </div>
                    <div class="mb-4">
                        <label for="kategori" class="block text-gray-700 font-bold mb-2">Kategori:</label>
                        <input type="text" id="kategori" name="kategori" class="w-full border border-gray-300 p-2 rounded" required>
                    </div>
                    <div class="mb-4">
                        <label for="gambar" class="block text-gray-700 font-bold mb-2">Gambar:</label>
                        <input type="file" id="gambar" name="gambar" class="w-full border border-gray-300 p-2 rounded">
                    </div>
                    <div class="flex justify-end">
                        <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded">Simpan</button>
                    </div>
                </div>
               
        </main>
    </div>

</body>
</html>