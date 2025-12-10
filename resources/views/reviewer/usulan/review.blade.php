@extends('reviewer.layouts.app')

@section('title', 'Review Usulan: ' . $usulan->judul)

@section('content')

<style>
    /* Menggunakan CSS yang mirip dengan show usulan admin untuk layout yang rapi */
    .review-split-container {
        display: grid;
        grid-template-columns: 2fr 1fr; /* Pembagian 2/3 untuk File, 1/3 untuk Form */
        gap: 2rem;
        min-height: 80vh; 
        overflow-y: hidden;
    }
    .file-viewer-wrapper {
        height: 100%;
        display: flex;
        flex-direction: column;
    }
    .form-penilaian-wrapper {
        height: 100%;
        overflow-y: auto; /* Memungkinkan form diskroll */
        position: sticky;
        top: 1rem;
        align-self: flex-start;
    }
    /* Media Query untuk Mobile/Tablet */
    @media (max-width: 1024px) {
        .review-split-container {
            grid-template-columns: 1fr;
            height: auto;
            overflow-y: visible;
        }
        .form-penilaian-wrapper {
            position: static;
        }
    }
</style>

<div class="max-w-7xl mx-auto mt-8 px-4 sm:px-6 lg:px-8">
    
    <h1 class="text-2xl font-bold text-gray-900 mb-6 border-b pb-2">
        Review Usulan: {{ $usulan->judul }}
    </h1>

    <div class="review-split-container">

        {{-- LEFT: Preview File Usulan (Menggunakan Iframe) --}}
        <div class="file-viewer-wrapper bg-white shadow rounded-lg p-4">
            <h2 class="text-lg font-semibold mb-4">File Usulan (Preview)</h2>
            
            {{-- Menggunakan variabel file_usulan yang benar --}}
            @if($usulan->file_usulan) 
                
                <iframe 
                    src="{{ asset('storage/' . $usulan->file_usulan) }}" 
                    class="w-full flex-grow min-h-[500px] border" 
                    frameborder="0"
                ></iframe>
                
                <a href="{{ asset('storage/' . $usulan->file_usulan) }}" 
                   target="_blank" 
                   class="mt-3 text-blue-600 hover:underline block text-center"
                   download>
                    Unduh File Usulan
                </a>

            @else
                <div class="text-gray-500 py-10 flex-grow flex items-center justify-center border rounded">
                    File belum diunggah.
                </div>
            @endif
        </div>

        {{-- RIGHT: Form Penilaian --}}
        <div class="form-penilaian-wrapper bg-white shadow rounded-lg p-4">
            <h2 class="text-lg font-semibold mb-4 border-b pb-2">Form Penilaian</h2>
            
            <form action="{{ route('reviewer.penilaian.submit', $usulan->id) }}" method="POST">
                @csrf

                @foreach($komponen as $kriteria)
                <div class="mb-4 p-3 border rounded">
                    <label class="block font-medium text-gray-700">{{ $kriteria->nama }} (Bobot: {{ $kriteria->bobot }}%)</label>
                    
                    {{-- Input Nilai --}}
                    <input type="number" name="nilai[{{ $kriteria->id }}]" min="0" max="100"
                            value="{{ $nilai_lama->has($kriteria->id) ? $nilai_lama[$kriteria->id]->nilai : '' }}"
                            required
                            class="mt-1 w-full border rounded p-2 focus:ring-indigo-500 focus:border-indigo-500">
                    
                    {{-- Input Catatan --}}
                    <textarea name="catatan[{{ $kriteria->id }}]" rows="2"
                        class="mt-2 w-full border rounded p-2"
                        placeholder="Catatan spesifik untuk kriteria ini...">{{ $nilai_lama->has($kriteria->id) ? $nilai_lama[$kriteria->id]->catatan : '' }}</textarea>
                </div>
                @endforeach

                <div class="text-right mt-6 pt-4 border-t">
                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700 font-semibold">
                        Submit Penilaian
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection