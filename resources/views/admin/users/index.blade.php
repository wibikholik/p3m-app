@extends('admin.layouts.app')

@section('title', 'Manajemen Pengguna - Admin P3M')

@section('content')
    <!-- Header Halaman -->
    <div class="flex justify-between items-center mb-8">
        <h2 class="text-3xl font-extrabold text-gray-900">Manajemen Pengguna</h2>
        <a href="{{ route('admin.users.create') }}"
           class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg shadow-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-300">
            <svg class="w-5 h-5 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
            Tambah Pengguna
        </a>
    </div>

    <!-- Notifikasi -->
    @if (session('success'))
        <div class="mb-6 flex items-center justify-between bg-green-50 border border-green-300 text-green-800 px-6 py-4 rounded-lg shadow-sm" role="alert">
            <div class="flex items-center space-x-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="font-medium">{{ session('success') }}</span>
            </div>
            <button onclick="this.closest('div').remove()" class="text-green-700 hover:text-green-900 focus:outline-none p-1 rounded-full hover:bg-green-100">&times;</button>
        </div>
    @endif

    @if (session('error'))
        <div class="mb-6 flex items-center justify-between bg-red-50 border border-red-300 text-red-800 px-6 py-4 rounded-lg shadow-sm" role="alert">
            <div class="flex items-center space-x-3">
                <svg class="h-6 w-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                <span class="font-medium">{{ session('error') }}</span>
            </div>
            <button onclick="this.closest('div').remove()" class="text-red-700 hover:text-red-900 focus:outline-none p-1 rounded-full hover:bg-red-100">&times;</button>
        </div>
    @endif

    <!-- Tabel Data Pengguna -->
    <div class="bg-white shadow-xl rounded-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="py-3 px-6 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">No</th>
                        <th class="py-3 px-6 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Nama</th>
                        <th class="py-3 px-6 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Email</th>
                        <th class="py-3 px-6 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Role</th>
                        <th class="py-3 px-6 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status Akun</th>
                        <th class="py-3 px-6 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($users as $user)
                        <tr class="hover:bg-gray-50 transition duration-150">
                            <td class="py-4 px-6 whitespace-nowrap text-sm text-gray-900">{{ $loop->iteration + $users->firstItem() - 1 }}</td>
                            <td class="py-4 px-6 whitespace-nowrap text-sm font-medium text-gray-800">{{ $user->name }}</td>
                            <td class="py-4 px-6 whitespace-nowrap text-sm text-gray-600">{{ $user->email }}</td>
                            <td class="py-4 px-6 whitespace-nowrap text-sm text-gray-600">
                                <span class="font-semibold">{{ ucfirst($user->role) }}</span>
                            </td>
                            <td class="py-4 px-6 whitespace-nowrap text-sm">
                                @if ($user->isBlocked())
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800">
                                        Diblokir
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                        Aktif
                                    </span>
                                @endif
                            </td>
                            <td class="py-4 px-6 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center space-x-2">
                                    {{-- Tombol Edit --}}
                                    <a href="{{ route('admin.users.edit', $user) }}"
                                       class="text-blue-600 hover:text-blue-900 transition duration-300 p-2 rounded-full hover:bg-blue-50"
                                       title="Edit">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-9-5l7-7m-7 7l-2 2m7-7l-2 2m-2-2L15 7"></path></svg>
                                    </a>

                                    {{-- Tombol Block/Unblock --}}
                                    @if ($user->isBlocked())
                                        <form action="{{ route('admin.users.unblock', $user) }}" method="POST"
                                              onsubmit="return confirm('Anda yakin ingin membuka blokir user ini?');">
                                            @csrf
                                            <button type="submit"
                                                    class="text-green-600 hover:text-green-900 transition duration-300 p-2 rounded-full hover:bg-green-50"
                                                    title="Unblock">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 21C6.477 21 2 16.523 2 11S6.477 1 12 1c1.238 0 2.43 0.22 3.535 0.635M16 4.5l-6 6M10 16l6-6m-4 4h4M4 11h.01M19 11h.01M12 21v.01M12 1z"></path></svg>
                                            </button>
                                        </form>
                                    @else
                                        <form action="{{ route('admin.users.block', $user) }}" method="POST"
                                              onsubmit="return confirm('Anda yakin ingin memblokir user ini?');">
                                            @csrf
                                            <button type="submit"
                                                    class="text-orange-600 hover:text-orange-900 transition duration-300 p-2 rounded-full hover:bg-orange-50"
                                                    title="Block">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path></svg>
                                            </button>
                                        </form>
                                    @endif

                                    {{-- Tombol Hapus --}}
                                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST"
                                          onsubmit="return confirm('PERINGATAN: Menghapus user tidak dapat dibatalkan. Anda yakin?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="text-red-600 hover:text-red-900 transition duration-300 p-2 rounded-full hover:bg-red-50"
                                                title="Hapus">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-10 text-lg text-gray-500 bg-gray-50 italic">
                                Belum ada data pengguna yang tersedia saat ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Paginasi -->
        <div class="mt-6">
            {{ $users->links() }}
        </div>
    </div>
@endsection