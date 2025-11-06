@extends('dosen.layouts.app')

@section('title', 'Detail Pengumuman')

@section('page-title', $pengumuman->judul)

@section('content')
<div class="max-w-5xl mx-auto">
    {{-- Kembali ke daftar pengumuman --}}
    <div class="mb-6">
        <a href="{{ route('dosen.pengumuman.index') }}" class="text-blue-600 hover:underline">
            ← Kembali ke Daftar Pengumuman
        </a>
    </div>

    {{-- Kartu Pengumuman --}}
    <div class="bg-white shadow-md rounded-lg p-6 border border-gray-200">

        {{-- Gambar pengumuman --}}
        @if ($pengumuman->gambar)
            <img src="{{ asset('storage/' . $pengumuman->gambar) }}" 
                 alt="Gambar Pengumuman"
                 class="w-full h-64 object-cover rounded mb-6">
        @endif

        {{-- Judul & Kategori --}}
        <h1 class="text-3xl font-bold text-gray-800 mb-2">{{ $pengumuman->judul }}</h1>
        <p class="text-sm text-gray-500 mb-4">
            {{ $pengumuman->kategori->nama_kategori ?? 'Umum' }} |
            {{ $pengumuman->created_at->format('d M Y') }}
        </p>

        {{-- Isi pengumuman --}}
        <div class="prose max-w-none text-gray-700 mb-6">
            {!! nl2br(e($pengumuman->isi)) !!}
        </div>

        {{-- Informasi tambahan: periode & status --}}
        <div class="p-4 bg-gray-50 border rounded-lg mb-6">
            <p><strong>Periode:</strong>
                @if ($pengumuman->tanggal_mulai && $pengumuman->tanggal_akhir)
                    {{ \Carbon\Carbon::parse($pengumuman->tanggal_mulai)->format('d M Y') }} – 
                    {{ \Carbon\Carbon::parse($pengumuman->tanggal_akhir)->format('d M Y') }}
                @else
                    Tidak ditentukan
                @endif
            </p>

            @php
                $now = now();
                $aktif = $pengumuman->status === 'Aktif' 
                    && $pengumuman->tanggal_mulai 
                    && $pengumuman->tanggal_akhir
                    && $now->between($pengumuman->tanggal_mulai, $pengumuman->tanggal_akhir);
            @endphp

            <p><strong>Status:</strong>
                @if ($aktif)
                    <span class="text-green-700 font-semibold">Aktif</span>
                @else
                    <span class="text-gray-500 font-semibold">Tidak Aktif</span>
                @endif
            </p>
        </div>

        {{-- Tombol Ajukan Usulan --}}
        @if ($aktif)
            <div class="text-center">
                <form action="{{ route('dosen.usulan.create', $pengumuman->id) }}" method="GET">
                    <button type="submit" 
                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-semibold transition">
                        Ajukan Usulan
                    </button>
                </form>
            </div>
        @else
            <div class="bg-gray-100 border border-gray-300 rounded-lg p-6 text-center text-gray-600 mt-4">
                <p>Periode pengajuan untuk pengumuman ini telah berakhir.</p>
                <p class="font-semibold mt-2">Anda tidak dapat mengajukan usulan lagi.</p>
            </div>
        @endif

        {{-- Lampiran pengumuman (opsional) --}}
        @if ($pengumuman->file_lampiran)
            <div class="mt-4">
                <a href="{{ asset('storage/' . $pengumuman->file_lampiran) }}" 
                   class="text-blue-600 hover:underline" target="_blank">
                    Unduh Lampiran
                </a>
            </div>
        @endif

    </div>
</div>
@endsection
