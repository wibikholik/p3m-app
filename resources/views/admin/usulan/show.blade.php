@extends('admin.layouts.app')

@section('title', 'Detail Usulan')
@section('page-title', 'Detail Usulan')

@section('content')
<div class="max-w-5xl mx-auto mt-8 px-4 sm:px-6 lg:px-8">

    {{-- Breadcrumb & Back --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 gap-4">
        <div>
            <nav class="flex mb-1" aria-label="Breadcrumb">
                <ol class="flex items-center space-x-2 text-sm text-gray-500">
                    <li><a href="{{ route('admin.usulan.index') }}" class="hover:text-blue-600">Manajemen Usulan</a></li>
                    <li><span class="text-gray-400">/</span></li>
                    <li class="text-gray-900 font-medium" aria-current="page">Detail</li>
                </ol>
            </nav>
            <h1 class="text-2xl font-bold text-gray-900">Detail Usulan</h1>
        </div>

        <a href="{{ route('admin.usulan.index') }}"
           class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md text-sm font-semibold text-gray-700 hover:bg-gray-50 shadow-sm">
            &larr; Kembali
        </a>
    </div>

    {{-- Status --}}
    <div class="mb-6 border-l-4 rounded-r-lg p-4 shadow-sm
        @if($usulan->status == 'diajukan') border-blue-400 bg-blue-50
        @elseif($usulan->status == 'diterima') border-green-400 bg-green-50
        @elseif($usulan->status == 'ditolak') border-red-400 bg-red-50
        @else border-gray-400 bg-gray-50
        @endif">

        <h3 class="text-sm font-medium capitalize">
            Status: {{ $usulan->status }}
        </h3>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- LEFT --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Informasi Utama --}}
            <div class="bg-white shadow sm:rounded-lg border border-gray-200">
                <div class="px-4 py-5 bg-gray-50 border-b">
                    <h3 class="text-lg font-medium text-gray-900">Informasi Usulan</h3>
                </div>
                <div class="px-4 py-5 space-y-4">
                    <div>
                        <label class="text-sm font-medium text-gray-500">Judul Usulan</label>
                        <p class="mt-1 text-lg font-semibold">{{ $usulan->judul }}</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="text-sm font-medium text-gray-500">Skema</label>
                            <p class="mt-1 px-2 py-1 bg-gray-100 inline-block rounded text-sm">
                                {{ $usulan->skema }}
                            </p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500">Tahun Pelaksanaan</label>
                            <p class="mt-1 text-sm">{{ $usulan->created_at->year }}</p>
                        </div>
                    </div>

                    <div>
                        <label class="text-sm font-medium text-gray-500">Abstrak / Deskripsi</label>
                        <div class="mt-1 bg-gray-50 border p-3 rounded text-sm leading-relaxed">
                            {{ $usulan->abstrak }}
                        </div>
                    </div>
                </div>
            </div>

            {{-- File Proposal --}}
            <div class="bg-white shadow sm:rounded-lg border border-gray-200">
                <div class="px-4 py-5 border-b">
                    <h3 class="text-lg font-medium text-gray-900">File Proposal</h3>
                </div>

                <div class="px-4 py-5">
                    @if($usulan->file_usulan)
                        <div class="flex items-center justify-between p-4 bg-blue-50 rounded-lg border">
                            <div class="flex items-center">
                                <svg class="w-8 h-8 text-red-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414..."
                                    />
                                </svg>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">Proposal.pdf</p>
                                    <p class="text-xs text-gray-500">Klik untuk melihat</p>
                                </div>
                            </div>

                            <a href="{{ asset('storage/'.$usulan->file_usulan) }}"
                               target="_blank"
                               class="px-3 py-2 border border-blue-300 rounded-md text-blue-700 text-sm hover:bg-blue-50">
                                Lihat File
                            </a>
                        </div>
                    @else
                        <div class="p-4 bg-red-50 border border-red-200 text-red-600 text-sm text-center rounded">
                            File belum diunggah.
                        </div>
                    @endif
                </div>
            </div>

        </div>

        {{-- RIGHT --}}
        <div class="space-y-6">

            {{-- Pengusul --}}
            <div class="bg-white shadow sm:rounded-lg border border-gray-200">
                <div class="px-4 py-4 bg-gray-50 border-b">
                    <h3 class="text-lg font-medium text-gray-900">Pengusul</h3>
                </div>

                <div class="p-4">
                    <p class="text-sm font-medium text-gray-700">
                        {{ $usulan->pengusul->name ?? 'Tidak ditemukan' }}
                    </p>
                    <p class="text-xs text-gray-500 mt-1">Dosen Pengusul</p>
                </div>
            </div>

            {{-- Anggota --}}
            <div class="bg-white shadow sm:rounded-lg border border-gray-200">
                <div class="px-4 py-4 bg-gray-50 border-b">
                    <h3 class="text-lg font-medium text-gray-900">Anggota</h3>
                </div>
                <div class="p-4">
                    @if($usulan->anggota && $usulan->anggota->count())
                        <ul class="divide-y">
                            @foreach($usulan->anggota as $agt)
                                <li class="py-3">
                                    <p class="text-sm font-medium">{{ $agt->nama }}</p>
                                    <p class="text-xs text-gray-500">{{ $agt->jabatan ?? 'Anggota' }}</p>
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

</div>
@endsection
