<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Kategori - Admin P3M</title>
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
                <h2 class="text-2xl font-bold text-gray-800">Manajemen Kategori</h2>
                <a href="{{ route('admin.kategori-pengumuman.create') }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded transition duration-300">
                    Buat Kategori Baru
                </a>
            </div>

            <!-- Notifikasi Sukses -->
            @if ($message = Session::get('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ $message }}</span>
                </div>
            @endif

            <!-- Konten Tabel -->
            <div class="bg-white shadow rounded-lg p-6">
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white">
                        <thead class="bg-gray-800 text-white">
                            <tr>
                                <th class="w-1/12 text-left py-3 px-4 uppercase font-semibold text-sm">No</th>
                                <th class="w-8/12 text-left py-3 px-4 uppercase font-semibold text-sm">Nama Kategori</th>
                                <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-700">
                            @forelse ($kategori as $item)
                                <tr class="border-b border-gray-200 hover:bg-gray-100">
                                    <td class="text-left py-3 px-4">{{ $kategori->firstItem() + $loop->index }}</td>
                                    <td class="text-left py-3 px-4">{{ $item->nama_kategori }}</td>
                                    <td class="text-left py-3 px-4">
                                        <form action="{{ route('admin.kategori-pengumuman.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kategori ini?');">
                                            <a href="{{ route('admin.kategori-pengumuman.edit', $item->id) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-3 rounded text-xs transition duration-300">Edit</a>
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-3 rounded text-xs transition duration-300">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center py-4">
                                        <div class="p-4 text-sm text-gray-700 bg-gray-50 rounded-lg" role="alert">
                                            Belum ada data kategori.
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            
            <!-- Paginasi -->
            <div class="mt-6">
                {{-- Catatan: Styling paginasi default Laravel menggunakan Bootstrap. --}}
                {{-- Anda mungkin perlu mempublikasikan dan mengedit view paginasi untuk Tailwind. --}}
                {{-- Jalankan `php artisan vendor:publish --tag=laravel-pagination` --}}
                {!! $kategori->links() !!}
            </div>
        </main>
    </div>

</body>
</html>

