@extends('dosen.layouts.app')

@section('title', 'Buat Laporan Kemajuan')
@section('page-title', 'Buat Laporan Kemajuan')

@section('content')

<div class="max-w-4xl mx-auto mt-10 bg-white p-8 shadow-lg rounded-xl border border-gray-200">

    <h2 class="text-2xl font-semibold text-gray-800 mb-6 flex items-center gap-2">
        <span class="text-blue-600">Laporan Kemajuan</span>
        — {{ Str::limit($usulan->judul, 60) }}
    </h2>

    <form action="{{ route('dosen.laporan-kemajuan.store', $usulan->id) }}" 
          method="POST" enctype="multipart/form-data">
        @csrf

        {{-- Ringkasan --}}
        <div class="mb-5">
            <label class="block text-sm font-medium text-gray-700 mb-1">Ringkasan Kemajuan *</label>
            <textarea name="ringkasan_kemajuan" rows="5"
                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                required>{{ old('ringkasan_kemajuan') }}</textarea>
            @error('ringkasan_kemajuan')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Kendala --}}
        <div class="mb-5">
            <label class="block text-sm font-medium text-gray-700 mb-1">Kendala</label>
            <textarea name="kendala" rows="4"
                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">{{ old('kendala') }}</textarea>
            @error('kendala')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Persentase --}}
        <div class="mb-5">
            <label class="block text-sm font-medium text-gray-700 mb-1">Persentase Kemajuan (%) *</label>
            <input type="number" name="persentase" min="1" max="100"
                class="mt-1 block w-32 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                value="{{ old('persentase') }}" required>
            @error('persentase')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- File --}}
        <div class="mb-5">
            <label class="block text-sm font-medium text-gray-700 mb-1">Upload Laporan (PDF)*</label>
            <input type="file" name="file_laporan"
                class="mt-1 block w-full text-sm border-gray-300 rounded-md shadow-sm cursor-pointer focus:ring-blue-500 focus:border-blue-500"
                required>
            @error('file_laporan')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Buttons --}}
        <div class="flex justify-end mt-8">
            <a href="{{ route('dosen.laporan-kemajuan.index') }}"
                class="px-5 py-2.5 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition mr-3">
                Batal
            </a>

            <button type="submit"
                class="px-6 py-2.5 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition shadow-sm">
                Simpan Laporan
            </button>
        </div>

    </form>
</div>

@endsection
