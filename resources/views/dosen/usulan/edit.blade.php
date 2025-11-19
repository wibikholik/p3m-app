@extends('dosen.layouts.app')

@section('title', 'Edit Usulan')
@section('page-title', 'Edit Usulan')

{{-- Load CSS Select2 --}}
@section('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    /* Fix height Select2 agar sejajar dengan input Tailwind */
    .select2-container .select2-selection--single {
        height: 42px !important;
        display: flex;
        align-items: center;
        border-color: #d1d5db; /* gray-300 */
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 40px !important;
    }
</style>
@endsection

@section('content')
<div class="max-w-5xl mx-auto mt-8 px-4 sm:px-6 lg:px-8">

    {{-- Header & Back Button --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 gap-4">
        <div>
            <nav class="flex mb-1" aria-label="Breadcrumb">
                <ol class="flex items-center space-x-2 text-sm text-gray-500">
                    <li><a href="{{ route('dosen.usulan.index') }}" class="hover:text-blue-600">Usulan Saya</a></li>
                    <li><span class="text-gray-400">/</span></li>
                    <li><a href="{{ route('dosen.usulan.show', $usulan->id) }}" class="hover:text-blue-600">Detail</a></li>
                    <li><span class="text-gray-400">/</span></li>
                    <li class="text-gray-900 font-medium" aria-current="page">Edit</li>
                </ol>
            </nav>
            <h1 class="text-2xl font-bold text-gray-900">Edit Usulan</h1>
        </div>
        <a href="{{ route('dosen.usulan.show', $usulan->id) }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 transition ease-in-out duration-150">
            &larr; Batal
        </a>
    </div>

    {{-- Error Validation --}}
    @if ($errors->any())
        <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 rounded shadow-sm">
            <p class="font-bold">Terjadi Kesalahan:</p>
            <ul class="list-disc pl-5 mt-1 text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Form Container --}}
    <div class="bg-white shadow overflow-hidden sm:rounded-lg border border-gray-200">
        <div class="px-4 py-5 sm:p-6">
            
            <form action="{{ route('dosen.usulan.update', $usulan->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                {{-- Hidden: ID Pengumuman (biasanya tidak berubah saat edit, tapi dibutuhkan jika validasi memerlukannya) --}}
                <input type="hidden" name="id_pengumuman" value="{{ $pengumuman->id }}">

                <div class="grid grid-cols-1 gap-6">
                    
                    {{-- Judul Usulan --}}
                    <div>
                        <label for="judul" class="block text-sm font-medium text-gray-700">Judul Usulan</label>
                        <input type="text" name="judul" id="judul" 
                               class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md p-2 border" 
                               value="{{ old('judul', $usulan->judul) }}" required>
                    </div>

                    {{-- Skema --}}
                    <div>
                        <label for="skema" class="block text-sm font-medium text-gray-700">Skema</label>
                        <select name="skema" id="skema" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
                            @if(count($skemaList) > 0)
                                <option value="" disabled>-- Pilih Skema --</option>
                                @foreach($skemaList as $key => $value)
                                    <option value="{{ $key }}" {{ old('skema', $usulan->skema) == $key ? 'selected' : '' }}>
                                        {{ $value }}
                                    </option>
                                @endforeach
                            @else
                                <option value="" disabled selected>-- Tidak ada skema tersedia untuk jabatan Anda --</option>
                            @endif
                        </select>
                    </div>

                    {{-- Abstrak --}}
                    <div>
                        <label for="abstrak" class="block text-sm font-medium text-gray-700">Abstrak</label>
                        <textarea name="abstrak" id="abstrak" rows="5" 
                                     class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md p-2 border" 
                                     required>{{ old('abstrak', $usulan->abstrak) }}</textarea>
                    </div>

                    {{-- Upload File --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700">File Proposal (PDF)</label>
                        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md hover:bg-gray-50 transition">
                            <div class="space-y-1 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <div class="flex text-sm text-gray-600 justify-center">
                                    <label for="file_usulan" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                        <span>Upload file baru</span>
                                        <input id="file_usulan" name="file_usulan" type="file" accept="application/pdf" class="sr-only">
                                    </label>
                                    <p class="pl-1">atau drag and drop</p>
                                </div>
                                <p class="text-xs text-gray-500">PDF hingga 2MB</p>
                            </div>
                        </div>
                        @if($usulan->file_usulan)
                            <div class="mt-2 flex items-center text-sm text-green-600 bg-green-50 p-2 rounded border border-green-200">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                File saat ini: 
                                <a href="{{ asset('storage/'.$usulan->file_usulan) }}" target="_blank" class="underline ml-1 font-semibold hover:text-green-800">Lihat Proposal Lama</a>
                            </div>
                        @endif
                    </div>

                    <hr class="border-gray-200">

                    {{-- Anggota Section --}}
                    <div>
                        <h3 class="text-lg font-medium leading-6 text-gray-900 mb-2">Anggota Tim</h3>
                        <p class="text-sm text-gray-500 mb-4">Tambahkan anggota dosen internal. NIDN dan Jabatan akan terisi otomatis, **Peran diisi manual**.</p>

                        <div id="anggotas-container" class="space-y-3">
                            {{-- 
                                LOOP ANGGOTA EKSISTING 
                                Kita loop data dari DB agar form terisi data lama
                            --}}
                            @foreach($usulan->anggota as $index => $anggota)
                            <div class="anggota-row flex flex-col sm:flex-row gap-2 items-start sm:items-center bg-gray-50 p-3 rounded border border-gray-200" data-index="{{ $index }}">
                                <div class="flex-1 w-full">
                                    <select name="anggota[{{ $index }}][nama]" class="select2-anggota w-full border-gray-300 rounded-md" required>
                                        <option value="">-- Cari Nama Anggota --</option>
                                        @foreach($dosenList as $dosen)
                                            <option value="{{ $dosen->name }}" 
                                                    data-nidn="{{ $dosen->nidn }}" 
                                                    data-jabatan="{{ $dosen->jabatan_akademik }}"
                                                    {{ $dosen->name == $anggota->nama ? 'selected' : '' }}>
                                                {{ $dosen->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                
                                {{-- Input Readonly NIDN & Jabatan --}}
                                <input type="text" name="anggota[{{ $index }}][nidn]" value="{{ $anggota->nidn }}" placeholder="NIDN" class="nidn-input block w-full sm:w-32 border-gray-300 bg-gray-100 text-gray-500 rounded-md shadow-sm text-sm p-2" readonly>
                                
                                <input type="text" name="anggota[{{ $index }}][jabatan]" value="{{ $anggota->jabatan }}" placeholder="Jabatan" class="jabatan-input block w-full sm:w-40 border-gray-300 bg-gray-100 text-gray-500 rounded-md shadow-sm text-sm p-2" readonly>

                                {{-- TAMBAHAN KOLOM PERAN (INPUT MANUAL) --}}
                                <input type="text" 
                                       name="anggota[{{ $index }}][peran]" 
                                       value="{{ $anggota->peran ?? '' }}" 
                                       placeholder="Peran" 
                                       required 
                                       class="block w-full sm:w-40 border-gray-300 bg-white text-gray-700 rounded-md shadow-sm text-sm p-2">
                                
                                <button type="button" class="remove-anggota bg-red-100 text-red-600 hover:bg-red-200 p-2 rounded-md transition">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                </button>
                            </div>
                            @endforeach
                        </div>

                        <button type="button" id="tambah-anggota" class="mt-3 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-blue-700 bg-blue-100 hover:bg-blue-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                            Tambah Anggota
                        </button>
                    </div>

                </div>

                {{-- Footer Actions --}}
                <div class="mt-8 pt-5 border-t border-gray-200 flex justify-end gap-3">
                    <a href="{{ route('dosen.usulan.show', $usulan->id) }}" class="px-4 py-2 bg-white border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Batal
                    </a>
                    <button type="submit" class="px-4 py-2 bg-blue-600 border border-transparent rounded-md text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 shadow-sm">
                        Simpan Perubahan
                    </button>
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
            placeholder: "-- Cari Nama Anggota --",
            allowClear: true,
            width: '100%' 
        });
    }

    // Init pada elemen yang sudah ada saat halaman dimuat (data eksisting)
    initSelect2($('.select2-anggota'));

    // 2. Event Listener: Auto-fill NIDN & Jabatan
    $(document).on('change', '.select2-anggota', function() {
        let selected = $(this).find(':selected');
        let nidn = selected.data('nidn');
        let jabatan = selected.data('jabatan');

        let row = $(this).closest('.anggota-row');
        row.find('.nidn-input').val(nidn);
        row.find('.jabatan-input').val(jabatan);
    });

    // 3. Persiapan Variabel untuk Tambah Baris Baru
    // Kita hitung index awal berdasarkan jumlah anggota eksisting agar name array tidak bentrok
    let anggotaIndex = {{ $usulan->anggota->count() }};
    
    // String options dosen disimpan di variable JS agar mudah diappend
    let optionsDosen = `<option value="">-- Cari Nama Anggota --</option>`;
    @foreach($dosenList as $dosen)
        optionsDosen += `<option value="{{ $dosen->name }}" data-nidn="{{ $dosen->nidn }}" data-jabatan="{{ $dosen->jabatan_akademik }}">{{ $dosen->name }}</option>`;
    @endforeach

    // 4. Fungsi Tambah Anggota
    $('#tambah-anggota').click(function() {
        let html = `
            <div class="anggota-row flex flex-col sm:flex-row gap-2 items-start sm:items-center bg-gray-50 p-3 rounded border border-gray-200 mt-2" data-index="${anggotaIndex}">
                <div class="flex-1 w-full">
                    <select name="anggota[${anggotaIndex}][nama]" class="select2-anggota w-full border-gray-300 rounded-md" required>
                        ${optionsDosen}
                    </select>
                </div>
                <input type="text" name="anggota[${anggotaIndex}][nidn]" placeholder="NIDN" class="nidn-input block w-full sm:w-32 border-gray-300 bg-gray-100 text-gray-500 rounded-md shadow-sm text-sm p-2" readonly>
                <input type="text" name="anggota[${anggotaIndex}][jabatan]" placeholder="Jabatan" class="jabatan-input block w-full sm:w-40 border-gray-300 bg-gray-100 text-gray-500 rounded-md shadow-sm text-sm p-2" readonly>
                
                {{-- TAMBAHAN KOLOM PERAN (INPUT MANUAL) --}}
                <input type="text" 
                       name="anggota[${anggotaIndex}][peran]" 
                       placeholder="Peran (Contoh: Anggota)" 
                       required 
                       class="block w-full sm:w-40 border-gray-300 bg-white text-gray-700 rounded-md shadow-sm text-sm p-2">

                <button type="button" class="remove-anggota bg-red-100 text-red-600 hover:bg-red-200 p-2 rounded-md transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        `;
        
        let newRow = $(html);
        $('#anggotas-container').append(newRow);
        
        // Init Select2 HANYA pada elemen baru
        initSelect2(newRow.find('.select2-anggota'));
        
        anggotaIndex++;
    });

    // 5. Hapus Anggota
    $(document).on('click', '.remove-anggota', function() {
        let row = $(this).closest('.anggota-row');
        
        // Cek jika ini adalah baris terakhir (opsional: jika Anda ingin memastikan minimal 1 anggota)
        if ($('#anggotas-container .anggota-row').length <= 1) {
            // alert('Minimal harus ada satu anggota peneliti.'); // Gunakan modal kustom jika alert dilarang
            return;
        }

        // Hapus Select2 instance sebelum menghapus DOM element
        row.find('.select2-anggota').select2('destroy');
        row.remove();
        
        // Opsional: Perbarui indeks (penting jika Anda ingin array name selalu berurutan)
        $('#anggotas-container .anggota-row').each(function(index) {
            $(this).attr('data-index', index);
            $(this).find('[name^="anggota"]').each(function() {
                let currentName = $(this).attr('name');
                let newName = currentName.replace(/\[\d+\]/, '[' + index + ']');
                $(this).attr('name', newName);
            });
        });
        
        anggotaIndex = $('#anggotas-container .anggota-row').length;
    });

    // Optional: Tampilkan nama file saat user memilih file baru
    $('#file_usulan').change(function() {
        var fileName = $(this).val().split('\\').pop();
        if(fileName) {
            $(this).prev('span').text('Ganti file: ' + fileName);
        } else {
            $(this).prev('span').text('Upload file baru');
        }
    });

});
</script>
@endsection