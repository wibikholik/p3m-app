@extends('dosen.layouts.app')

@section('title', 'Edit Laporan Kemajuan')
@section('page-title', 'Edit Laporan Kemajuan')

@section('content')

<div class="max-w-6xl mx-auto mt-8 px-4 sm:px-6 lg:px-8">

    {{-- HEADER --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Edit Laporan Kemajuan</h1>
            <p class="text-sm text-gray-500 mt-1">
                Laporan untuk: <span class="font-semibold text-blue-600">{{ Str::limit($laporan->usulan->judul, 50) }}</span>
            </p>
        </div>

        <a href="{{ route('dosen.laporan-kemajuan.index') }}"
           class="px-4 py-2 bg-white border border-gray-300 rounded-md hover:bg-gray-50 text-sm">
           ← Kembali
        </a>
    </div>

    {{-- ERROR --}}
    @if ($errors->any())
        <div class="mb-4 p-4 bg-red-100 text-red-700 border border-red-300 rounded-md">
            <ul class="list-disc ml-5 text-sm">
                @foreach ($errors->all() as $e)
                    <li>{{ $e }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- FORM --}}
    <div class="bg-white shadow rounded-lg border">
        <div class="p-6">

            <form action="{{ route('dosen.laporan-kemajuan.update', $laporan->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                {{-- Ringkasan --}}
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Ringkasan Kemajuan *</label>
                    <textarea name="ringkasan_kemajuan" rows="5"
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2"
                        required>{{ old('ringkasan_kemajuan', $laporan->ringkasan_kemajuan) }}</textarea>
                    @error('ringkasan_kemajuan')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Kendala --}}
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Kendala</label>
                    <textarea name="kendala" rows="4"
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2">{{ old('kendala', $laporan->kendala) }}</textarea>
                    @error('kendala')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Persentase --}}
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Persentase Kemajuan (%) *</label>
                    <input type="number" name="persentase" min="1" max="100"
                        class="mt-1 block w-32 border border-gray-300 rounded-md shadow-sm p-2"
                        value="{{ old('persentase', $laporan->persentase) }}" required>
                    @error('persentase')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- File --}}
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Upload Laporan (PDF)*</label>
                    <div class="mt-2 flex flex-col items-center p-6 border-2 border-dashed border-gray-300 rounded-md bg-gray-50">
                        <input type="file" id="file_laporan" name="file_laporan" class="hidden" accept="application/pdf">
                        <label for="file_laporan" class="cursor-pointer text-blue-600 underline">
                            Klik untuk upload
                        </label>
                        <p id="file-name-display" class="mt-3 text-sm text-green-600 font-semibold">
                            {{ $laporan->file_laporan ? basename($laporan->file_laporan) : 'Belum ada file diunggah' }}
                        </p>
                    </div>
                    @error('file_laporan')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Submit --}}
                <div class="flex justify-end mt-6">
                    <a href="{{ route('dosen.laporan-kemajuan.index') }}"
                        class="px-4 py-2 bg-gray-200 rounded-md mr-3 hover:bg-gray-300">
                        Batal
                    </a>

                    <button type="submit"
                        class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                        Update Laporan
                    </button>
                </div>

            </form>
        </div>
    </div>

</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$('#file_laporan').change(function(){
    if(this.files.length > 0) {
        $('#file-name-display').text(this.files[0].name);
    } else {
        $('#file-name-display').text('{{ $laporan->file_laporan ? basename($laporan->file_laporan) : "Belum ada file diunggah" }}');
    }
});
</script>

@endsection
