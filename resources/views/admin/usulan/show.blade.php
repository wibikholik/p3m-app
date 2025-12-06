@extends('admin.layouts.app')

@section('title', 'Verifikasi: ' . Str::limit($usulan->judul, 30))
@section('page-title', 'Verifikasi Usulan')

@section('content')

<style>
    .split-container {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 2rem;
        height: calc(100vh - 200px);
        overflow-y: hidden;
    }
    .file-viewer-wrapper {
        overflow-y: scroll;
        height: 100%;
        padding-right: 1.5rem;
    }
    #pdf-viewer {
        height: 850px;
        overflow-y: scroll;
        border: 1px solid #d1d5db;
        background: #f8fafc;
        padding: 10px;
    }
    canvas {
        width: 100% !important;
        margin-bottom: 10px;
        border: 1px solid #e2e8f0;
        border-radius: 4px;
        box-shadow: 0 1px 2px rgba(0,0,0,0.1);
    }
    .sticky-form {
        position: sticky;
        top: 1rem;
        align-self: flex-start;
        max-height: 100%;
        overflow-y: auto;
    }
    @media (max-width: 1024px) {
        .split-container {
            grid-template-columns: 1fr;
            height: auto;
            overflow-y: visible;
        }
        .file-viewer-wrapper {
            height: auto;
            overflow: visible;
        }
        .sticky-form {
            position: static;
        }
    }
</style>

<div class="max-w-7xl mx-auto mt-8 px-4 sm:px-6 lg:px-8">
    
    {{-- Notifikasi --}}
    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif

    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-4 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">
                Verifikasi Usulan: {{ Str::limit($usulan->judul, 40) }}
            </h1>
        </div>
        <a href="{{ route('admin.usulan.index') }}"
           class="px-4 py-2 bg-white border border-gray-300 rounded text-sm font-semibold hover:bg-gray-50 shadow-sm">
            ← Kembali ke Daftar
        </a>
    </div>

    {{-- Status --}}
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

    <div class="mb-6 border-l-4 rounded-r-lg p-4 shadow-sm {{ $class }}">
        <h3 class="text-sm font-bold capitalize">
            Status Saat Ini: {{ str_replace('_', ' ', ucfirst($usulan->status)) }}
        </h3>
        @if ($usulan->catatan_admin && $usulan->status != 'diajukan')
            <p class="mt-1 text-xs font-medium">Catatan Admin: {{ $usulan->catatan_admin }}</p>
        @endif
    </div>

    {{-- Informasi Usulan --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">

        <div class="lg:col-span-2 bg-white shadow rounded border border-gray-200">
            <div class="px-4 py-5 bg-gray-50 border-b">
                <h3 class="font-medium text-gray-900">Informasi Usulan</h3>
            </div>
            <div class="p-4 space-y-4">
                <div>
                    <label class="text-sm text-gray-500">Judul Usulan</label>
                    <p class="text-lg font-semibold">{{ $usulan->judul }}</p>
                </div>
                <div class="grid grid-cols-3 gap-4 text-sm">
                    <div>
                        <label class="text-gray-500">Program</label>
                        <p class="font-medium">{{ $usulan->pengumuman->judul ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <label class="text-gray-500">Kategori</label>
                        <p class="font-medium text-indigo-700">{{ $usulan->pengumuman->kategori->nama_kategori ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <label class="text-gray-500">Skema</label>
                        <p class="bg-gray-100 px-2 py-1 rounded">{{ $usulan->skema }}</p>
                    </div>
                </div>
                <div>
                    <label class="text-sm text-gray-500">Abstrak</label>
                    <div class="bg-gray-50 border p-3 rounded text-sm max-h-32 overflow-y-auto">
                        {{ $usulan->abstrak }}
                    </div>
                </div>
            </div>
        </div>

        {{-- Pengusul --}}
        <div class="lg:col-span-1">
            <div class="bg-white shadow rounded border border-gray-200 p-4">
                <h3 class="font-medium text-gray-900 mb-2">Pengusul Utama & Tim</h3>
                <p class="font-semibold">{{ $usulan->pengusul->name ?? 'Tidak ditemukan' }}</p>
                <p class="text-xs text-gray-500 mb-3">NIDN: {{ $usulan->pengusul->nidn ?? 'N/A' }}</p>

                <h4 class="font-medium text-sm border-t pt-3 mt-3">Anggota:</h4>
                @forelse ($usulan->anggota as $agt)
                    <p class="text-sm">{{ $agt->name }}</p>
                @empty
                    <p class="text-gray-500 text-sm italic">Tidak ada anggota.</p>
                @endforelse
            </div>
        </div>
    </div>

    {{-- SPLIT SECTION --}}
    <div class="split-container">

        {{-- LEFT: PDF VIEWER --}}
        <div class="file-viewer-wrapper">

            @php
                $filename = $usulan->file_usulan;
                $path = $filename ? asset('storage/usulan_files/' . $filename) : null;
                $isPdf = $filename && strtolower(pathinfo($filename, PATHINFO_EXTENSION)) === 'pdf';
            @endphp

            @if ($filename && $isPdf)
                <div class="bg-white shadow rounded border border-gray-200 mb-6">
                    <div class="px-4 py-3 bg-gray-100 border-b">
                        <h3 class="text-lg font-medium text-gray-900">Preview: {{ $filename }}</h3>
                    </div>

                    <div id="pdf-viewer"></div>

                    <div class="px-4 py-3 border-t bg-gray-50 text-right">
                        <a href="{{ route('admin.usulan.download_file', $filename) }}"
                           class="px-3 py-2 border border-blue-300 rounded text-blue-700 text-sm hover:bg-blue-50 shadow-sm"
                           download>Unduh File Asli</a>
                    </div>
                </div>
            @elseif ($filename)
                <div class="p-4 bg-yellow-50 border text-yellow-600 rounded">
                    File tidak dapat dipreview. Unduh
                    <a class="font-semibold underline" href="{{ route('admin.usulan.download_file', $filename) }}">di sini</a>.
                </div>
            @else
                <div class="p-4 bg-red-50 border text-red-600 rounded">File usulan belum diunggah.</div>
            @endif

        </div>

        {{-- RIGHT: FORM --}}
        <div class="sticky-form">

            @if ($usulan->status == 'diajukan')
                <div class="bg-white shadow rounded border border-red-300 p-6">
                    <h2 class="text-xl font-bold text-red-700 mb-4">
                        TINDAK LANJUT: Verifikasi Administrasi
                    </h2>

                    <form action="{{ route('admin.usulan.verifikasi', $usulan->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <h3 class="text-lg font-semibold mb-4 text-gray-700">Checklist:</h3>

                        @foreach ($masterKelengkapan as $item)
                            @php
                                $checked = isset($ceklistUsulan[$item->id]) && ($ceklistUsulan[$item->id]->status == 1);
                            @endphp

                            <label class="flex items-center mb-2">
                                <input type="checkbox" name="checklist[]" value="{{ $item->id }}"
                                       class="h-4 w-4 text-indigo-600 rounded"
                                       {{ $checked ? 'checked' : '' }}>
                                <span class="ml-2">{{ $item->nama }}</span>
                            </label>
                        @endforeach

                        <label class="block mt-4 text-sm font-medium">Catatan Admin (Jika ditolak)</label>
                        <textarea name="catatan_admin" class="w-full border rounded p-2"></textarea>

                        <div class="flex justify-end mt-4 gap-3 pt-4 border-t">
                            <button name="action" value="reject"
                                class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">
                                Tolak
                            </button>
                            <button name="action" value="approve"
                                class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">
                                Lolos
                            </button>
                        </div>
                    </form>
                </div>
            @else
                <div class="bg-white shadow rounded border p-6 text-center">
                    Proposal sudah diverifikasi.
                    <a class="block text-indigo-600 mt-2" href="{{ route('admin.usulan.index') }}">Kembali</a>
                </div>
            @endif

        </div>
    </div>

</div>

{{-- PDF.js --}}
<script src="{{ asset('pdfjs/build/pdf') }}"></script>

@if ($isPdf)
<script>
    const url = "{{ $path }}";

    const pdfjsLib = window['pdfjsLib'];

    pdfjsLib.GlobalWorkerOptions.workerSrc = "{{ asset('pdfjs/build/pdf.worker') }}";

    pdfjsLib.getDocument(url).promise.then(pdf => {
        const viewer = document.getElementById('pdf-viewer');

        for (let page = 1; page <= pdf.numPages; page++) {
            pdf.getPage(page).then(p => {
                const canvas = document.createElement("canvas");
                viewer.appendChild(canvas);

                const ctx = canvas.getContext("2d");
                const viewport = p.getViewport({ scale: 1.2 });

                canvas.height = viewport.height;
                canvas.width = viewport.width;

                p.render({
                    canvasContext: ctx,
                    viewport: viewport
                });
            });
        }
    });
</script>
@endif


@endsection
