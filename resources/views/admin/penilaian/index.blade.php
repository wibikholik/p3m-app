@extends('admin.layouts.app')

@section('title', 'Manajemen Penilaian')
@section('page-title', 'Manajemen Penilaian Proposal')

@section('content')
<div class="max-w-6xl mx-auto mt-8 px-4 sm:px-6 lg:px-8">

    {{-- ALERT SUCCESS --}}
    @if(session('success'))
        <div class="mb-4 px-4 py-3 bg-green-100 border border-green-300 text-green-800 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    {{-- FORM TAMBAH --}}
    <div class="bg-white shadow rounded-lg p-6 mb-6 border border-gray-200">
        <form action="{{ route('admin.penilaian.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">

                <div>
                    <label class="block text-sm font-medium text-gray-700">Nama Kriteria</label>
                    <input name="nama"
                           class="mt-1 w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                           placeholder="Contoh: Latar Belakang"
                           required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Bobot (%)</label>
                    <input name="bobot" type="number"
                           class="mt-1 w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                           placeholder="10"
                           min="1">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Urutan</label>
                    <input name="order" type="number"
                           class="mt-1 w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                           placeholder="1" min="0">
                </div>

                <div class="flex items-end">
                    <button class="w-full bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md shadow">
                        Tambah
                    </button>
                </div>
            </div>
        </form>
    </div>

    {{-- TABLE --}}
    <div class="bg-white shadow overflow-hidden sm:rounded-lg border border-gray-200">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Order</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Nama</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Bobot</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-700 uppercase">Status</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-700 uppercase w-40">Aksi</th>
                    </tr>
                </thead>

                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($items as $row)
                    <tr class="hover:bg-gray-50">

                        <td class="px-6 py-4">{{ $row->order }}</td>
                        <td class="px-6 py-4 font-semibold">{{ $row->nama }}</td>
                        <td class="px-6 py-4">{{ $row->bobot }}%</td>

                        <td class="px-6 py-4 text-center">
                            @if($row->is_active)
                                <span class="px-2 py-1 text-xs bg-green-100 text-green-800 rounded-full font-semibold">Aktif</span>
                            @else
                                <span class="px-2 py-1 text-xs bg-gray-200 text-gray-700 rounded-full font-semibold">Nonaktif</span>
                            @endif
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                            <div class="flex justify-end space-x-2">

                                <a href="{{ route('admin.penilaian.edit', $row->id) }}"
                                   class="px-3 py-1 bg-blue-500 text-white rounded-md hover:bg-blue-600">
                                    Edit
                                </a>

                                <form action="{{ route('admin.penilaian.toggle', $row->id) }}" method="POST">
                                    @csrf
                                    <button class="px-3 py-1 bg-yellow-500 text-white rounded-md hover:bg-yellow-600">
                                        {{ $row->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                    </button>
                                </form>

                                <form action="{{ route('admin.penilaian.destroy', $row->id) }}" method="POST"
                                      onsubmit="return confirm('Hapus komponen penilaian ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="px-3 py-1 bg-red-600 text-white rounded-md hover:bg-red-700">Hapus</button>
                                </form>

                            </div>
                        </td>

                    </tr>
                    @endforeach
                </tbody>

            </table>
        </div>
    </div>
</div>
@endsection
