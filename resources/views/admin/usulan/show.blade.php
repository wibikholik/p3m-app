@extends('admin.layouts.app')

@section('title', 'Verifikasi: ' . Str::limit($usulan->judul, 30))
@section('page-title', 'Verifikasi Usulan')

@section('content')

<style>
    /* Styling khusus untuk Split-View */
    /* Menyesuaikan tinggi agar Form sticky di bagian bawah bekerja optimal */
    .split-container {
        display: grid;
        grid-template-columns: 2fr 1fr; /* Dua pertiga untuk file, satu pertiga untuk form */
        gap: 2rem;
        /* Tinggi total view setelah header, dikurangi padding dan margin luar */
        height: calc(100vh - 200px); 
        overflow-y: hidden;
    }
    .file-viewer-wrapper {
        overflow-y: scroll;
        height: 100%;
        padding-right: 1.5rem; /* Memberi ruang untuk scrollbar */
    }
    .sticky-form {
        position: sticky;
        top: 1rem; /* Jarak dari atas container */
        align-self: flex-start; /* Memastikan form menempel di atas */
        max-height: 100%;
        overflow-y: auto; /* Memungkinkan form itu sendiri di-scroll jika terlalu panjang */
    }
    /* Untuk tampilan mobile, kembali ke susunan tumpuk */
    @media (max-width: 1024px) {
        .split-container {
            grid-template-columns: 1fr;
            height: auto;
            overflow-y: visible;
        }
        .file-viewer-wrapper {
            overflow-y: visible;
            height: auto;
            padding-right: 0;
        }
        .sticky-form {
            position: static;
        }
    }
</style>

<div class="max-w-7xl mx-auto mt-8 px-4 sm:px-6 lg:px-8">
    
    {{-- Notifikasi Session (Success/Error) --}}
    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif
    @if (session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif

    {{-- Header & Status Bar --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-4 gap-4">
        <div>
            <nav class="flex mb-1" aria-label="Breadcrumb">
                <ol class="flex items-center space-x-2 text-sm text-gray-500">
                    <li><a href="{{ route('admin.usulan.index') }}" class="hover:text-blue-600">Manajemen Usulan</a></li>
                    <li><span class="text-gray-400">/</span></li>
                    <li class="text-gray-900 font-medium" aria-current="page">Verifikasi</li>
                </ol>
            </nav>
            <h1 class="text-2xl font-bold text-gray-900">Verifikasi Usulan: {{ Str::limit($usulan->judul, 40) }}</h1>
        </div>

        <a href="{{ route('admin.usulan.index') }}"
           class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md text-sm font-semibold text-gray-700 hover:bg-gray-50 shadow-sm">
             &larr; Kembali ke Daftar
        </a>
    </div>
    
    {{-- Status Bar --}}
    <div class="mb-6 border-l-4 rounded-r-lg p-4 shadow-sm
        @php
            $status = strtolower($usulan->status);
            $statusClasses = [
                'diajukan' => 'border-blue-400 bg-blue-50 text-blue-700',
                'lolos_administrasi' => 'border-indigo-400 bg-indigo-50 text-indigo-700',
                'diterima' => 'border-green-400 bg-green-50 text-green-700',
                'ditolak' => 'border-red-400 bg-red-50 text-red-700',
                'in_review' => 'border-purple-400 bg-purple-50 text-purple-700',
                'revisi' => 'border-orange-400 bg-orange-50 text-orange-700',
            ];
            $class = $statusClasses[$status] ?? 'border-gray-400 bg-gray-50 text-gray-700';
        @endphp
        {{ $class }}">
        <h3 class="text-sm font-bold capitalize">
            Status Saat Ini: {{ str_replace('_', ' ', ucfirst($usulan->status)) }}
        </h3>
        @if ($usulan->catatan_admin && $usulan->status != 'diajukan')
            <p class="mt-1 text-xs font-medium">Catatan Admin: {{ $usulan->catatan_admin }}</p>
        @endif
    </div>

    
    {{-- LEVEL ATAS: INFORMASI STATIS (Informasi Usulan & Pengusul) --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        
        {{-- KIRI: Informasi Utama (2/3) --}}
        <div class="lg:col-span-2">
            <div class="bg-white shadow sm:rounded-lg border border-gray-200 h-full">
                <div class="px-4 py-5 bg-gray-50 border-b">
                    <h3 class="text-lg font-medium text-gray-900">Informasi Usulan</h3>
                </div>
                <div class="px-4 py-5 space-y-4">
                    <div>
                        <label class="text-sm font-medium text-gray-500">Judul Usulan</label>
                        <p class="mt-1 text-lg font-semibold">{{ $usulan->judul }}</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="text-sm font-medium text-gray-500">Program</label>
                            <p class="mt-1 text-sm font-medium text-gray-900">{{ $usulan->pengumuman->judul ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500">Kategori</label>
                            <p class="mt-1 text-sm text-indigo-700 font-medium">{{ $usulan->pengumuman->kategori->nama_kategori ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500">Skema</label>
                            <p class="mt-1 px-2 py-1 bg-gray-100 inline-block rounded text-sm">{{ $usulan->skema }}</p>
                        </div>
                    </div>
                    
                    <div>
                        <label class="text-sm font-medium text-gray-500">Abstrak</label>
                        <div class="mt-1 bg-gray-50 border p-3 rounded text-sm leading-relaxed max-h-32 overflow-y-auto">
                            {{ $usulan->abstrak }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        {{-- KANAN: Pengusul & Anggota (1/3) --}}
        <div class="lg:col-span-1 space-y-3">
            {{-- Pengusul --}}
            <div class="bg-white shadow sm:rounded-lg border border-gray-200">
                <div class="px-4 py-4 bg-gray-50 border-b">
                    <h3 class="text-lg font-medium text-gray-900">Pengusul Utama & Tim</h3>
                </div>
                <div class="p-4">
                    <p class="text-sm font-medium text-gray-700 font-bold">
                        {{ $usulan->pengusul->name ?? 'Tidak ditemukan' }}
                    </p>
                    <p class="text-xs text-gray-500 mt-1 mb-3">NIDN: {{ $usulan->pengusul->nidn ?? 'N/A' }} (Ketua)</p>

                    <h4 class="text-sm font-semibold mt-4 border-t pt-3">Anggota Tim:</h4>
                    @if(isset($usulan->anggota) && $usulan->anggota->count())
                        <ul class="divide-y">
                            @foreach($usulan->anggota as $agt)
                                <li class="py-2">
                                    <p class="text-sm font-medium">{{ $agt->name ?? $agt->nama }}</p>
                                    <p class="text-xs text-gray-500">{{ $agt->jabatan ?? 'Anggota' }}</p>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-sm text-gray-500 italic">Tidak ada anggota tim tambahan.</p>
                    @endif
                </div>
            </div>
            @if($usulan->reviewers->count())
                <div class="bg-gray-50 shadow sm:rounded-lg border border-gray-200 p-4">
                    <h4 class="text-md font-semibold mb-2">Reviewer yang Ditugaskan</h4>
                    <div class="space-y-2">
                        @foreach($usulan->reviewers as $rev)
                            <div class="border-b last:border-b-0 pb-2">
                                <p class="font-medium text-gray-800">{{ $rev->name }}</p>
                                <p class="text-xs text-gray-500">
                                    Status: {{ ucfirst($rev->pivot->status) }}
                                    @if($rev->pivot->deadline)
                                        | Deadline: {{ \Carbon\Carbon::parse($rev->pivot->deadline)->format('d M Y') }}
                                    @endif
                                </p>
                                @if($rev->pivot->catatan_assign)
                                    <p class="text-xs text-gray-600">Catatan Assign: {{ $rev->pivot->catatan_assign }}</p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

    </div>
    
    {{-- LEVEL BAWAH: START SPLIT-VIEW AKSI --}}
    <div class="split-container">
        
        {{-- LEFT: FILE VIEWER (Scrollable) --}}
        <div class="file-viewer-wrapper">
            
            {{-- Embed File Proposal --}}
            @php
                // Mengambil nama file dari kolom yang benar
                $filename = $usulan->file_usulan ?? null;
                // Cek apakah file ada dan ekstensinya PDF
                $isPdf = $filename && in_array(strtolower(pathinfo($filename, PATHINFO_EXTENSION)), ['pdf']);
            @endphp

            @if($filename && $isPdf)
                <div class="bg-white shadow sm:rounded-lg border border-gray-200 mb-6" style="min-height: 800px;">
                    <div class="px-4 py-3 bg-gray-100 border-b">
                        <h3 class="text-lg font-medium text-gray-900">Preview Proposal: {{ $filename }}</h3>
                    </div>
                    
                    {{-- Menggunakan <iframe> untuk preview PDF (lebih stabil daripada <embed>) --}}
                    <iframe 
    src="https://docs.google.com/gview?url={{ urlencode(asset('storage/usulan/' . $filename)) }}&embedded=true" 
    style="width:100%; height:850px;" 
    frameborder="0"
    title="File Proposal Preview">
</iframe>


                    {{-- Link Download Manual di Bawah Preview --}}
                    <div class="px-4 py-3 border-t bg-gray-50 text-right">
                        <a href="{{ route('admin.usulan.download_file', $filename) }}"
                            class="px-3 py-2 border border-blue-300 rounded-md text-blue-700 text-sm hover:bg-blue-50 shadow-sm"
                            download>
                            Unduh File Asli
                        </a>
                    </div>
                </div>
            @elseif($filename)
                {{-- File ditemukan tapi tidak bisa di-preview --}}
                <div class="p-4 bg-yellow-50 border border-yellow-200 text-yellow-600 text-sm text-center rounded mb-6">
                    File ditemukan ({{ $filename }}), tetapi tidak dapat dipreview secara otomatis (hanya PDF yang didukung). Silakan <a href="{{ route('admin.usulan.download_file', $filename) }}" class="font-semibold underline">Unduh File Asli</a>.
                </div>
            @else
                {{-- File tidak ditemukan --}}
                <div class="p-4 bg-red-50 border border-red-200 text-red-600 text-sm text-center rounded mb-6">
                    File proposal belum diunggah.
                </div>
            @endif
        </div>

        {{-- RIGHT: STICKY VERIFICATION FORM --}}
<div class="sticky-form">
    @if ($usulan->status == 'diajukan')
        <div class="bg-white shadow overflow-hidden sm:rounded-lg border border-red-300 p-6">
            <h2 class="text-xl font-bold text-red-700 border-b pb-3 mb-5">
                TINDAK LANJUT: Verifikasi Administrasi
            </h2>
            
            <form action="{{ route('admin.usulan.verifikasi', $usulan->id) }}" method="POST">
                @csrf
                @method('PUT')

                {{-- Checklist Verifikasi --}}
                <div class="space-y-4 mb-6">
                    <h3 class="text-lg font-semibold text-gray-700">Checklist Kelengkapan Dokumen:</h3>

                    @foreach ($masterKelengkapan as $item)
                        @php
                            $checked = isset($ceklistUsulan[$item->id]) && ($ceklistUsulan[$item->id]->status == 1 || $ceklistUsulan[$item->id]->status === 'lengkap');
                        @endphp


                        <div class="relative flex items-start">
                            <div class="flex items-center h-5">
                                <input  
                                    type="checkbox" 
                                    id="check_{{ $item->id }}" 
                                    name="checklist[]" 
                                    value="{{ $item->id }}" 
                                    class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded"
                                    {{ $checked ? 'checked' : '' }}
                                >
                            </div>
                            <div class="ml-3 text-sm">
                                <label for="check_{{ $item->id }}" class="font-medium text-gray-700">
                                    {{ $item->nama }}
                                </label>
                                @if ($item->deskripsi)
                                    <p class="text-gray-500 text-xs">{{ $item->deskripsi }}</p>
                                @endif
                            </div>
                        </div>
                    @endforeach

                </div>
                
                {{-- Field Catatan --}}
                <div class="mb-6">
                    <label for="catatan_admin" class="block text-sm font-medium text-gray-700">
                        Catatan/Alasan Penolakan (Wajib diisi jika Tolak)
                    </label>
                    <textarea id="catatan_admin" name="catatan_admin" rows="3"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500"></textarea>
                </div>

                {{-- Tombol Aksi --}}
                <div class="flex justify-end space-x-3 pt-4 border-t">
                    {{-- Tombol Tolak --}}
                    <button type="submit" name="action" value="reject"
                        class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md 
                               text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition">
                        Tolak Administrasi
                    </button>

                    {{-- Tombol Lolos --}}
                    <button type="submit" name="action" value="approve"
                        class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md 
                               text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition">
                        Lolos Administrasi
                    </button>
                </div>
            </form>
        </div>
    @else
        {{-- Jika status bukan diajukan --}}
        <div class="bg-white shadow overflow-hidden sm:rounded-lg border border-gray-200 p-6 text-center">
            <p class="text-lg font-semibold text-gray-700">Proposal ini sudah diverifikasi Administrasi.</p>
            <p class="text-sm text-gray-500 mt-2">
                Anda dapat kembali ke 
                <a href="{{ route('admin.usulan.index') }}" class="text-indigo-600 hover:text-indigo-800">
                    Daftar Usulan
                </a>.
            </p>
        </div>
    @endif
</div>


</div>
@endsection
