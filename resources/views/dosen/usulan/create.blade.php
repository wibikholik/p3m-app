@extends('dosen.layouts.app')

@section('title', 'Buat Laporan Kemajuan')
@section('page-title', 'Buat Laporan Kemajuan')

@section('content')

<div class="max-w-4xl mx-auto mt-10 bg-white p-8 shadow-lg rounded-xl border">

    <h2 class="text-xl font-bold text-gray-800 mb-6">
        Laporan Kemajuan — {{ Str::limit($usulan->judul, 50) }}
    </h2>

    <form action="{{ route('dosen.laporan-kemajuan.store', $usulan->id) }}" 
          method="POST" enctype="multipart/form-data">

        @csrf

        {{-- Ringkasan --}}
        <div class="mb-5">
            <label class="block text-sm font-semibold text-gray-700 mb-1">
                Ringkasan Kemajuan *
            </label>
            <textarea 
                name="ringkasan_kemajuan" 
                rows="5"
                class="w-full border border-gray-300 rounded-lg p-3 focus:ring focus:ring-blue-200"
                required>{{ old('ringkasan_kemajuan') }}</textarea>

            @error('ringkasan_kemajuan')
                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Kendala --}}
        <div class="mb-5">
            <label class="block text-sm font-semibold text-gray-700 mb-1">
                Kendala
            </label>
            <textarea 
                name="kendala" 
                rows="4"
                class="w-full border border-gray-300 rounded-lg p-3 focus:ring focus:ring-blue-200"
            >{{ old('kendala') }}</textarea>

            @error('kendala')
                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Persentase --}}
        <div class="mb-5">
            <label class="block text-sm font-semibold text-gray-700 mb-1">
                Persentase Kemajuan (%) *
            </label>
            <input 
                type="number" 
                name="persentase"
                min="1" 
                max="100"
                value="{{ old('persentase') }}"
                class="w-32 border border-gray-300 rounded-lg p-2 focus:ring focus:ring-blue-200"
                required
            >

            @error('persentase')
                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- File --}}
        <div class="mb-5">
            <label class="block text-sm font-semibold text-gray-700 mb-1">
                Upload Laporan (PDF) *
            </label>
            <input 
                type="file" 
                name="file_laporan"
                class="w-full border border-gray-300 rounded-lg p-2 bg-white focus:ring focus:ring-blue-200"
                required
            >

            @error('file_laporan')
                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Submit --}}
        <div class="flex justify-end mt-8">
            <a href="{{ route('dosen.laporan-kemajuan.index') }}"
                class="px-4 py-2 bg-gray-200 rounded-lg mr-3 hover:bg-gray-300">
                Batal
            </a>

            <button type="submit"
                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 shadow">
                Simpan Laporan
            </button>
        </div>

    </form>
</div>

@endsection
