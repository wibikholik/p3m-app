@extends('admin.layouts.app')

@section('title', 'Edit Kelengkapan')
@section('page-title', 'Edit Kelengkapan')

@section('content')
<div class="max-w-4xl mx-auto mt-8 px-4 sm:px-6 lg:px-8">

    <div class="bg-white shadow rounded-lg p-6 border border-gray-200">

        <form action="{{ route('admin.kelengkapan.update', $item->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                {{-- NAMA --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700">Nama Kelengkapan</label>
                    <input type="text"
                           name="nama"
                           value="{{ $item->nama }}"
                           class="mt-1 w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                           required>
                </div>

                {{-- ORDER --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700">Urutan</label>
                    <input type="number"
                           name="order"
                           value="{{ $item->order }}"
                           class="mt-1 w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                </div>

                {{-- DESKRIPSI --}}
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700">Deskripsi</label>
                    <textarea name="deskripsi"
                              class="mt-1 w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                              rows="3">{{ $item->deskripsi }}</textarea>
                </div>

                {{-- STATUS --}}
                <div class="flex items-center mt-2">
                    <input type="checkbox"
                           name="is_active"
                           id="is_active"
                           class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                           {{ $item->is_active ? 'checked' : '' }}>
                    <label for="is_active" class="ml-2 text-gray-700">Aktif</label>
                </div>

            </div>

            <div class="mt-6 flex gap-3">
                <button class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 shadow">
                    Simpan Perubahan
                </button>

                <a href="{{ route('admin.kelengkapan.index') }}"
                   class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 shadow">
                    Batal
                </a>
            </div>

        </form>

    </div>

</div>
@endsection
