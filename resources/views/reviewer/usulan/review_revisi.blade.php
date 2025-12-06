@extends('reviewer.layouts.app')

@section('title', 'Review Revisi Usulan: ' . $usulan->judul)

@section('content')
<div class="max-w-7xl mx-auto mt-8 px-4 sm:px-6 lg:px-8 grid grid-cols-2 gap-6">

    {{-- Preview File Revisi --}}
    <div class="bg-white shadow rounded-lg p-4 overflow-auto">
        <h2 class="text-lg font-semibold mb-4">File Revisi Usulan</h2>
        @if($usulan->file_revisi)
            <iframe src="{{ asset('storage/' . $usulan->file_revisi) }}" class="w-full h-[500px]" frameborder="0"></iframe>
        @else
            <p class="text-gray-500">File revisi belum diunggah oleh dosen.</p>
        @endif
    </div>

    {{-- Form Penilaian Revisi --}}
    <div class="bg-white shadow rounded-lg p-4 overflow-auto">
        <h2 class="text-lg font-semibold mb-4">Form Penilaian Revisi</h2>
        <form action="{{ route('reviewer.usulan.submit_revisi', $usulan->id) }}" method="POST">
            @csrf

            @foreach($komponen_penilaian as $kriteria)
            <div class="mb-4">
                <label class="block font-medium">{{ $kriteria->nama }} (Bobot: {{ $kriteria->bobot }}%)</label>
                <input type="number" name="nilai[{{ $kriteria->id }}]" min="0" max="100"
                       value="{{ $nilai_lama->has($kriteria->id) ? $nilai_lama[$kriteria->id]->nilai : '' }}"
                       class="mt-1 w-full border rounded p-2">
                <textarea name="catatan[{{ $kriteria->id }}]" rows="2"
                    class="mt-1 w-full border rounded p-2"
                    placeholder="Catatan...">{{ $nilai_lama->has($kriteria->id) ? $nilai_lama[$kriteria->id]->catatan : '' }}</textarea>
            </div>
            @endforeach

            <div class="text-right">
                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">Submit Penilaian Revisi</button>
            </div>
        </form>
    </div>
</div>
@endsection
