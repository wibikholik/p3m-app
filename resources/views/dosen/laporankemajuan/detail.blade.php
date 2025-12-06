@extends('dosen.layouts.app')

@section('title', 'Detail Laporan Kemajuan')
@section('page-title', 'Detail Laporan Kemajuan')

@section('content')

<div class="max-w-4xl mx-auto bg-white shadow-md rounded-lg p-8 mt-8">

    {{-- Judul --}}
    <h2 class="text-2xl font-bold text-gray-800 mb-6">
        Laporan Kemajuan — {{ Str::limit($laporan->usulan->judul, 60) }}
    </h2>

    {{-- Info Usulan --}}
    <div class="mb-6">
        <h3 class="text-lg font-semibold text-gray-700 mb-2">Informasi Usulan</h3>

        <div class="bg-gray-50 p-4 border rounded-lg">
            <p><strong>Judul:</strong> {{ $laporan->usulan->judul }}</p>
            <p><strong>Skema:</strong> {{ $laporan->usulan->skema }}</p>
            <p><strong>Status Usulan:</strong> 
                <span class="px-2 py-1 rounded text-white
                    @if($laporan->usulan->status == 'Diterima') bg-green-600
                    @elseif($laporan->usulan->status == 'Ditolak') bg-red-600
                    @else bg-gray-600
                    @endif">
                    {{ $laporan->usulan->status }}
                </span>
            </p>
        </div>
    </div>

    {{-- Detail Laporan --}}
    <div class="mb-6">
        <h3 class="text-lg font-semibold text-gray-700 mb-2">Isi Laporan</h3>

        <div class="bg-gray-50 p-4 border rounded-lg leading-relaxed">
            <p class="mb-3">
                <strong>Ringkasan Kemajuan:</strong><br>
                {{ $laporan->ringkasan_kemajuan }}
            </p>

            <p class="mb-3">
                <strong>Kendala:</strong><br>
                {{ $laporan->kendala ?? '-' }}
            </p>

            <p class="mb-3">
                <strong>Persentase Kemajuan:</strong> {{ $laporan->persentase }}%
            </p>

            <p class="mb-3">
                <strong>Status:</strong> 
                <span class="px-2 py-1 rounded text-white
                    @if($laporan->status == 'Terkirim') bg-blue-600
                    @elseif($laporan->status == 'Menunggu Verifikasi') bg-yellow-600
                    @elseif($laporan->status == 'Revisi') bg-red-600
                    @else bg-gray-600
                    @endif">
                    {{ $laporan->status }}
                </span>
            </p>
        </div>
    </div>

    {{-- File Laporan --}}
    <div class="mb-6">
        <h3 class="text-lg font-semibold text-gray-700 mb-2">File Laporan</h3>

        <div class="bg-gray-50 p-4 border rounded-lg">
            <p class="mb-3"><strong>File:</strong></p>

            <a href="{{ asset('storage/' . $laporan->file_laporan) }}"
               target="_blank"
               class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                Lihat / Unduh PDF
            </a>
        </div>
    </div>

    {{-- Aksi --}}
    <div class="flex justify-between mt-8">

        <a href="{{ route('dosen.laporan-kemajuan.index') }}"
           class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">
            Kembali
        </a>

        <div class="flex gap-3">

            {{-- Tombol Edit --}}
            <a href="{{ route('dosen.laporan-kemajuan.edit', $laporan->id) }}"
               class="px-4 py-2 bg-yellow-500 text-white rounded hover:bg-yellow-600">
                Edit
            </a>

            {{-- Tombol Delete --}}
            <form action="{{ route('dosen.laporan-kemajuan.destroy', $laporan->id) }}"
                  method="POST"
                  onsubmit="return confirm('Yakin ingin menghapus laporan ini?');">
                @csrf
                @method('DELETE')

                <button class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">
                    Hapus
                </button>
            </form>

        </div>
    </div>

</div>

@endsection
