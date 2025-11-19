@extends('admin.layouts.app')

@section('title', 'Manajemen Pengumuman')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-3xl font-extrabold text-gray-900">Manajemen Pengumuman</h2>
        <a href="{{ route('admin.pengumuman.create') }}" 
           class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg shadow-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-300">
            <svg class="w-5 h-5 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
            Buat Pengumuman Baru
        </a>
    </div>

    {{-- Notifikasi sukses --}}
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

    {{-- Tabel pengumuman --}}
    <div class="bg-white shadow-xl rounded-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="py-3 px-6 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">No</th>
                        <th class="py-3 px-6 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Gambar</th>
                        <th class="py-3 px-6 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Judul</th>
                        <th class="py-3 px-6 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Kategori</th>
                        <th class="py-3 px-6 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Periode Pendaftaran</th>
                        <th class="py-3 px-6 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="py-3 px-6 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($pengumuman as $item)
                        @php
                            // Mengambil status berdasarkan tanggal dan status di database (logika asli dipertahankan)
                            $statusLabel = $item->status === 'Aktif' ? 'Aktif' : 'Tidak Aktif';
                            $statusColor = $item->status === 'Aktif' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-600';
                        @endphp
                        <tr class="hover:bg-gray-50 transition duration-150">
                            <td class="py-4 px-6 whitespace-nowrap text-sm font-medium text-gray-900">{{ $loop->iteration }}</td>
                            <td class="py-4 px-6 whitespace-nowrap">
                                @if ($item->gambar)
                                    <img src="{{ asset('storage/' . $item->gambar) }}" alt="Gambar Pengumuman" class="h-10 w-10 object-cover rounded-md shadow-sm border border-gray-200">
                                @else
                                    <span class="text-gray-400 text-xs italic">N/A</span>
                                @endif
                            </td>
                            <td class="py-4 px-6 max-w-xs truncate text-sm text-gray-800 font-semibold">{{ $item->judul }}</td>
                            <td class="py-4 px-6 whitespace-nowrap text-sm text-gray-600">{{ $item->kategori->nama_kategori ?? 'Umum' }}</td>
                            <td class="py-4 px-6 whitespace-nowrap text-sm text-gray-600">
                                @if ($item->tanggal_mulai && $item->tanggal_akhir)
                                    {{ \Carbon\Carbon::parse($item->tanggal_mulai)->format('d M Y') }} -
                                    {{ \Carbon\Carbon::parse($item->tanggal_akhir)->format('d M Y') }}
                                @else
                                    <span class="text-gray-400 italic">Tidak ditentukan</span>
                                @endif
                            </td>
                            <td class="py-4 px-6 whitespace-nowrap">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $statusColor }}">
                                    {{ $statusLabel }}
                                </span>
                            </td>
                            <td class="py-4 px-6 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('admin.pengumuman.edit', $item->id) }}" 
                                       class="text-blue-600 hover:text-blue-900 transition duration-300 p-2 rounded-full hover:bg-blue-50">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-9-5l7-7m-7 7l-2 2m7-7l-2 2m-2-2L15 7"></path></svg>
                                    </a>
                                    
                                    <form action="{{ route('admin.pengumuman.destroy', $item->id) }}" method="POST"
                                          onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengumuman ini? Tindakan ini tidak dapat dibatalkan.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="text-red-600 hover:text-red-900 transition duration-300 p-2 rounded-full hover:bg-red-50">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-10 text-lg text-gray-500 bg-gray-50 italic">
                                Belum ada data pengumuman yang tersedia saat ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection