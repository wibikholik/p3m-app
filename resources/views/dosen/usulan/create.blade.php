@extends('dosen.layouts.app')

@section('title', 'Ajukan Usulan Baru')
@section('page-title', 'Ajukan Usulan')

{{-- Styles Khusus untuk Select2 agar serasi dengan Tailwind --}}
@section('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    /* Menyamakan tinggi Select2 dengan Input Tailwind (42px) */
    .select2-container .select2-selection--single {
        height: 42px !important;
        display: flex;
        align-items: center;
        border-color: #d1d5db; /* gray-300 */
        border-radius: 0.375rem; /* rounded-md */
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 40px !important;
    }
    .select2-container--default .select2-selection--single:focus {
        border-color: #3b82f6; /* blue-500 */
        box-shadow: 0 0 0 1px #3b82f6;
    }
</style>
@endsection

@section('content')
<div class="max-w-5xl mx-auto mt-8 px-4 sm:px-6 lg:px-8">

    {{-- Header & Breadcrumb --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 gap-4">
        <div>
            <nav class="flex mb-1" aria-label="Breadcrumb">
                <ol class="flex items-center space-x-2 text-sm text-gray-500">
                    <li><a href="{{ route('dosen.pengumuman.index') }}" class="hover:text-blue-600">Pengumuman</a></li>
                    <li><span class="text-gray-400">/</span></li>
                    <li class="text-gray-900 font-medium" aria-current="page">Ajukan Usulan</li>
                </ol>
            </nav>
            <h1 class="text-2xl font-bold text-gray-900">Form Pengajuan Usulan</h1>
            <p class="text-sm text-gray-600 mt-1">Mengajukan untuk: <span class="font-semibold text-blue-600">{{ $pengumuman->judul }}</span></p>
        </div>
        <a href="{{ route('dosen.pengumuman.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 transition ease-in-out duration-150">
            &larr; Batal
        </a>
    </div>

    {{-- Form Card --}}
    <div class="bg-white shadow overflow-hidden sm:rounded-lg border border-gray-200">
        <div class="px-4 py-5 sm:p-6">
            
            <form action="{{ route('dosen.usulan.store') }}" method="POST" enctype="multipart/form-data" id="form-usulan">
                @csrf
                <input type="hidden" name="id_pengumuman" value="{{ $pengumuman->id }}">

                <div class="space-y-6">
                    
                    {{-- BAGIAN 1: Data Utama --}}
                    <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                        
                        {{-- Judul Usulan --}}
                        <div class="sm:col-span-6">
                            <label for="judul" class="block text-sm font-medium text-gray-700">Judul Usulan</label>
                            <div class="mt-1">
                                <input type="text" name="judul" id="judul" class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md p-2.5 border" placeholder="Masukkan judul penelitian/pengabdian..." required>
                            </div>
                        </div>

                        {{-- Skema --}}
                        <div class="sm:col-span-3">
                            <label for="skema" class="block text-sm font-medium text-gray-700">Skema</label>
                            <div class="mt-1">
                                <select name="skema" id="skema" class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md p-2.5 border" required>
                                    @if(count($skemaList) > 0)
                                        <option value="" disabled selected>-- Pilih Skema --</option>
                                        @foreach($skemaList as $key => $value)
                                            <option value="{{ $key }}">{{ $value }}</option>
                                        @endforeach
                                    @else
                                        <option value="" disabled selected>-- Tidak ada skema tersedia --</option>
                                    @endif
                                </select>
                            </div>
                        </div>

                        {{-- Abstrak --}}
                        <div class="sm:col-span-6">
                            <label for="abstrak" class="block text-sm font-medium text-gray-700">Abstrak / Ringkasan</label>
                            <div class="mt-1">
                                <textarea id="abstrak" name="abstrak" rows="4" class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md p-2 border" placeholder="Tuliskan abstrak singkat..." required></textarea>
                            </div>
                        </div>

                        {{-- Upload Proposal --}}
                        <div class="sm:col-span-6">
                            <label class="block text-sm font-medium text-gray-700">Unggah Proposal (PDF)</label>
                            <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md hover:bg-gray-50 transition cursor-pointer relative">
                                <div class="space-y-1 text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <div class="flex text-sm text-gray-600 justify-center">
                                        <label for="file_usulan" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                            <span>Upload file proposal</span>
                                            <input id="file_usulan" name="file_usulan" type="file" accept="application/pdf" class="sr-only" required>
                                        </label>
                                        <p class="pl-1">atau drag and drop</p>
                                    </div>
                                    <p class="text-xs text-gray-500">Format PDF maksimal 10MB</p>
                                    <p id="file-name-display" class="text-sm text-green-600 font-semibold mt-2"></p>
                                </div>
                            </div>
                        </div>

                    </div>

                    <hr class="border-gray-200 my-6">

                    {{-- BAGIAN 2: Anggota --}}
                    <div>
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Anggota Tim Peneliti</h3>
                        <p class="mt-1 text-sm text-gray-500">Tambahkan dosen internal yang terlibat. NIDN dan Jabatan akan terisi otomatis saat nama dipilih.</p>
                        
                        <div id="anggotas-container" class="mt-4 space-y-3">
                            {{-- Row Pertama (Wajib Ada) --}}
                            <div class="anggota-row grid grid-cols-1 md:grid-cols-12 gap-4 items-start bg-gray-50 p-4 rounded-lg border border-gray-200">
                                <div class="md:col-span-5">
                                    <label class="block text-xs font-medium text-gray-500 mb-1 md:hidden">Nama Dosen</label>
                                    <select name="anggota[0][nama]" class="select2-anggota block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" required>
                                        <option value="">-- Cari Nama Anggota --</option>
                                        @foreach($dosenList as $dosen)
                                            <option value="{{ $dosen->name }}" 
                                                    data-nidn="{{ $dosen->nidn }}" 
                                                    data-jabatan="{{ $dosen->jabatan_akademik }}">
                                                {{ $dosen->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="md:col-span-3">
                                    <label class="block text-xs font-medium text-gray-500 mb-1 md:hidden">NIDN</label>
                                    <input type="text" name="anggota[0][nidn]" placeholder="NIDN" class="nidn-input block w-full rounded-md border-gray-300 bg-gray-200 text-gray-600 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" readonly>
                                </div>
                                <div class="md:col-span-3">
                                    <label class="block text-xs font-medium text-gray-500 mb-1 md:hidden">Jabatan</label>
                                    <input type="text" name="anggota[0][jabatan]" placeholder="Jabatan" class="jabatan-input block w-full rounded-md border-gray-300 bg-gray-200 text-gray-600 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" readonly>
                                </div>
                                <div class="md:col-span-3">
                                    <label class="block text-xs font-medium text-gray-500 mb-1 md:hidden">Jabatan</label>
                                    <input type="text" name="anggota[0][jabatan]" placeholder="Jabatan" class="jabatan-input block w-full rounded-md border-gray-300 bg-gray-200 text-gray-600 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" readonly>
                                </div>
                                <div class="md:col-span-1 flex justify-end md:justify-center items-center pt-1">
                                    <button type="button" class="remove-anggota text-red-500 hover:text-red-700 bg-red-50 hover:bg-red-100 p-2 rounded-full transition">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <button type="button" id="tambah-anggota" class="mt-4 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-blue-700 bg-blue-100 hover:bg-blue-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                            Tambah Anggota Lain
                        </button>
                    </div>

                </div>

                {{-- Footer --}}
                <div class="pt-5 mt-8 border-t border-gray-200">
                    <div class="flex justify-end">
                        <a href="{{ route('dosen.pengumuman.index') }}" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Batal
                        </a>
                        <button type="submit" class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                            Ajukan Usulan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Scripts --}}
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
$(document).ready(function () {

    // 1. Init Select2
    function initSelect2(element) {
        element.select2({
            placeholder: "-- Pilih Nama Anggota --",
            allowClear: true,
            width: '100%'
        });
    }

    initSelect2($('.select2-anggota'));

    // 2. Auto-fill NIDN & Jabatan
    $(document).on('change', '.select2-anggota', function() {
        let selected = $(this).find(':selected');
        let nidn = selected.data('nidn');
        let jabatan = selected.data('jabatan');
        let row = $(this).closest('.anggota-row');
        row.find('.nidn-input').val(nidn);
        row.find('.jabatan-input').val(jabatan);
    });

    // 3. Tambah Anggota Dinamis
    let anggotaIndex = 1;
    let optionsDosen = `<option value="">-- Cari Nama Anggota --</option>`;
    @foreach($dosenList as $dosen)
        optionsDosen += `<option value="{{ $dosen->name }}" data-nidn="{{ $dosen->nidn }}" data-jabatan="{{ $dosen->jabatan_akademik }}">{{ $dosen->name }}</option>`;
    @endforeach

    $('#tambah-anggota').click(function() {
        let html = `
            <div class="anggota-row grid grid-cols-1 md:grid-cols-12 gap-4 items-start bg-gray-50 p-4 rounded-lg border border-gray-200">
                <div class="md:col-span-5">
                    <label class="block text-xs font-medium text-gray-500 mb-1 md:hidden">Nama Dosen</label>
                    <select name="anggota[${anggotaIndex}][nama]" class="select2-anggota block w-full rounded-md border-gray-300" required>
                        ${optionsDosen}
                    </select>
                </div>
                <div class="md:col-span-3">
                    <label class="block text-xs font-medium text-gray-500 mb-1 md:hidden">NIDN</label>
                    <input type="text" name="anggota[${anggotaIndex}][nidn]" placeholder="NIDN" class="nidn-input block w-full rounded-md border-gray-300 bg-gray-200 text-gray-600 shadow-sm sm:text-sm" readonly>
                </div>
                <div class="md:col-span-3">
                    <label class="block text-xs font-medium text-gray-500 mb-1 md:hidden">Jabatan</label>
                    <input type="text" name="anggota[${anggotaIndex}][jabatan]" placeholder="Jabatan" class="jabatan-input block w-full rounded-md border-gray-300 bg-gray-200 text-gray-600 shadow-sm sm:text-sm" readonly>
                </div>
                <div class="md:col-span-1 flex justify-end md:justify-center items-center pt-1">
                    <button type="button" class="remove-anggota text-red-500 hover:text-red-700 bg-red-50 hover:bg-red-100 p-2 rounded-full transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    </button>
                </div>
            </div>
        `;
        
        $('#anggotas-container').append(html);
        initSelect2($('#anggotas-container .anggota-row:last-child .select2-anggota'));
        anggotaIndex++;
    });

    // 4. Hapus Anggota
    $(document).on('click', '.remove-anggota', function() {
        if ($('.anggota-row').length > 1) {
            $(this).closest('.anggota-row').remove();
        } else {
            alert("Minimal satu anggota diperlukan.");
        }
    });

    // 5. Tampilkan Nama File Upload
    $('#file_usulan').change(function() {
        let fileName = $(this).val().split('\\').pop();
        if(fileName) {
            $('#file-name-display').text('File terpilih: ' + fileName);
        } else {
            $('#file-name-display').text('');
        }
    });

});
</script>
@endsection