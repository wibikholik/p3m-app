@extends('dosen.layouts.app')

@section('title', 'Detail Usulan')
@section('page-title', 'Detail Usulan')

@section('content')
<div class="max-w-5xl mx-auto mt-8 px-4 sm:px-6 lg:px-8">

    {{-- Header & Back Button --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 gap-4">
        <div>
            <nav class="flex mb-1" aria-label="Breadcrumb">
                <ol class="flex items-center space-x-2 text-sm text-gray-500">
                    <li><a href="{{ route('dosen.usulan.index') }}" class="hover:text-blue-600">Usulan Saya</a></li>
                    <li><span class="text-gray-400">/</span></li>
                    <li class="text-gray-900 font-medium" aria-current="page">Detail</li>
                </ol>
            </nav>
            <h1 class="text-2xl font-bold text-gray-900">Detail Usulan</h1>
        </div>
        <a href="{{ route('dosen.usulan.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
            &larr; Kembali ke Daftar
        </a>
    </div>

    {{-- Status Alert Section --}}
    <div class="mb-6 border-l-4 rounded-r-lg p-4 shadow-sm
        @if($usulan->status == 'Draft' || $usulan->status == 'Revisi') border-yellow-400 bg-yellow-50
        @elseif($usulan->status == 'diajukan' || $usulan->status == 'Menunggu Persetujuan') border-blue-400 bg-blue-50
        @elseif($usulan->status == 'Diterima') border-green-400 bg-green-50
        @else border-red-400 bg-red-50
        @endif">
        <div class="flex">
            <div class="ml-3">
                <h3 class="text-sm font-medium 
                    @if($usulan->status == 'Draft' || $usulan->status == 'Revisi') text-yellow-800
                    @elseif($usulan->status == 'diajukan') text-blue-800
                    @elseif($usulan->status == 'Diterima') text-green-800
                    @else text-red-800
                    @endif">
                    Status: {{ ucfirst($usulan->status) }}
                </h3>
                <div class="mt-2 text-sm 
                    @if($usulan->status == 'Draft') text-yellow-700
                    @elseif($usulan->status == 'diajukan') text-blue-700
                    @else text-gray-700
                    @endif">
                    @if($usulan->status == 'Draft')
                        <p>Usulan ini belum diajukan. Silakan periksa kelengkapan data di bawah lalu klik tombol <strong>Submit</strong>.</p>
                    @elseif($usulan->status == 'diajukan')
                        <p>Usulan sedang dalam proses review oleh admin/reviewer. Anda tidak dapat mengubah data saat ini.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        {{-- LEFT COLUMN: Informasi Utama --}}
        <div class="lg:col-span-2 space-y-6">
            
            {{-- Card Detail Usulan --}}
            <div class="bg-white shadow overflow-hidden sm:rounded-lg border border-gray-200">
                <div class="px-4 py-5 sm:px-6 bg-gray-50 border-b border-gray-200">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Informasi Dasar</h3>
                </div>
                <div class="px-4 py-5 sm:p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Judul Usulan</label>
                        <p class="mt-1 text-lg font-semibold text-gray-900">{{ $usulan->judul }}</p>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Skema</label>
                            <p class="mt-1 text-sm text-gray-900 bg-gray-100 inline-block px-2 py-1 rounded">{{ $usulan->skema }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Tahun Pelaksanaan</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $usulan->tahun_pelaksanaan ?? $usulan->created_at->year }}</p>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-500">Abstrak / Deskripsi</label>
                        <div class="mt-1 text-sm text-gray-900 text-justify leading-relaxed bg-gray-50 p-3 rounded border border-gray-100">
                            {{ $usulan->abstrak ?? $usulan->deskripsi }}
                        </div>
                    </div>
                </div>
            </div>

            {{-- Card File Proposal --}}
            <div class="bg-white shadow sm:rounded-lg border border-gray-200">
                <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Berkas Proposal</h3>
                </div>
                <div class="px-4 py-5 sm:p-6">
                    @if($usulan->file_usulan) 
                        <div class="flex items-center justify-between p-4 bg-blue-50 rounded-lg border border-blue-100">
                            <div class="flex items-center">
                                {{-- Icon PDF --}}
                                <svg class="w-8 h-8 text-red-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"></path>
                                </svg>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">File Proposal Lengkap.pdf</p>
                                    <p class="text-xs text-gray-500">Klik tombol di samping untuk mengunduh</p>
                                </div>
                            </div>
                            <a href="{{ asset('storage/'.$usulan->file_usulan) }}" target="_blank" class="inline-flex items-center px-3 py-2 border border-blue-300 shadow-sm text-sm leading-4 font-medium rounded-md text-blue-700 bg-white hover:bg-blue-50 focus:outline-none transition">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                Lihat File
                            </a>
                        </div>
                    @else
                        <div class="text-center p-4 bg-red-50 rounded border border-red-100 text-red-600 text-sm">
                            <svg class="w-6 h-6 mx-auto mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                            File proposal belum diunggah.
                        </div>
                    @endif
                </div>
            </div>

        </div>

        {{-- RIGHT COLUMN: Anggota & Meta --}}
        <div class="space-y-6">
            
            {{-- Card Anggota --}}
            <div class="bg-white shadow sm:rounded-lg border border-gray-200">
                <div class="px-4 py-4 bg-gray-50 border-b border-gray-200">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Tim Peneliti</h3>
                </div>
                <div class="p-4">
                    @if($usulan->anggota && $usulan->anggota->count() > 0)
                        <ul class="divide-y divide-gray-100">
                            {{-- Ketua (User Login) --}}
                            <li class="py-3 flex items-center">
                                <div class="h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold text-xs mr-3">
                                    K
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ $usulan->user->name ?? 'Ketua' }}</p>
                                    <p class="text-xs text-gray-500">Ketua Pengusul</p>
                                </div>
                            </li>

                            {{-- Anggota Lain --}}
                            @foreach($usulan->anggota as $agt)
                                <li class="py-3 flex items-center">
                                    <div class="h-8 w-8 rounded-full bg-gray-100 flex items-center justify-center text-gray-600 font-bold text-xs mr-3">
                                        A
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">{{ $agt->nama }}</p>
                                        <p class="text-xs text-gray-500">{{ $agt->jabatan ?? 'Anggota' }}</p>
                                        <hr>    
                                        <p class="text-xs text-gray-500">{{ $agt->peran ?? 'Anggota' }}</p>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-sm text-gray-500 italic">Tidak ada anggota tambahan.</p>
                    @endif
                </div>
            </div>

        </div>
    </div>

    {{-- ACTION FOOTER (Hanya jika Draft/Revisi) --}}
    @if($usulan->status == 'Draft' || $usulan->status == 'Revisi')
        <div class="mt-8 bg-white shadow sm:rounded-lg border border-gray-200 p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Tindakan Pengajuan</h3>
            
            @php
                // Cek kelengkapan data (HANYA FILE, RAB SUDAH DIHAPUS)
                $proposalLengkap = !empty($usulan->file_usulan);
                $bisaSubmit = $proposalLengkap; 
            @endphp

            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-gray-50 p-4 rounded-lg">
                <div>
                    <p class="text-sm text-gray-700 mb-2">Pastikan file proposal sudah benar sebelum mengirim.</p>
                    <div class="flex items-center text-sm">
                        @if($proposalLengkap)
                            <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span class="text-gray-900">File Proposal Siap</span>
                        @else
                            <svg class="w-5 h-5 text-red-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            <span class="text-red-600 font-medium">File Proposal Belum Diunggah</span>
                        @endif
                    </div>
                </div>

                <div class="flex gap-3">
                    {{-- Tombol Edit --}}
                    <a href="{{ route('dosen.usulan.edit', $usulan->id) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        Edit Data
                    </a>

                    {{-- Tombol Submit --}}
                    @if($bisaSubmit)
                        <form action="{{ route('dosen.usulan.submit', $usulan->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin? Data tidak dapat diubah setelah dikirim.');">
                            @csrf
                            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                Kirim Usulan Sekarang
                            </button>
                        </form>
                    @else
                        <button type="button" disabled class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-gray-400 cursor-not-allowed">
                            Lengkapi Data Dulu
                        </button>
                    @endif
                </div>
            </div>
        </div>
    @endif

</div>
@endsection