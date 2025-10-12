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
                <h2 class="text-2xl font-bold text-gray-800 mb-4">Pengumuman</h2>
                <!-- //tombol pengumuman di samping kanan/ -->
                <div class="flex justify-end">
                    <a href="{{ route('admin.pengumuman.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded">Buat Pengumuman Baru</a>
                </div>
            </div>
            <br>
            <!-- alert -->
             <!-- Alert Notifikasi -->
            @if (session('success'))
                <div class="mb-4 flex items-center justify-between bg-green-100 border border-green-300 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <div class="flex items-center space-x-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <span>{{ session('success') }}</span>
                    </div>
                    <button onclick="this.parentElement.style.display='none'" class="text-green-700 hover:text-green-900">
                        &times;
                    </button>
                </div>
            @endif

            @if (session('error'))
                <div class="mb-4 flex items-center justify-between bg-red-100 border border-red-300 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <div class="flex items-center space-x-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        <span>{{ session('error') }}</span>
                    </div>
                    <button onclick="this.parentElement.style.display='none'" class="text-red-700 hover:text-red-900">
                        &times;
                    </button>
                </div>
            @endif
               <!-- Tabel Pengumuman -->
            <div class="mt-6 overflow-x-auto">
                <table class="min-w-full bg-white border border-gray-200 rounded-lg shadow">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="py-2 px-4 border-b text-left">No</th>
                            <th class="py-2 px-4 border-b text-left">Gambar</th>
                            <th class="py-2 px-4 border-b text-left">Judul</th>
                            <th class="py-2 px-4 border-b text-left">Isi</th>
                            <th class="py-2 px-4 border-b text-left">Tanggal</th>
                            <th class="py-2 px-4 border-b text-left">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pengumuman as $item)
                            <tr class="hover:bg-gray-50">
                                <td class="py-2 px-4 border-b text-center">{{ $loop->iteration }}</td>
                                <td class="py-2 px-4 border-b text-center">
                                    @if($item->gambar)
                                        <img src="{{ asset('storage/' . $item->gambar) }}" alt="{{ $item->judul }}" class="w-16 h-16 object-cover mx-auto rounded">
                                    @else
                                        <span class="text-gray-400 italic">Tidak ada</span>
                                    @endif
                                </td>
                                <td class="py-2 px-4 border-b">{{ $item->judul }}</td>
                                <td class="py-2 px-4 border-b">{{ Str::limit($item->isi, 50) }}</td>
                                <td class="py-2 px-4 border-b text-center">{{ $item->created_at->format('Y-m-d') }}</td>
                            
                                <td class="py-2 px-4 border-b text-center">
                                    <button class="bg-blue-500 text-white px-4 py-1 rounded"><a href="{{ route('admin.pengumuman.edit', $item->id) }}">Edit</a></button>
                                    <form action="{{ route('admin.pengumuman.destroy', $item->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button class="bg-red-500 text-white px-2 py-1 rounded" type="submit" onclick="return confirm('Yakin ingin menghapus pengumuman ini?')">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

    @if($pengumuman->isEmpty())
        <div class="text-center text-gray-500 py-4">
            Belum ada data pengumuman.
        </div>
    @endif
</div>

        </main>
    </div>

</body>
<script>
    setTimeout(() => {
        document.querySelectorAll('[role="alert"]').forEach(el => el.style.display = 'none');
    }, 3000);
</script>
</html>
