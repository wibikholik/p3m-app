@extends('reviewer.layouts.app')

@section('title', 'Nilai Laporan Kemajuan')
@section('page-title', 'Nilai Laporan Kemajuan')

@section('content')
<div class="max-w-7xl mx-auto mt-10 px-4 sm:px-6 lg:px-8 grid grid-cols-1 lg:grid-cols-2 gap-6">

    {{-- Kolom Kiri: Preview File PDF --}}
    <div class="bg-white shadow-md rounded-lg p-4 h-[90vh] flex flex-col">
        <h3 class="text-lg font-semibold text-gray-800 mb-3 border-b pb-2">📂 File Laporan (Preview)</h3>
        
        @if($laporan->file_laporan)
            {{-- Menggunakan iframe untuk menampilkan PDF. Pastikan asset('storage/') benar. --}}
            <iframe src="{{ asset('storage/' . $laporan->file_laporan) }}" class="w-full flex-grow" frameborder="0"></iframe>
            <a href="{{ asset('storage/'.$laporan->file_laporan) }}" target="_blank" class="mt-3 text-sm text-blue-600 hover:underline block text-center">
                Unduh / Buka di Tab Baru
            </a>
        @else
            <div class="text-center text-gray-500 py-10 flex-grow flex items-center justify-center">
                File laporan belum tersedia.
            </div>
        @endif
    </div>
    
    {{-- Kolom Kanan: Detail & Form Penilaian --}}
    <div class="bg-white shadow-md rounded-lg p-6 h-fit">
        
        {{-- Display Header and Current Status --}}
        <div class="mb-6 border-b pb-4">
            <h2 class="text-xl font-bold text-gray-800 mb-2">
                {{ Str::limit($laporan->usulan->judul, 60) }}
            </h2>

            <p class="text-sm text-gray-600 mb-1">
                Dosen: {{ $laporan->usulan->pengusul->name ?? 'N/A' }} | Persentase: {{ $laporan->persentase }}%
            </p>

            @if($laporan->status != 'Terkirim')
            <div class="mt-3 p-3 bg-yellow-100 border border-yellow-300 rounded">
                <strong>Status Laporan:</strong> {{ $laporan->status }} (Nilai Anda: {{ $laporan->nilai_reviewer ?? '-' }})
            </div>
            @endif
        </div>

        {{-- Content --}}
        <div class="mb-4">
            <p class="text-sm text-gray-700">
                <strong>Ringkasan Kemajuan:</strong><br>{{ $laporan->ringkasan_kemajuan }}
            </p>
        </div>

        @if($laporan->kendala)
        <div class="mb-4">
            <p class="text-sm text-gray-700">
                <strong>Kendala:</strong><br>{{ $laporan->kendala }}
            </p>
        </div>
        @endif
        
        {{-- FORM PENILAIAN: Tampilkan hanya jika belum selesai dinilai atau statusnya 'Perbaikan' --}}
        @if(!$is_reviewed || $laporan->status == 'Perbaikan')
        <hr class="my-4">
        <h3 class="text-lg font-semibold text-green-700 mb-3">Form Penilaian</h3>
        
        <form action="{{ route('reviewer.laporan-kemajuan.nilai', $laporan->id) }}" method="POST">
            @csrf

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Nilai (0-100)</label>
                <input type="number" name="nilai" min="0" max="100" 
                       value="{{ old('nilai', $laporan->nilai_reviewer ?? '') }}"
                       class="mt-1 block w-32 border rounded-md p-2" required>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Status Review</label>
                <select name="status_review" class="mt-1 w-48 border rounded-md p-2" required>
                    <option value="Disetujui" {{ old('status_review', $laporan->status) == 'Disetujui' ? 'selected' : '' }}>Disetujui</option>
                    <option value="Ditolak" {{ old('status_review', $laporan->status) == 'Ditolak' ? 'selected' : '' }}>Ditolak</option>
                    <option value="Perbaikan" {{ old('status_review', $laporan->status) == 'Perbaikan' ? 'selected' : '' }}>Perbaikan</option>
                </select>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Catatan / Revisi (opsional)</label>
                <textarea name="catatan_reviewer" rows="4" class="mt-1 block w-full border rounded-md p-2">{{ old('catatan_reviewer', $laporan->catatan_reviewer ?? '') }}</textarea>
                @error('catatan_reviewer') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="flex justify-end gap-3 mt-5">
                <a href="{{ route('reviewer.laporan-kemajuan.index') }}" class="px-4 py-2 bg-gray-200 rounded-md hover:bg-gray-300">Kembali</a>
                <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">Kirim Penilaian</button>
            </div>
        </form>
        @else
        {{-- Tampilan setelah dinilai --}}
        <div class="mt-6 p-4 bg-green-50 border border-green-300 rounded">
            <h3 class="font-semibold text-green-700">Penilaian Anda</h3>
            <p class="text-sm text-green-600">Status Akhir: **{{ $laporan->status }}**.</p>
            <p class="text-sm text-green-600">Nilai: **{{ $laporan->nilai_reviewer }}**. Catatan: *{{ $laporan->catatan_reviewer ?? 'Tidak ada catatan' }}*</p>
        </div>
        <div class="flex justify-end mt-4">
             <a href="{{ route('reviewer.laporan-kemajuan.index') }}" class="px-4 py-2 bg-gray-200 rounded-md hover:bg-gray-300">Kembali ke Daftar</a>
        </div>
        @endif
    </div>
</div>
@endsection