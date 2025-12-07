@extends('dosen.layouts.app')

@section('title', 'Form Laporan Akhir')
@section('page-title', 'Form Laporan Akhir')

@section('content')
<div class="max-w-4xl mx-auto mt-8 p-6 bg-white shadow-lg rounded-lg">
    <h2 class="text-2xl font-bold mb-4 border-b pb-2">
        Laporan Akhir: {{ Str::limit($usulan->judul, 70) }}
    </h2>
    
    @if ($laporanAkhir->exists && $laporanAkhir->status == 'Terkirim')
        <div class="p-4 mb-4 text-sm text-blue-700 bg-blue-100 rounded-lg">
            Status: Laporan Akhir **Telah Terkirim** pada {{ $laporanAkhir->updated_at->format('d M Y') }}. Anda dapat memperbarui data jika diperlukan.
        </div>
    @endif
    
    @if ($catatanReviewer || $catatanAdmin)
        <div class="p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg border border-red-300">
            <h4 class="font-bold">Catatan Perbaikan:</h4>
            @if($catatanReviewer)
                <p><strong>Reviewer:</strong> {{ $catatanReviewer }}</p>
            @endif
            @if($catatanAdmin)
                 <p><strong>Admin:</strong> {{ $catatanAdmin }}</p>
            @endif
        </div>
    @endif

    <form action="{{ route('dosen.laporan_akhir.submit', $usulan->id) }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-4">
            <label for="ringkasan_hasil" class="block text-sm font-medium text-gray-700">Ringkasan Hasil Penelitian/Pengabdian (Maks 1000 Karakter)</label>
            <textarea name="ringkasan_hasil" id="ringkasan_hasil" rows="5" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2" required>{{ old('ringkasan_hasil', $laporanAkhir->ringkasan_hasil ?? '') }}</textarea>
            @error('ringkasan_hasil') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="mb-4">
            <label for="publikasi_target" class="block text-sm font-medium text-gray-700">Target Publikasi / Luaran Capaian</label>
            <input type="text" name="publikasi_target" id="publikasi_target" 
                   value="{{ old('publikasi_target', $laporanAkhir->publikasi_target ?? '') }}"
                   class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2">
            @error('publikasi_target') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="mb-4">
            <label for="file_laporan_akhir" class="block text-sm font-medium text-gray-700">Upload File Laporan Akhir (PDF, Max 10MB)</label>
            <input type="file" name="file_laporan_akhir" id="file_laporan_akhir" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
            @error('file_laporan_akhir') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            
            @if ($laporanAkhir->file_laporan_akhir)
                <p class="mt-2 text-sm text-green-600">
                    File saat ini: <a href="{{ asset('storage/' . $laporanAkhir->file_laporan_akhir) }}" target="_blank" class="underline">Lihat File</a>. Upload file baru untuk mengganti.
                </p>
            @endif
        </div>

        <div class="flex justify-end gap-3 mt-6">
            <a href="{{ route('dosen.laporan_akhir.index') }}" class="px-4 py-2 bg-gray-200 rounded-md hover:bg-gray-300">Batal</a>
            <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                Kirim Laporan Akhir
            </button>
        </div>
    </form>
</div>
@endsection