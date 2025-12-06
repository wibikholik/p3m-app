@extends('admin.layouts.app')

@section('title', 'Daftar Usulan')
@section('page-title', 'Daftar Usulan')

@section('content')
<div class="max-w-7xl mx-auto mt-8 px-4 sm:px-6 lg:px-8">

    {{-- Header Section --}}
    <div class="sm:flex sm:items-center sm:justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Daftar Usulan</h1>
            <p class="mt-2 text-sm text-gray-700">Kelola seluruh usulan dari dosen ke P3M.</p>
        </div>

        {{-- Filter Status --}}
        <div class="mt-4 sm:mt-0">
            <form id="filterForm" action="{{ route('admin.usulan.index') }}" method="GET">
                <select name="status" onchange="document.getElementById('filterForm').submit()"
                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-2 border">
                    <option value="">Semua Status</option>
                    <option value="diajukan" {{ request('status') == 'diajukan' ? 'selected' : '' }}>Diajukan</option>
                    <option value="lolos_administrasi" {{ request('status') == 'lolos_administrasi' ? 'selected' : '' }}>Lolos Administrasi</option>
                    <option value="sedang_di_review" {{ request('status') == 'sedang_di_review' ? 'selected' : '' }}>Sedang di Review</option>
                    <option value="didanai" {{ request('status') == 'didanai' ? 'selected' : '' }}>Didanai</option>
                    <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                </select>
            </form>
        </div>
    </div>

    {{-- Table Section --}}
    <div class="bg-white shadow overflow-hidden sm:rounded-lg border border-gray-200">
        @if($usulan->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-12">No</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Judul Usulan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pengusul</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah Reviewer</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Reviewer Sudah Menilai</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Rata-rata Nilai</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($usulan as $index => $item)
                            <tr class="hover:bg-gray-50 transition-colors duration-150">
                                {{-- No --}}
                                <td class="px-6 py-4 text-center text-sm text-gray-500">{{ $index + 1 }}</td>

                                {{-- Judul --}}
                                <td class="px-6 py-4">
                                    <a href="{{ route('admin.usulan.show', $item->id) }}" class="text-blue-600 hover:underline">
                                        {{ Str::limit($item->judul, 60) }}
                                    </a>
                                    <div class="text-xs text-gray-500 mt-1">Tahun: {{ $item->created_at->year }}</div>
                                </td>

                                {{-- Pengusul --}}
                                <td class="px-6 py-4 text-sm text-gray-700">{{ $item->pengusul->name ?? 'Tidak ditemukan' }}</td>

                                {{-- Jumlah Reviewer --}}
                                <td class="px-6 py-4 text-center text-sm text-gray-700">
                                    {{ $item->reviewers_count ?? 0 }}
                                </td>

                                {{-- Reviewer Sudah Menilai --}}
                                <td class="px-6 py-4 text-center text-sm text-gray-700">
                                    {{ $item->reviewers_done ?? 0 }}
                                </td>

                                {{-- Rata-rata Nilai --}}
                                <td class="px-6 py-4 text-center text-sm text-gray-700">
                                    {{ $item->average_score ? number_format($item->average_score, 2) : '-' }}
                                </td>

                                {{-- Status --}}
                                <td class="px-6 py-4 text-center">
                                    @php
                                        $status = strtolower($item->status);
                                        $statusClasses = [
                                            'diajukan' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                                            'lolos_administrasi' => 'bg-blue-100 text-blue-800 border-blue-200',
                                            'sedang_di_review' => 'bg-purple-100 text-purple-800 border-purple-200',
                                            'didanai' => 'bg-green-500 text-white border-green-700',
                                            'ditolak' => 'bg-red-100 text-red-800 border-red-200',
                                            'revisi' => 'bg-orange-100 text-orange-800 border-orange-200',
                                        ];
                                        $class = $statusClasses[$status] ?? 'bg-gray-100 text-gray-800 border-gray-200';
                                    @endphp
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full border {{ $class }}">
                                        {{ str_replace('_',' ', ucfirst($item->status)) }}
                                    </span>
                                </td>

                                {{-- Aksi --}}
                                <td class="px-6 py-4 text-right text-sm font-medium flex justify-end gap-2">
                                    {{-- Detail --}}
                                    <a href="{{ route('admin.usulan.show', $item->id) }}"
                                       class="text-white bg-blue-600 hover:bg-blue-700 px-3 py-1 rounded-lg shadow-md transition">
                                        Detail
                                    </a>

                                    {{-- Rekap Penilaian --}}
                                    @if($item->reviewers_count > 0)
                                    <a href="{{ route('admin.usulan.rekap', $item->id) }}"
                                       class="text-white bg-green-600 hover:bg-green-700 px-3 py-1 rounded-lg shadow-md transition">
                                        Rekap
                                    </a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        @else
            <div class="text-center py-12 text-gray-500">
                Belum ada usulan.
            </div>
        @endif
    </div>
</div>
@endsection
