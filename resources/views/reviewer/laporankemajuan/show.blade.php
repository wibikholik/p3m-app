@extends('reviewer.layouts.app')

@section('title', 'Nilai Laporan Kemajuan')
@section('page-title', 'Nilai Laporan Kemajuan')

@section('content')
<div class="max-w-4xl mx-auto mt-10 bg-white p-8 shadow-md rounded-lg">
    <h2 class="text-xl font-bold text-gray-800 mb-6">
        {{ Str::limit($laporan->usulan->judul, 50) }}
    </h2>

    <p class="text-sm text-gray-600 mb-4">
        Dosen: {{ $laporan->usulan->user->name }} | Persentase Kemajuan: {{ $laporan->persentase }}%
    </p>

    <p class="text-sm text-gray-700 mb-4">
        <strong>Ringkasan Kemajuan:</strong><br>{{ $laporan->ringkasan_kemajuan }}
    </p>

    @if($laporan->kendala)
    <p class="text-sm text-gray-700 mb-4">
        <strong>Kendala:</strong><br>{{ $laporan->kendala }}
    </p>
    @endif

    <a href="{{ asset('storage/'.$laporan->file_laporan) }}" target="_blank" class="text-blue-600 underline mb-6 block">
        Unduh Laporan PDF
    </a>

    <form action="{{ route('reviewer.laporan-kemajuan.nilai', $laporan->id) }}" method="POST">
        @csrf

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700">Nilai (0-100)</label>
            <input type="number" name="nilai" min="0" max="100" class="mt-1 block w-32 border rounded-md p-2" required>
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700">Status Review</label>
            <select name="status_review" class="mt-1 w-48 border rounded-md p-2" required>
                <option value="Disetujui">Disetujui</option>
                <option value="Ditolak">Ditolak</option>
                <option value="Perbaikan">Perbaikan</option>
            </select>
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700">Catatan / Revisi (opsional)</label>
            <textarea name="catatan_reviewer" rows="4" class="mt-1 block w-full border rounded-md p-2"></textarea>
        </div>

        <div class="flex justify-end gap-3">
            <a href="{{ route('reviewer.laporan-kemajuan.index') }}" class="px-4 py-2 bg-gray-200 rounded-md hover:bg-gray-300">Kembali</a>
            <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">Kirim Penilaian</button>
        </div>
    </form>
</div>
@endsection
