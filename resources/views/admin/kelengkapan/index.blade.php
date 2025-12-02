@extends('admin.layouts.app')

@section('title', 'Manajemen Kelengkapan')
@section('page-title', 'Manajemen Kelengkapan')

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
        <form action="{{ route('admin.kelengkapan.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Nama Kelengkapan</label>
                    <input name="nama"
                           class="mt-1 w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                           placeholder="Contoh: Kelengkapan Proposal"
                           required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Deskripsi</label>
                    <input name="deskripsi"
                           class="mt-1 w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                           placeholder="Opsional">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Urutan</label>
                    <input name="order"
                           type="number"
                           class="mt-1 w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                           placeholder="0"
                           min="0">
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
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Urut</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Nama</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Deskripsi</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-700 uppercase">Status</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-700 uppercase w-40">Aksi</th>
                    </tr>
                </thead>

                <tbody class="bg-white divide-y divide-gray-200">

                    @foreach($items as $it)
                    <tr class="hover:bg-gray-50">

                        <td class="px-6 py-4 text-sm text-gray-700">
                            {{ $it->order }}
                        </td>

                        <td class="px-6 py-4 text-sm font-semibold text-gray-800">
                            {{ $it->nama }}
                        </td>

                        <td class="px-6 py-4 text-sm text-gray-600">
                            {{ $it->deskripsi }}
                        </td>

                        <td class="px-6 py-4 text-center">
                            @if($it->is_active)
                                <span class="px-2 py-1 text-xs font-semibold bg-green-100 text-green-800 rounded-full">Aktif</span>
                            @else
                                <span class="px-2 py-1 text-xs font-semibold bg-gray-200 text-gray-700 rounded-full">Nonaktif</span>
                            @endif
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm">

                            <div class="flex justify-end space-x-2">

                                {{-- Edit --}}
                                <a href="{{ route('admin.kelengkapan.edit', $it->id) }}"
                                   class="px-3 py-1 bg-blue-500 text-white rounded-md hover:bg-blue-600">
                                    Edit
                                </a>

                                {{-- Toggle --}}
                                <form action="{{ route('admin.kelengkapan.toggle', $it->id) }}" method="POST">
                                    @csrf
                                    <button class="px-3 py-1 bg-yellow-500 text-white rounded-md hover:bg-yellow-600">
                                        {{ $it->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                    </button>
                                </form>

                                {{-- Hapus --}}
                                <form action="{{ route('admin.kelengkapan.destroy', $it->id) }}"
                                      method="POST"
                                      onsubmit="return confirm('Hapus kelengkapan ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="px-3 py-1 bg-red-600 text-white rounded-md hover:bg-red-700">
                                        Hapus
                                    </button>
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
