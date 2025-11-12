@extends('dosen.layouts.app')

@section('title', 'Pengumuman Dosen')

@section('content')
<h2 class="text-2xl font-bold text-gray-800 mb-6">Daftar Pengumuman</h2>

{{-- Filter Kategori --}}
<div class="mb-6 flex justify-end">
    <form action="{{ route('dosen.pengumuman.index') }}" method="GET" class="flex space-x-2">
        <select name="kategori" class="border border-gray-300 rounded-lg px-3 py-2 text-gray-700 focus:ring focus:ring-blue-200">
            <option value="">Semua Kategori</option>
            @foreach ($kategori as $kat)
                <option value="{{ $kat->id }}" {{ request('kategori') == $kat->id ? 'selected' : '' }}>
                    {{ $kat->nama_kategori }}
                </option>
            @endforeach
        </select>
        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition duration-300">
            Filter
        </button>
    </form>
</div>

{{-- Daftar Pengumuman --}}
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @forelse ($pengumuman as $item)
        @php
            $now = now();
            $isAktif = $item->tanggal_mulai && $item->tanggal_akhir 
                && $now->between($item->tanggal_mulai, $item->tanggal_akhir);
        @endphp

        <div class="bg-white shadow rounded-lg overflow-hidden flex flex-col">
            @if ($item->gambar)
                <img src="{{ asset('storage/' . $item->gambar) }}" alt="Gambar Pengumuman" class="h-40 w-full object-cover">
            @else
                <div class="h-40 bg-gray-200 flex items-center justify-center text-gray-400 italic">
                    Tidak ada gambar
                </div>
            @endif

            <div class="p-4 flex-1 flex flex-col">
                <h3 class="text-lg font-semibold text-gray-800 mb-2">{{ $item->judul }}</h3>
                <p class="text-sm text-gray-500 mb-2">{{ $item->kategori->nama_kategori ?? 'Tanpa Kategori' }}</p>

                <p class="text-sm text-gray-600 mb-2">
                    <strong>Periode:</strong>
                    @if ($item->tanggal_mulai && $item->tanggal_akhir)
                        {{ \Carbon\Carbon::parse($item->tanggal_mulai)->format('d M Y') }} -
                        {{ \Carbon\Carbon::parse($item->tanggal_akhir)->format('d M Y') }}
                    @else
                        Tidak ditentukan
                    @endif
                </p>

                <a href="{{ route('dosen.pengumuman.show', $item->id) }}" 
                   class="mt-3 inline-block text-blue-600 font-semibold hover:underline">
                   Lihat Detail →
                </a>

                <div class="mt-auto pt-3">
                    @if ($isAktif)
                        <a href="{{ route('dosen.usulan.create', $item->id) }}" 
                           class="block w-full text-center bg-green-500 text-white py-2 rounded hover:bg-green-600 transition duration-300">
                            Ajukan Usulan
                        </a>
                    @else
                        <button disabled 
                                class="block w-full text-center bg-gray-300 text-gray-600 py-2 rounded cursor-not-allowed">
                            Sudah Berakhir
                        </button>
                    @endif
                </div>
            </div>
        </div>
    @empty
        <div class="col-span-full text-center text-gray-500 py-10">
            Belum ada pengumuman yang tersedia.
        </div>
    @endforelse
</div>
@endsection
