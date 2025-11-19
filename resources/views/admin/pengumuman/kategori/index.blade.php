@extends('admin.layouts.app')

@section('title', 'Manajemen Kategori - Admin P3M')

@section('content')
    <!-- Header Halaman -->
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-3xl font-extrabold text-gray-900">Manajemen Kategori</h2>
        <a href="{{ route('admin.kategori-pengumuman.create') }}" 
           class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg shadow-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition duration-300">
            <svg class="w-5 h-5 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
            Buat Kategori Baru
        </a>
    </div>

    <!-- Notifikasi Sukses -->
    @if ($message = Session::get('success'))
        <div class="mb-6 flex items-center justify-between bg-green-50 border border-green-300 text-green-800 px-6 py-4 rounded-lg shadow-sm" role="alert">
            <div class="flex items-center space-x-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="font-medium">{{ $message }}</span>
            </div>
            <button onclick="this.closest('div').remove()" class="text-green-700 hover:text-green-900 focus:outline-none p-1 rounded-full hover:bg-green-100">&times;</button>
        </div>
    @endif

    <!-- Konten Tabel -->
    <div class="bg-white shadow-xl rounded-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="w-1/12 py-3 px-6 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">No</th>
                        <th class="w-8/12 py-3 px-6 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Nama Kategori</th>
                        <th class="w-3/12 py-3 px-6 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($kategori as $item)
                        <tr class="hover:bg-gray-50 transition duration-150">
                            {{-- Menggunakan $kategori->firstItem() + $loop->index untuk nomor urut yang benar pada pagination --}}
                            <td class="py-4 px-6 whitespace-nowrap text-sm font-medium text-gray-900">{{ $kategori->firstItem() + $loop->index }}</td>
                            <td class="py-4 px-6 max-w-lg truncate text-sm text-gray-800 font-semibold">{{ $item->nama_kategori }}</td>
                            <td class="py-4 px-6 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('admin.kategori-pengumuman.edit', $item->id) }}" 
                                       class="text-blue-600 hover:text-blue-900 transition duration-300 p-2 rounded-full hover:bg-blue-50">
                                        {{-- Ikon Edit --}}
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-9-5l7-7m-7 7l-2 2m7-7l-2 2m-2-2L15 7"></path></svg>
                                    </a>
                                    
                                    <form action="{{ route('admin.kategori-pengumuman.destroy', $item->id) }}" 
                                          method="POST"
                                          onsubmit="return confirm('Apakah Anda yakin ingin menghapus kategori: {{ $item->nama_kategori }}? Tindakan ini tidak dapat dibatalkan.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="text-red-600 hover:text-red-900 transition duration-300 p-2 rounded-full hover:bg-red-50">
                                            {{-- Ikon Hapus --}}
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center py-10 text-lg text-gray-500 bg-gray-50 italic">
                                Belum ada data kategori yang tersedia saat ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Paginasi -->
    <div class="mt-6">
        {!! $kategori->links() !!}
    </div>
@endsection