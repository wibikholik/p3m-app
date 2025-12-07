@extends('admin.layouts.app')

@section('title', 'Monev Laporan Kemajuan')
@section('page-title', 'Monev Laporan Kemajuan')

@section('content')
<div class="max-w-7xl mx-auto mt-8 px-4 sm:px-6 lg:px-8 grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- Kolom Kiri: Detail Laporan dan Preview File --}}
    <div class="lg:col-span-2 bg-white shadow rounded-lg p-6 h-[90vh] flex flex-col">
        <h2 class="text-2xl font-bold mb-4 text-gray-800 border-b pb-2">
            Laporan: {{ Str::limit($laporan->usulan->judul, 70) }}
        </h2>

        <div class="text-sm text-gray-700 mb-4">
            <p><strong>Dosen Pengusul:</strong> {{ $laporan->usulan->pengusul->name ?? 'N/A' }}</p>
            <p><strong>Dikirim pada:</strong> {{ $laporan->created_at->format('d M Y H:i') }}</p>
            <p><strong>Status Global:</strong> 
                <span class="font-semibold text-{{ $is_reviewed ? 'green' : 'red' }}-600">{{ $laporan->status }}</span>
            </p>
        </div>
        
        <div class="flex-grow flex flex-col">
            <h3 class="text-lg font-semibold mt-4 mb-2">File Laporan (Preview)</h3>
            @if($laporan->file_laporan)
                {{-- Preview menggunakan iframe --}}
                <iframe src="{{ asset('storage/' . $laporan->file_laporan) }}" class="w-full flex-grow min-h-[300px]" frameborder="0"></iframe>
                <a href="{{ asset('storage/'.$laporan->file_laporan) }}" target="_blank" class="mt-2 text-blue-600 hover:underline block text-center">
                    Unduh Laporan PDF
                </a>
            @else
                <div class="text-gray-500 py-10 flex-grow flex items-center justify-center border rounded">
                    File tidak ditemukan.
                </div>
            @endif
        </div>
    </div>

    {{-- Kolom Kanan: Ringkasan & Keputusan Admin --}}
    <div class="lg:col-span-1">
        
        {{-- Ringkasan Laporan --}}
        <div class="bg-white shadow rounded-lg p-6 mb-6">
            <h3 class="text-xl font-bold mb-3 border-b pb-2">Ringkasan Laporan</h3>
            <p class="text-sm mb-4"><strong>Persentase Kemajuan:</strong> {{ $laporan->persentase }}%</p>
            <h4 class="font-semibold text-sm">Ringkasan:</h4>
            <p class="text-sm mb-3 italic">{{ $laporan->ringkasan_kemajuan }}</p>
            @if($laporan->kendala)
                <h4 class="font-semibold text-sm">Kendala:</h4>
                <p class="text-sm italic">{{ $laporan->kendala }}</p>
            @endif
        </div>
        
        {{-- Hasil Review --}}
        <div class="bg-gray-50 shadow rounded-lg p-6 mb-6 border border-gray-200">
            <h3 class="text-xl font-bold mb-3">Hasil Review</h3>
            
            @if($is_reviewed)
                <div class="mb-3">
                    <p class="text-sm"><strong>Reviewer:</strong> {{ $laporan->reviewer->name ?? 'N/A' }}</p>
                    <p class="text-lg font-bold text-green-700">Nilai: {{ $laporan->nilai_reviewer ?? '—' }} / 100</p>
                    <p class="text-sm"><strong>Keputusan:</strong> {{ $laporan->status }}</p>
                </div>
                <h4 class="font-semibold mt-3">Catatan Reviewer:</h4>
                <p class="text-sm italic p-3 bg-white border rounded">{{ $laporan->catatan_reviewer ?? 'Tidak ada catatan.' }}</p>
            @else
                <div class="p-3 bg-yellow-100 text-yellow-800 rounded">
                    Menunggu penilaian dari Reviewer.
                </div>
            @endif
        </div>
        
        {{-- Form Keputusan Final Admin --}}
        <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-xl font-bold mb-3 border-b pb-2">Keputusan Monev Admin</h3>

            @if($is_reviewed)
                <form action="{{ route('admin.monev.laporan_kemajuan.finalize', $laporan->id) }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Keputusan Akhir</label>
                        <select name="keputusan_akhir" class="mt-1 block w-full border rounded-md p-2" required>
                            <option value="">Pilih Keputusan</option>
                            <option value="Lanjut Laporan Akhir">Lanjut ke Laporan Akhir</option>
                            <option value="Tolak Laporan Akhir">Tolak / Hentikan Pendanaan</option>
                        </select>
                        @error('keputusan_akhir')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Catatan Admin (Opsional)</label>
                        <textarea name="catatan_admin" rows="3" class="mt-1 block w-full border rounded-md p-2">{{ old('catatan_admin', $laporan->catatan_admin) }}</textarea>
                    </div>

                    <button type="submit" class="w-full bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">
                        Finalisasi Keputusan
                    </button>
                </form>
            @else
                <div class="p-4 bg-gray-100 text-gray-700 rounded">
                    Keputusan Admin akan dibuka setelah Reviewer selesai menilai laporan ini.
                </div>
            @endif
        </div>
    </div>
</div>
@endsection