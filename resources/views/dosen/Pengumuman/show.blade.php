@extends('dosen.layouts.app')

@section('title', 'Detail Pengumuman')
@section('page-title', $pengumuman->judul)

@section('content')
<div class="max-w-5xl mx-auto mt-8 px-4 sm:px-6 lg:px-8">

    {{-- Breadcrumb & Back --}}
    <div class="flex items-center justify-between mb-6">
        <nav class="flex" aria-label="Breadcrumb">
            <ol class="flex items-center space-x-2 text-sm text-gray-500">
                <li><a href="{{ route('dosen.pengumuman.index') }}" class="hover:text-blue-600">Pengumuman</a></li>
                <li><span class="text-gray-400">/</span></li>
                <li class="text-gray-900 font-medium truncate max-w-xs" aria-current="page">{{ Str::limit($pengumuman->judul, 30) }}</li>
            </ol>
        </nav>
        <a href="{{ route('dosen.pengumuman.index') }}" class="text-sm font-medium text-gray-500 hover:text-blue-600 flex items-center transition">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Kembali
        </a>
    </div>

    <div class="bg-white shadow overflow-hidden sm:rounded-xl border border-gray-200">
        
        {{-- Gambar Header --}}
        @if ($pengumuman->gambar)
            <div class="relative h-64 w-full sm:h-96 bg-gray-100">
                <img src="{{ asset('storage/' . $pengumuman->gambar) }}" 
                     alt="{{ $pengumuman->judul }}" 
                     class="w-full h-full object-cover">
            </div>
        @endif

        <div class="px-6 py-8 sm:px-10">

            {{-- Header Content --}}
            <div class="border-b border-gray-200 pb-6 mb-6">
                <div class="flex flex-wrap items-center gap-3 mb-3">
                    <span class="inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                        {{ $pengumuman->kategori->nama_kategori ?? 'Umum' }}
                    </span>
                    <span class="text-sm text-gray-500 flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        Diposting: {{ $pengumuman->created_at->format('d M Y') }}
                    </span>
                </div>
                <h1 class="text-3xl font-extrabold text-gray-900 leading-tight">
                    {{ $pengumuman->judul }}
                </h1>
            </div>

            {{-- Status & Periode Card --}}
            @php
                $now = now();
                // Asumsi logic status aktif: 
                // 1. Data status di DB adalah 'Aktif' (opsional, tergantung sistem Anda)
                // 2. Tanggal sekarang berada dalam rentang start & end
                $start = $pengumuman->tanggal_mulai ? \Carbon\Carbon::parse($pengumuman->tanggal_mulai) : null;
                $end = $pengumuman->tanggal_akhir ? \Carbon\Carbon::parse($pengumuman->tanggal_akhir) : null;
                
                $isOpen = $start && $end && $now->between($start, $end);
                $isUpcoming = $start && $now->lessThan($start);
                $isClosed = $end && $now->greaterThan($end);
            @endphp

            <div class="bg-gray-50 rounded-lg p-5 border border-gray-200 flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
                <div class="flex items-start space-x-4">
                    <div class="flex-shrink-0">
                        @if($isOpen)
                            <div class="p-2 bg-green-100 rounded-full">
                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                        @elseif($isClosed)
                            <div class="p-2 bg-red-100 rounded-full">
                                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                            </div>
                        @else
                            <div class="p-2 bg-yellow-100 rounded-full">
                                <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                        @endif
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Periode Pengajuan</p>
                        <p class="text-lg font-semibold text-gray-900">
                            @if($start && $end)
                                {{ $start->format('d M Y') }} <span class="text-gray-400 mx-2">&rarr;</span> {{ $end->format('d M Y') }}
                            @else
                                <span class="italic text-gray-500">Jadwal belum ditentukan</span>
                            @endif
                        </p>
                        <div class="mt-1">
                            @if($isOpen)
                                <span class="text-sm text-green-700 font-medium bg-green-100 px-2 py-0.5 rounded">Sedang Dibuka</span>
                            @elseif($isClosed)
                                <span class="text-sm text-red-700 font-medium bg-red-100 px-2 py-0.5 rounded">Sudah Ditutup</span>
                            @elseif($isUpcoming)
                                <span class="text-sm text-yellow-700 font-medium bg-yellow-100 px-2 py-0.5 rounded">Segera Dibuka</span>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Tombol Aksi --}}
                <div class="flex-shrink-0">
                    @if ($isOpen)
                        <a href="{{ route('dosen.usulan.create', $pengumuman->id) }}" class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition">
                            Ajukan Usulan Sekarang
                            <svg class="ml-2 -mr-1 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                        </a>
                    @else
                        <button disabled class="inline-flex items-center justify-center px-6 py-3 border border-gray-300 text-base font-medium rounded-md text-gray-400 bg-gray-100 cursor-not-allowed">
                            Pengajuan Tidak Tersedia
                        </button>
                    @endif
                </div>
            </div>

            {{-- Isi Konten --}}
            <div class="prose prose-blue max-w-none text-gray-700">
                {!! nl2br(e($pengumuman->isi ?? $pengumuman->deskripsi)) !!}
            </div>

            {{-- File Lampiran --}}
            @if ($pengumuman->file_lampiran)
                <div class="mt-10 border-t border-gray-200 pt-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Dokumen Lampiran</h3>
                    <div class="bg-white border border-gray-200 rounded-lg p-4 flex items-center justify-between max-w-xl hover:shadow-md transition">
                        <div class="flex items-center">
                            <svg class="w-10 h-10 text-red-500 mr-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"></path>
                            </svg>
                            <div>
                                <p class="text-sm font-medium text-gray-900 truncate">Panduan / Lampiran Pengumuman</p>
                                <p class="text-xs text-gray-500">Klik untuk mengunduh</p>
                            </div>
                        </div>
                        <a href="{{ asset('storage/' . $pengumuman->file_lampiran) }}" target="_blank" class="text-blue-600 hover:text-blue-800 font-medium text-sm">
                            Unduh
                        </a>
                    </div>
                </div>
            @endif

        </div>
    </div>
</div>
@endsection