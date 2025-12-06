@extends('dosen.layouts.app')

@section('title', 'Edit Usulan')
@section('page-title', 'Edit Usulan')

@section('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
.select2-container .select2-selection--single {
    height: 42px !important;
    display: flex;
    align-items: center;
    border-color: #d1d5db;
}
.select2-container--default .select2-selection--single .select2-selection__arrow {
    height: 40px !important;
}
</style>
@endsection

@section('content')
<div class="max-w-6xl mx-auto mt-8 px-4 sm:px-6 lg:px-8">

    {{-- Breadcrumb & Header --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 gap-4">
        <div>
            <nav class="flex mb-1 text-sm text-gray-500" aria-label="Breadcrumb">
                <ol class="flex space-x-2">
                    <li><a href="{{ route('dosen.usulan.index') }}" class="hover:text-blue-600">Usulan Saya</a></li>
                    <li>/</li>
                    <li>Edit Usulan</li>
                </ol>
            </nav>
            <h1 class="text-2xl font-bold text-gray-900">Edit Usulan</h1>
        </div>
        <a href="{{ route('dosen.usulan.show', $usulan->id) }}"
           class="px-4 py-2 bg-white border border-gray-300 rounded-md hover:bg-gray-50 text-sm">
           ← Kembali
        </a>
    </div>

    {{-- Error --}}
    @if ($errors->any())
    <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-700 rounded">
        <p class="font-bold mb-2">Terjadi Kesalahan:</p>
        <ul class="list-disc ml-5 text-sm">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    {{-- Form --}}
    <div class="bg-white shadow rounded-lg border p-6">
        <form action="{{ route('dosen.usulan.update', $usulan->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <input type="hidden" name="id_pengumuman" value="{{ $pengumuman->id }}">

            {{-- Judul --}}
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Judul Usulan</label>
                <input type="text" name="judul" value="{{ old('judul', $usulan->judul) }}" required
                       class="mt-1 w-full border rounded-md p-2">
            </div>

            {{-- Skema --}}
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Skema</label>
                <select name="skema" required class="mt-1 w-full border rounded-md p-2">
                    <option value="">-- Pilih Skema --</option>
                    @foreach($skemaList as $key => $label)
                        <option value="{{ $key }}" {{ old('skema', $usulan->skema) == $key ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Abstrak --}}
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Abstrak</label>
                <textarea name="abstrak" rows="5" required
                          class="mt-1 w-full border rounded-md p-2">{{ old('abstrak', $usulan->abstrak) }}</textarea>
            </div>

            {{-- File Usulan --}}
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Upload Proposal (PDF) — opsional</label>
                <input type="file" name="file_usulan" accept="application/pdf" class="mt-1">
                @if($usulan->file_usulan)
                    <p class="mt-2 text-sm text-green-600">
                        File saat ini: 
                        <a href="{{ asset('storage/'.$usulan->file_usulan) }}" target="_blank" class="underline">
                            Lihat
                        </a>
                    </p>
                @endif
            </div>

            {{-- File Revisi --}}
            <div class="mb-4 border-t pt-4 mt-4">
                <label class="block text-sm font-medium text-gray-700">Upload Revisi Proposal (PDF)</label>
                <input type="file" name="file_revisi" accept="application/pdf" class="mt-1">
                @if($usulan->file_revisi)
                    <p class="mt-2 text-sm text-green-600">
                        File revisi saat ini: 
                        <a href="{{ asset('storage/'.$usulan->file_revisi) }}" target="_blank" class="underline">
                            Lihat
                        </a>
                    </p>
                @endif
                <input type="hidden" name="mode_revisi" value="1">
            </div>

            {{-- Submit --}}
            <div class="flex justify-end mt-6">
                <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                    Simpan Perubahan / Upload Revisi
                </button>
            </div>
        </form>
    </div>
</div>

@endsection
