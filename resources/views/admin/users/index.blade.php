<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Pengguna - Admin P3M</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex">

    @include('admin.layouts.sidebar')

    <div class="flex-1 flex flex-col ml-64">
        @include('admin.layouts.navbar')

        <main class="flex-1 p-6">
            <!-- Header Halaman -->
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-800">Manajemen Pengguna</h2>
                 <a href="{{   route('admin.users.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition duration-300">
                    Tambah Pengguna
                </a>
            </div>
           

            <!-- Notifikasi Sukses -->
            @if (session('success'))
                <div class="mb-4 flex items-center justify-between bg-green-100 border border-green-300 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <div class="flex items-center space-x-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <span>{{ session('success') }}</span>
                    </div>
                    <button onclick="this.parentElement.style.display='none'" class="text-green-700 hover:text-green-900">&times;</button>
                </div>
            @endif

            <!-- Notifikasi Error -->
             @if (session('error'))
                <div class="mb-4 flex items-center justify-between bg-red-100 border border-red-300 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <span>{{ session('error') }}</span>
                    <button onclick="this.parentElement.style.display='none'" class="text-red-700 hover:text-red-900">&times;</button>
                </div>
            @endif


            <!-- Tabel Data Pengguna -->
            <div class="bg-white shadow rounded-lg p-6">
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white">
                        <thead class="bg-gray-800 text-white">
                            <tr>
                                <th class="text-left py-3 px-4 uppercase font-semibold text-sm">No</th>
                                <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Nama</th>
                                <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Email</th>
                                <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Role</th>
                                <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Status</th>
                                <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-700">
                            @forelse ($users as $user)
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="py-3 px-4">{{ $loop->iteration + $users->firstItem() - 1 }}</td>
                                    <td class="py-3 px-4">{{ $user->name }}</td>
                                    <td class="py-3 px-4">{{ $user->email }}</td>
                                    <td class="py-3 px-4">{{ ucfirst($user->role) }}</td>
                                    <td class="py-3 px-4">
                                        @if ($user->isBlocked())
                                            <span class="font-semibold text-red-600">Diblokir</span>
                                        @else
                                            <span class="font-semibold text-green-600">Aktif</span>
                                        @endif
                                    </td>
                                    <td class="py-3 px-4">
                                        <div class="flex items-center space-x-2">
                                            <a href="{{ route('admin.users.edit', $user) }}" class="bg-yellow-500 text-white px-3 py-1 rounded text-sm hover:bg-yellow-600 transition duration-300">Edit</a>
                                            
                                            @if ($user->isBlocked())
                                                <form action="{{ route('admin.users.unblock', $user) }}" method="POST" onsubmit="return confirm('Anda yakin ingin membuka blokir user ini?');">
                                                    @csrf
                                                    <button type="submit" class="bg-green-500 text-white px-3 py-1 rounded text-sm hover:bg-green-600 transition duration-300">Unblock</button>
                                                </form>
                                            @else
                                                <form action="{{ route('admin.users.block', $user) }}" method="POST" onsubmit="return confirm('Anda yakin ingin memblokir user ini?');">
                                                    @csrf
                                                    <button type="submit" class="bg-orange-500 text-white px-3 py-1 rounded text-sm hover:bg-orange-600 transition duration-300">Block</button>
                                                </form>
                                            @endif

                                            <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('PERINGATAN: Menghapus user tidak dapat dibatalkan. Anda yakin?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded text-sm hover:bg-red-600 transition duration-300">Hapus</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-6 text-gray-500">
                                        Belum ada data pengguna.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <!-- Paginasi -->
                 <div class="mt-4">
                    {{ $users->links() }}
                </div>
            </div>
        </main>
    </div>

</body>
</html>

