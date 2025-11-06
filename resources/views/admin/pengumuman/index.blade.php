@extends('admin.layouts.app')

@section('title', 'Manajemen Pengumuman')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Manajemen Pengumuman</h2>
        <a href="{{ route('admin.pengumuman.create') }}" 
           class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition duration-300">
            Buat Pengumuman Baru
        </a>
    </div>

    {{-- Notifikasi sukses --}}
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

    {{-- Tabel pengumuman --}}
    <div class="bg-white shadow rounded-lg p-6">
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white">
                <thead class="bg-gray-800 text-white">
                    <tr>
                        <th class="py-3 px-4 text-left text-sm font-semibold uppercase">No</th>
                        <th class="py-3 px-4 text-left text-sm font-semibold uppercase">Gambar</th>
                        <th class="py-3 px-4 text-left text-sm font-semibold uppercase">Judul</th>
                        <th class="py-3 px-4 text-left text-sm font-semibold uppercase">Kategori</th>
                        <th class="py-3 px-4 text-left text-sm font-semibold uppercase">Periode</th>
                        <th class="py-3 px-4 text-left text-sm font-semibold uppercase">Status</th>
                        <th class="py-3 px-4 text-left text-sm font-semibold uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700">
                    @forelse ($pengumuman as $item)
                        @php
                            $now = now();
                            $isAktif = $item->tanggal_mulai && $item->tanggal_akhir && $now->between($item->tanggal_mulai, $item->tanggal_akhir);
                        @endphp
                        <tr class="border-b hover:bg-gray-50">
                            <td class="py-3 px-4">{{ $loop->iteration }}</td>
                            <td class="py-3 px-4">
                                @if ($item->gambar)
                                    <img src="{{ asset('storage/' . $item->gambar) }}" alt="Gambar Pengumuman" class="h-16 w-16 object-cover rounded">
                                @else
                                    <span class="text-gray-400 italic">Tidak ada</span>
                                @endif
                            </td>
                            <td class="py-3 px-4 font-medium text-gray-800">{{ $item->judul }}</td>
                            <td class="py-3 px-4">{{ $item->kategori->nama_kategori ?? 'Tidak ada kategori' }}</td>
                            <td class="py-3 px-4">
                                @if ($item->tanggal_mulai && $item->tanggal_akhir)
                                    {{ \Carbon\Carbon::parse($item->tanggal_mulai)->format('d M Y') }} -
                                    {{ \Carbon\Carbon::parse($item->tanggal_akhir)->format('d M Y') }}
                                @else
                                    <span class="text-gray-400 italic">Tidak ditentukan</span>
                                @endif
                            </td>
                            <td class="py-3 px-4">
                                @if ($item->status === 'Aktif')
                                    <span class="bg-green-100 text-green-700 px-2 py-1 rounded text-sm font-semibold">Aktif</span>
                                @else
                                    <span class="bg-gray-200 text-gray-600 px-2 py-1 rounded text-sm font-semibold">Tidak Aktif</span>
                                @endif
                            </td>
                            <td class="py-3 px-4">
                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('admin.pengumuman.edit', $item->id) }}" 
                                       class="bg-yellow-500 text-white px-3 py-1 rounded text-sm hover:bg-yellow-600 transition duration-300">
                                        Edit
                                    </a>
                                    <form action="{{ route('admin.pengumuman.destroy', $item->id) }}" method="POST"
                                          onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengumuman ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="bg-red-500 text-white px-3 py-1 rounded text-sm hover:bg-red-600 transition duration-300">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-6 text-gray-500">Belum ada data pengumuman.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
