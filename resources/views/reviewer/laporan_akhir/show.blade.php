@extends('reviewer.layouts.app')

@section('title', 'Nilai Laporan Akhir')
@section('page-title', 'Nilai Laporan Akhir')

@section('content')
<div class="max-w-7xl mx-auto mt-8 px-4 sm:px-6 lg:px-8 grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- Kolom Kiri (lg:col-span-2): Preview File PDF --}}
    <div class="lg:col-span-2 bg-white shadow-md rounded-lg p-4 h-[90vh] flex flex-col">
        <h3 class="text-lg font-semibold text-gray-800 mb-3 border-b pb-2">📂 File Laporan Akhir (Preview)</h3>
        
        @if($laporanAkhir->file_laporan_akhir)
            {{-- Menggunakan iframe untuk menampilkan PDF. Pastikan asset('storage/') benar. --}}
            <iframe src="{{ asset('storage/' . $laporanAkhir->file_laporan_akhir) }}" class="w-full flex-grow" frameborder="0"></iframe>
            <a href="{{ asset('storage/'.$laporanAkhir->file_laporan_akhir) }}" target="_blank" class="mt-3 text-sm text-blue-600 hover:underline block text-center">
                Unduh / Buka di Tab Baru
            </a>
        @else
            <div class="text-center text-gray-500 py-10 flex-grow flex items-center justify-center">
                File laporan akhir belum tersedia.
            </div>
        @endif
    </div>
    
    {{-- Kolom Kanan (lg:col-span-1): Detail & Form Penilaian --}}
    <div class="lg:col-span-1">
        <div class="bg-white shadow-md rounded-lg p-6 h-fit">
            
            {{-- Display Header and Current Status --}}
            <div class="mb-6 border-b pb-4">
                <h2 class="text-xl font-bold text-gray-800 mb-2">
                    Laporan Akhir: {{ Str::limit($laporanAkhir->usulan->judul, 60) }}
                </h2>

                <p class="text-sm text-gray-600 mb-1">
                    Dosen: {{ $laporanAkhir->usulan->pengusul->name ?? 'N/A' }}
                </p>

                @if($laporanAkhir->status != 'Terkirim')
                <div class="mt-3 p-3 bg-yellow-100 border border-yellow-300 rounded">
                    <strong>Status Laporan:</strong> {{ $laporanAkhir->status }} (Nilai Anda: {{ $laporanAkhir->nilai_reviewer ?? '-' }})
                </div>
                @endif
            </div>

            {{-- Ringkasan dan Luaran --}}
            <div class="mb-4">
                <p class="text-sm text-gray-700">
                    <strong>Ringkasan Hasil:</strong><br>{{ $laporanAkhir->ringkasan_hasil }}
                </p>
            </div>
            
            @if($laporanAkhir->publikasi_target)
            <div class="mb-4">
                <p class="text-sm text-gray-700">
                    <strong>Target Publikasi/Luaran:</strong><br>{{ $laporanAkhir->publikasi_target }}
                </p>
            </div>
            @endif

            {{-- FORM PENILAIAN: Tampilkan hanya jika belum selesai dinilai atau statusnya 'Perbaikan' --}}
            @if(!$is_reviewed || $laporanAkhir->status == 'Perbaikan')
            <hr class="my-4">
            <h3 class="text-lg font-semibold text-green-700 mb-3">Form Penilaian</h3>
            
            <form action="{{ route('reviewer.laporan_akhir.nilai', $laporanAkhir->id) }}" method="POST">
                @csrf

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Nilai (0-100)</label>
                    <input type="number" name="nilai" min="0" max="100" 
                           value="{{ old('nilai', $laporanAkhir->nilai_reviewer ?? '') }}"
                           class="mt-1 block w-32 border rounded-md p-2" required>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Status Review</label>
                    <select name="status_review" class="mt-1 w-full border rounded-md p-2" required>
                        <option value="Disetujui" {{ old('status_review', $laporanAkhir->status) == 'Disetujui' ? 'selected' : '' }}>Disetujui</option>
                        <option value="Ditolak" {{ old('status_review', $laporanAkhir->status) == 'Ditolak' ? 'selected' : '' }}>Ditolak</option>
                        <option value="Perbaikan" {{ old('status_review', $laporanAkhir->status) == 'Perbaikan' ? 'selected' : '' }}>Perbaikan</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Catatan / Revisi (opsional)</label>
                    <textarea name="catatan_reviewer" rows="4" class="mt-1 block w-full border rounded-md p-2">{{ old('catatan_reviewer', $laporanAkhir->catatan_reviewer ?? '') }}</textarea>
                    @error('catatan_reviewer') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="flex justify-end gap-3 mt-5">
                    <a href="{{ route('reviewer.laporan_akhir.index') }}" class="px-4 py-2 bg-gray-200 rounded-md hover:bg-gray-300">Kembali</a>
                    <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">Kirim Penilaian</button>
                </div>
            </form>
            @else
            {{-- Tampilan setelah dinilai --}}
            <div class="mt-6 p-4 bg-green-50 border border-green-300 rounded">
                <h3 class="font-semibold text-green-700">Penilaian Sudah Selesai</h3>
                <p class="text-sm text-green-600">Laporan ini sudah Anda nilai, dan statusnya: **{{ $laporanAkhir->status }}**.</p>
                <p class="text-sm text-green-600">Nilai: **{{ $laporanAkhir->nilai_reviewer }}**. Catatan: *{{ $laporanAkhir->catatan_reviewer ?? 'Tidak ada catatan' }}*</p>
            </div>
            <div class="flex justify-end mt-4">
                 <a href="{{ route('reviewer.laporan_akhir.index') }}" class="px-4 py-2 bg-gray-200 rounded-md hover:bg-gray-300">Kembali ke Daftar</a>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection