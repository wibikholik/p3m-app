@extends('dosen.layouts.app')

@section('title', 'Edit Usulan')
@section('page-title', 'Edit Usulan')

@section('content')
<div class="max-w-4xl mx-auto mt-6 p-6 bg-white rounded-lg shadow">

    <h1 class="text-2xl font-bold mb-4">Edit Usulan: {{ $usulan->judul }}</h1>
    <div class="text-right mb-4">
        <a class="text-blue-600 hover:underline" href="{{ route('dosen.usulan.show', $usulan->id) }}">
            ← Batal dan Kembali ke Detail
        </a>
    </div>

    {{-- Tampilkan Error Validasi --}}
    @if ($errors->any())
        <div class="mb-4 p-4 bg-red-100 text-red-700 rounded">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- UBAH: Action ke route 'update' dan method 'PUT' --}}
    <form action="{{ route('dosen.usulan.update', $usulan->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT') 
        
        <input type="hidden" name="id_pengumuman" value="{{ $pengumuman->id }}">

        {{-- Judul Usulan (isi value) --}}
        <div class="mb-4">
            <label for="judul" class="block mb-1 font-medium">Judul Usulan</label>
            <input type="text" name="judul" id="judul" class="w-full border rounded p-2" value="{{ old('judul', $usulan->judul) }}" required>
        </div>

        {{-- Skema (pilih yg 'selected') --}}
        <div class="mb-4">
            <label for="skema" class="block mb-1 font-medium">Skema</label>
            <select name="skema" id="skema" class="w-full border rounded p-2" required>
                @if(count($skemaList) > 0)
                    <option value="" disabled>-- Pilih Skema Sesuai Jabatan Anda --</option>
                    @foreach($skemaList as $key => $value)
                        <option value="{{ $key }}" {{ old('skema', $usulan->skema) == $key ? 'selected' : '' }}>
                            {{ $value }}
                        </option>
                    @endforeach
                @else
                    <option value="" disabled selected>-- Jabatan Anda tidak memiliki skema yang tersedia --</option>
                @endif
            </select>
        </div>

        {{-- Deskripsi (isi value di textarea) --}}
        <div class="mb-4">
            <label for="deskripsi" class="block mb-1 font-medium">Deskripsi</label>
            <textarea name="deskripsi" id="deskripsi" rows="4" class="w-full border rounded p-2" required>{{ old('deskripsi', $usulan->deskripsi) }}</textarea>
        </div>

        {{-- Tahun Pelaksanaan (isi value) --}}
        <div class="mb-4">
            <label for="tahun_pelaksanaan" class="block mb-1 font-medium">Tahun Pelaksanaan</label>
            <input type="text" name="tahun_pelaksanaan" id="tahun_pelaksanaan" class="w-full border rounded p-2" value="{{ old('tahun_pelaksanaan', $usulan->tahun_pelaksanaan) }}" required>
        </div>

        {{-- Upload Proposal (tampilkan file lama) --}}
        <div class="mb-6">
            <label for="file_proposal" class="block mb-1 font-medium">Unggah Proposal Baru (PDF)</label>
            <input type="file" name="file_proposal" id="file_proposal" accept="application/pdf" class="w-full">
            @if($usulan->file_lampiran)
                <p class="text-sm mt-1">
                    File saat ini: 
                    <a href="{{ asset('storage/'.$usulan->file_lampiran) }}" target="_blank" class="text-blue-600 hover:underline">
                        Lihat Proposal
                    </a>
                </p>
            @endif
        </div>

        {{-- RAB (isi value) --}}
        <div class="mb-6">
            <h2 class="text-lg font-semibold mb-2">Rencana Anggaran Biaya (RAB)</h2>
            <div id="rabs">
                {{-- Loop RAB yang ada --}}
                @foreach(old('rabs', $usulan->rabs->toArray()) as $index => $rab)
                <div class="flex gap-2 mb-2 items-center">
                    <input type="text" name="rabs[{{ $index }}][nama_item]" placeholder="Nama Item" class="border rounded p-2 flex-1" value="{{ $rab['nama_item'] ?? '' }}" required>
                    <input type="number" name="rabs[{{ $index }}][jumlah]" placeholder="Jumlah" class="border rounded p-2 w-24" value="{{ $rab['jumlah'] ?? '' }}" required>
                    <input type="number" name="rabs[{{ $index }}][harga_satuan]" placeholder="Harga Satuan" class="border rounded p-2 w-32" value="{{ $rab['harga_satuan'] ?? '' }}" required>
                    <button type="button" onclick="this.parentElement.remove()" class="remove-rab bg-red-500 text-white px-2 py-1 rounded">X</button>
                </div>
                @endforeach
            </div>
            <button type="button" id="tambah-rab" class="bg-blue-600 text-white px-4 py-2 rounded mt-2">Tambah Item</button>
        </div>

        {{-- Anggota (isi value) --}}
        <div class="mb-6">
            <h2 class="text-lg font-semibold mb-2">Anggota</h2>
            <div id="anggotas">
                {{-- Loop Anggota yang ada --}}
                @foreach(old('anggotas', $usulan->anggotas->toArray()) as $index => $anggota)
                <div class="flex gap-2 mb-2 items-center">
                    <select name="anggotas[{{ $index }}][id_user]" class="select-dosen w-full border rounded p-2" required>
                        {{-- Pre-fill option untuk Select2 --}}
                        <option value="{{ $anggota['id_user'] }}" selected>
                            {{ \App\Models\User::find($anggota['id_user'])->name ?? 'Memuat...' }}
                        </option>
                    </select>
                    <input type="text" name="anggotas[{{ $index }}][peran]" placeholder="Peran (opsional)" class="border rounded p-2 w-48" value="{{ $anggota['peran'] ?? '' }}">
                    <button type="button" onclick="this.parentElement.remove()" class="remove-anggota bg-red-500 text-white px-2 py-1 rounded">X</button>
                </div>
                @endforeach
            </div>
            <button type="button" id="tambah-anggota" class="bg-blue-600 text-white px-4 py-2 rounded mt-2">Tambah Anggota</button>
        </div>

        {{-- Submit --}}
        <div class="text-right">
            <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded">Simpan Perubahan</button>
        </div>
    </form>
</div>

{{-- JQuery & Select2 --}}
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
$(document).ready(function () {

    // ======== INIT SELECT2 DOSEN ========
    function initSelect2() {
        $('.select-dosen').select2({
            placeholder: 'Cari nama dosen...',
            ajax: {
                url: '{{ route("dosen.search") }}',
                dataType: 'json',
                delay: 250,
                data: function (params) { return { q: params.term }; },
                processResults: function (data) {
                    return { results: data.map(function (item) { return { id: item.id, text: item.name }; }) };
                },
                cache: true
            }
        });
    }

    initSelect2();

    // ======== TAMBAH RAB ========
    // UBAH: Index dimulai dari jumlah data yg ada
    let rabIndex = {{ count(old('rabs', $usulan->rabs->toArray())) }};
    $('#tambah-rab').click(function() {
        let html = `
            <div class="flex gap-2 mb-2 items-center">
                <input type="text" name="rabs[${rabIndex}][nama_item]" placeholder="Nama Item" class="border rounded p-2 flex-1" required>
                <input type="number" name="rabs[${rabIndex}][jumlah]" placeholder="Jumlah" class="border rounded p-2 w-24" required>
                <input type="number" name="rabs[${rabIndex}][harga_satuan]" placeholder="Harga Satuan" class="border rounded p-2 w-32" required>
                <button type="button" class="remove-rab bg-red-500 text-white px-2 py-1 rounded">X</button>
            </div>
        `;
        $('#rabs').append(html);
        rabIndex++;
    });

    // Hapus RAB
    $(document).on('click', '.remove-rab', function() {
        $(this).parent().remove();
    });

    // ======== TAMBAH ANGGOTA ========
    // UBAH: Index dimulai dari jumlah data yg ada
    let anggotaIndex = {{ count(old('anggotas', $usulan->anggotas->toArray())) }};
    $('#tambah-anggota').click(function() {
        let html = `
            <div class="flex gap-2 mb-2 items-center">
                <select name="anggotas[${anggotaIndex}][id_user]" class="select-dosen w-full border rounded p-2" required></select>
                <input type="text" name="anggotas[${anggotaIndex}][peran]" placeholder="Peran (opsional)" class="border rounded p-2 w-48">
                <button type="button" class="remove-anggota bg-red-500 text-white px-2 py-1 rounded">X</button>
            </div>
        `;
        $('#anggotas').append(html);
        initSelect2(); // Re-init select2 untuk row baru
        anggotaIndex++;
    });

    // Hapus Anggota
    $(document).on('click', '.remove-anggota', function() {
        $(this).parent().remove();
    });

});
</script>
@endsection