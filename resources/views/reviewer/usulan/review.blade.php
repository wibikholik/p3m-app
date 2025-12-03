@extends('reviewer.layouts.app')

@section('title', 'Review: ' . Str::limit($usulan->judul, 30))
@section('page-title', 'Review Usulan')

@section('content')
<style>
.split-container {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 2rem;
    height: calc(100vh - 200px);
    overflow-y: hidden;
}
.file-viewer-wrapper { overflow-y: scroll; height: 100%; padding-right: 1.5rem; }
.sticky-form { position: sticky; top: 1rem; align-self: flex-start; max-height: 100%; overflow-y: auto; }
@media (max-width: 1024px) {
    .split-container { grid-template-columns: 1fr; height: auto; overflow-y: visible; }
    .file-viewer-wrapper { overflow-y: visible; height: auto; padding-right: 0; }
    .sticky-form { position: static; }
}
</style>

<div class="max-w-7xl mx-auto mt-8 px-4 sm:px-6 lg:px-8">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-4 gap-4">
        <h1 class="text-2xl font-bold text-gray-900">Review Usulan: {{ Str::limit($usulan->judul, 40) }}</h1>
        <a href="{{ route('reviewer.dashboard') }}"
           class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md text-sm font-semibold text-gray-700 hover:bg-gray-50 shadow-sm">
            &larr; Kembali
        </a>
    </div>

    <div class="split-container">

        {{-- LEFT: File Preview --}}
        <div class="file-viewer-wrapper">
            @php
                $filename = $usulan->file_usulan ?? null;
                $isPdf = $filename && in_array(strtolower(pathinfo($filename, PATHINFO_EXTENSION)), ['pdf']);
            @endphp

            @if($filename && $isPdf)
                <div class="bg-white shadow sm:rounded-lg border border-gray-200 mb-6" style="min-height: 800px;">
                    <div class="px-4 py-3 bg-gray-100 border-b">
                        <h3 class="text-lg font-medium text-gray-900">Preview Proposal: {{ $filename }}</h3>
                    </div>
                    <iframe 
                        src="https://docs.google.com/gview?url={{ urlencode(asset('storage/usulan/' . $filename)) }}&embedded=true" 
                        style="width:100%; height:850px;" 
                        frameborder="0">
                    </iframe>
                    <div class="px-4 py-3 border-t bg-gray-50 text-right">
                        <a href="{{ route('reviewer.usulan.download_file', [$usulan->id, $filename]) }}"
                            class="px-3 py-2 border border-blue-300 rounded-md text-blue-700 text-sm hover:bg-blue-50 shadow-sm">
                            Unduh File Asli
                        </a>
                    </div>
                </div>
            @elseif($filename)
                <div class="p-4 bg-yellow-50 border border-yellow-200 text-yellow-600 text-sm text-center rounded mb-6">
                    File ditemukan ({{ $filename }}), tetapi hanya PDF yang bisa dipreview.
                </div>
            @else
                <div class="p-4 bg-red-50 border border-red-200 text-red-600 text-sm text-center rounded mb-6">
                    File proposal belum diunggah.
                </div>
            @endif
        </div>

        {{-- RIGHT: Review Form --}}
        <div class="sticky-form">

            @if(!$pivot->sudah_direview)
            
                <div class="bg-white shadow sm:rounded-lg border border-gray-200 p-6">

                    @if(session('success'))
                        <div class="mb-4 p-3 bg-green-50 border border-green-200 text-green-700 rounded">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form action="{{ route('reviewer.usulan.review.submit', $usulan->id) }}" method="POST">
                        @csrf

                        <h3 class="text-lg font-semibold mb-4 text-gray-800">Komponen Penilaian</h3>

                        @foreach($komponen_penilaian as $kriteria)
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">
                                {{ $kriteria->nama }} (Bobot: {{ $kriteria->bobot }}%)
                            </label>
                            <input type="number" name="nilai[{{ $kriteria->id }}]" min="0" max="100" class="mt-1 w-full border border-gray-300 rounded-md shadow-sm">
                        </div>
                        @endforeach

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Catatan Reviewer</label>
                            <textarea name="catatan" rows="4"
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm"></textarea>
                        </div>

                        <div class="text-right">
                            <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">
                                Submit Review
                            </button>
                        </div>

                    </form>

                </div>

            @else
                <div class="bg-white shadow sm:rounded-lg border border-gray-200 p-6 text-center">
                    <p class="text-lg font-semibold text-gray-700">Usulan ini sudah direview.</p>
                    <p class="text-sm text-gray-500 mt-2">
                        Anda dapat kembali ke 
                        <a href="{{ route('reviewer.dashboard') }}" class="text-indigo-600 hover:text-indigo-800">Dashboard Reviewer</a>.
                    </p>
                </div>
            @endif

        </div>
    </div>
</div>
@endsection
