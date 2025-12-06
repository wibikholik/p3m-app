@extends('dosen.layouts.app')

@section('title', 'Laporan Kemajuan')
@section('page-title', 'Laporan Kemajuan')

@section('content')

<div class="max-w-7xl mx-auto mt-8 px-4 sm:px-6 lg:px-8">

    <div class="sm:flex sm:items-center sm:justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Laporan Kemajuan</h1>
            <p class="mt-2 text-sm text-gray-700">
                Daftar laporan kemajuan untuk usulan yang telah diterima.
            </p>
        </div>
    </div>

    <div class="bg-white shadow overflow-hidden sm:rounded-lg border border-gray-200">

        @if($laporans->count() > 0)

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Judul Usulan</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Tahun</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>

                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($laporans as $index => $laporan)
                    <tr class="hover:bg-gray-50 transition-colors">

                        {{-- No --}}
                        <td class="px-6 py-4 text-sm text-gray-500 text-center">
                            {{ $index + 1 }}
                        </td>

                        {{-- Judul Usulan --}}
                        <td class="px-6 py-4">
                            <div class="text-sm font-semibold text-gray-900">
                                {{ Str::limit($laporan->usulan->judul, 70) }}
                            </div>
                        </td>

                        {{-- Tahun --}}
                        <td class="px-6 py-4 text-sm text-gray-500 text-center">
                            {{ $laporan->created_at->year }}
                        </td>

                        {{-- Status --}}
                        <td class="px-6 py-4 text-center">
                            @php
                                $stat = strtolower($laporan->status);
                                $badge = 'bg-gray-100 text-gray-800 border-gray-200';

                                if ($stat === 'belum dikirim') {
                                    $badge = 'bg-yellow-100 text-yellow-800 border-yellow-200';
                                } elseif ($stat === 'dikirim') {
                                    $badge = 'bg-blue-100 text-blue-800 border-blue-200';
                                } elseif ($stat === 'disetujui') {
                                    $badge = 'bg-green-100 text-green-800 border-green-200';
                                } elseif ($stat === 'ditolak') {
                                    $badge = 'bg-red-100 text-red-800 border-red-200';
                                }
                            @endphp

                            <span class="px-3 py-1 inline-flex text-xs font-semibold rounded-full border {{ $badge }}">
                                {{ ucfirst($laporan->status) }}
                            </span>
                        </td>

                        {{-- Aksi --}}
                        <td class="px-6 py-4 text-right text-sm font-medium flex justify-end gap-3">

                            {{-- Lihat Detail --}}
                            <a href="{{ route('dosen.laporan-kemajuan.show', $laporan->id) }}"
                                class="text-blue-600 hover:text-blue-900 bg-blue-50 px-3 py-1 rounded hover:bg-blue-100 transition">
                                Detail
                            </a>

                            {{-- Edit jika belum disetujui --}}
                            @if(in_array($laporan->status, ['Belum dikirim', 'Ditolak']))
                                <a href="{{ route('dosen.laporan-kemajuan.edit', $laporan->id) }}"
                                    class="text-yellow-600 hover:text-yellow-900 bg-yellow-50 px-3 py-1 rounded hover:bg-yellow-100 transition">
                                    Edit
                                </a>
                            @endif
                            <form action="{{ route('dosen.laporan-kemajuan.destroy', $laporan->id) }}"
                                method="POST"
                                onsubmit="return confirm('Yakin ingin menghapus laporan ini?');">
                                @csrf
                                @method('DELETE')

                                <button class="px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700">
                                    Hapus
                                </button>
                            </form>

                        </td>
                    </tr>
                    @endforeach
                </tbody>

            </table>
        </div>

        @else

        {{-- Empty State --}}
        <div class="text-center py-12">
            <svg class="mx-auto h-12 w-12 text-gray-400"
                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">Belum ada laporan kemajuan</h3>
            <p class="mt-1 text-sm text-gray-500">Usulan Anda harus diterima terlebih dahulu.</p>
        </div>

        @endif

    </div>
</div>

@endsection
