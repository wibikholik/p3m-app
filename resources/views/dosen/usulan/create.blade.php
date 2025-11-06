@extends('dosen.layouts.app')

@section('title', 'Ajukan Usulan')

@section('page-title', 'Ajukan Usulan')

@section('content')
<div class="max-w-4xl mx-auto mt-6 p-6 bg-white rounded-lg shadow">

    <h1 class="text-2xl font-bold mb-4">Ajukan Usulan untuk: {{ $pengumuman->judul }}</h1>
    <div class="text-right mb-4">
            <a class="text-blue-600 hover:underline" href="{{ route('dosen.pengumuman.index') }}">
            ← Kembali ke Daftar Pengumuman
        </a>
    </div>

    <form action="{{ route('dosen.usulan.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="id_pengumuman" value="{{ $pengumuman->id }}">

        {{-- Judul Usulan --}}
        <div class="mb-4">
            <label for="judul" class="block mb-1 font-medium">Judul Usulan</label>
            <input type="text" name="judul" id="judul" class="w-full border rounded p-2" required>
        </div>
        {{-- skema --}}
        <div class="mb-4">
            <label for="skema" class="block mb-1 font-medium">Skema</label>
            <input type="text" name="skema" id="skema" class="w-full border rounded p-2" required>
        </div>

        {{-- Deskripsi --}}
        <div class="mb-4">
            <label for="deskripsi" class="block mb-1 font-medium">Deskripsi</label>
            <textarea name="deskripsi" id="deskripsi" rows="4" class="w-full border rounded p-2" required></textarea>
        </div>

        {{-- Tahun Pelaksanaan --}}
        <div class="mb-4">
            <label for="tahun_pelaksanaan" class="block mb-1 font-medium">Tahun Pelaksanaan</label>
            <input type="text" name="tahun_pelaksanaan" id="tahun_pelaksanaan" class="w-full border rounded p-2" required>
        </div>

        {{-- Upload Proposal --}}
        <div class="mb-6">
            <label for="file_proposal" class="block mb-1 font-medium">Unggah Proposal (PDF)</label>
            <input type="file" name="file_proposal" id="file_proposal" accept="application/pdf" class="w-full">
        </div>

        {{-- RAB --}}
        <div class="mb-6">
            <h2 class="text-lg font-semibold mb-2">Rencana Anggaran Biaya (RAB)</h2>
            <div id="rabs">
                <div class="flex gap-2 mb-2 items-center">
                    <input type="text" name="rabs[0][nama_item]" placeholder="Nama Item" class="border rounded p-2 flex-1" required>
                    <input type="number" name="rabs[0][jumlah]" placeholder="Jumlah" class="border rounded p-2 w-24" required>
                    <input type="number" name="rabs[0][harga_satuan]" placeholder="Harga Satuan" class="border rounded p-2 w-32" required>
                    <button type="button" onclick="this.parentElement.remove()" class="bg-red-500 text-white px-2 py-1 rounded">X</button>
                </div>
            </div>
            <button type="button" onclick="tambahRAB()" class="bg-blue-600 text-white px-4 py-2 rounded mt-2">Tambah Item</button>
        </div>

        {{-- Anggota --}}
        <div class="mb-6">
            <h2 class="text-lg font-semibold mb-2">Anggota</h2>
            <div id="anggotas">
                <div class="flex gap-2 mb-2 items-center">
                    <input type="text" name="anggotas[0][nama]" placeholder="Nama Anggota" class="border rounded p-2 flex-1" required>
                    <input type="text" name="anggotas[0][peran]" placeholder="Peran (opsional)" class="border rounded p-2 w-48">
                    <button type="button" onclick="this.parentElement.remove()" class="bg-red-500 text-white px-2 py-1 rounded">X</button>
                </div>
            </div>
            <button type="button" onclick="tambahAnggota()" class="bg-blue-600 text-white px-4 py-2 rounded mt-2">Tambah Anggota</button>
        </div>
        
        {{-- Submit --}}
        <div class="text-right">
            <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded">Kirim Usulan</button>
        </div>
    </form>
</div>

{{-- Script untuk tambah RAB & Anggota --}}
<script>
let rabIndex = 1;
function tambahRAB(){
    const div = document.createElement('div');
    div.classList.add('flex','gap-2','mb-2','items-center');
    div.innerHTML = `
        <input type="text" name="rabs[${rabIndex}][nama_item]" placeholder="Nama Item" class="border rounded p-2 flex-1" required>
        <input type="number" name="rabs[${rabIndex}][jumlah]" placeholder="Jumlah" class="border rounded p-2 w-24" required>
        <input type="number" name="rabs[${rabIndex}][harga_satuan]" placeholder="Harga Satuan" class="border rounded p-2 w-32" required>
        <button type="button" onclick="this.parentElement.remove()" class="bg-red-500 text-white px-2 py-1 rounded">X</button>
    `;
    document.getElementById('rabs').appendChild(div);
    rabIndex++;
}

let anggotaIndex = 1;
function tambahAnggota(){
    const div = document.createElement('div');
    div.classList.add('flex','gap-2','mb-2','items-center');
    div.innerHTML = `
        <input type="text" name="anggotas[${anggotaIndex}][nama]" placeholder="Nama Anggota" class="border rounded p-2 flex-1" required>
        <input type="text" name="anggotas[${anggotaIndex}][peran]" placeholder="Peran (opsional)" class="border rounded p-2 w-48">
        <button type="button" onclick="this.parentElement.remove()" class="bg-red-500 text-white px-2 py-1 rounded">X</button>
    `;
    document.getElementById('anggotas').appendChild(div);
    anggotaIndex++;
}
</script>
@endsection
