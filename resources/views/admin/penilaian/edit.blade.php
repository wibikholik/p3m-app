@extends('admin.layouts.app')

@section('title', 'Edit Penilaian')
@section('page-title', 'Edit Komponen Penilaian')

@section('content')
<div class="max-w-3xl mx-auto bg-white shadow rounded-lg p-6 border border-gray-200">

    <form action="{{ route('admin.penilaian.update', $item->id) }}" method="POST">
        @csrf
        @method('PUT') {{-- WAJIB DITAMBAHKAN --}}

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700">Nama Kriteria</label>
            <input type="text" name="nama" value="{{ $item->nama }}"
                class="mt-1 w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                required>
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700">Bobot (%)</label>
            <input type="number" name="bobot" value="{{ $item->bobot }}"
                class="mt-1 w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
        </div>

        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700">Urutan</label>
            <input type="number" name="order" value="{{ $item->order }}"
                class="mt-1 w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                required>
        </div>

        <div class="flex justify-end space-x-2">
            <a href="{{ route('admin.penilaian.index') }}"
               class="px-4 py-2 bg-gray-300 text-gray-900 rounded-md hover:bg-gray-400">
                Kembali
            </a>

            <button type="submit"
                    class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 shadow">
                Simpan Perubahan
            </button>
        </div>
    </form>

</div>
@endsection
