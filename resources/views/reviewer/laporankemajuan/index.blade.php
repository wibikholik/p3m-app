@extends('reviewer.layouts.app')

@section('title', 'Tugas Review Laporan Kemajuan')

@section('content')
<div class="max-w-7xl mx-auto mt-8 px-4 sm:px-6 lg:px-8">
    <h1 class="text-3xl font-bold mb-6 text-gray-800">Daftar Laporan Kemajuan untuk Direview</h1>

    @if(session('success'))
        <div class="bg-green-100 text-green-800 p-4 rounded-lg mb-6 border border-green-200">
            {{ session('success') }}
        </div>
    @endif

    <div class="overflow-x-auto">
        <table class="min-w-full bg-white border border-gray-200 shadow rounded-lg overflow-hidden">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-700 uppercase">No</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-700 uppercase">Judul Usulan</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-700 uppercase">Pengusul</th>
                    <th class="px-6 py-3 text-center text-sm font-medium text-gray-700 uppercase">Status Laporan</th>
                    <th class="px-6 py-3 text-center text-sm font-medium text-gray-700 uppercase">Status Review Saya</th>
                    <th class="px-6 py-3 text-center text-sm font-medium text-gray-700 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($laporans as $index => $laporan)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4 text-center">{{ $index + 1 }}</td>
                    <td class="px-6 py-4">
                        <a href="{{ route('reviewer.laporan-kemajuan.show', $laporan->id) }}" class="text-blue-600 hover:underline">
                            {{ Str::limit($laporan->usulan->judul, 70) }}
                        </a>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-700">
                        {{ $laporan->usulan->pengusul->name ?? 'N/A' }}
                    </td>
                    <td class="px-6 py-4 text-center">
                        @php
                            $statusMap = [
                                'Terkirim' => ['bg' => 'yellow', 'text' => 'Menunggu Review'],
                                'Disetujui' => ['bg' => 'green', 'text' => 'Disetujui'],
                                'Ditolak' => ['bg' => 'red', 'text' => 'Ditolak'],
                                'Perbaikan' => ['bg' => 'orange', 'text' => 'Perlu Perbaikan'],
                            ];
                            $class = $statusMap[$laporan->status] ?? ['bg' => 'gray', 'text' => 'Tidak Diketahui'];
                        @endphp
                        <span class="px-3 py-1 bg-{{ $class['bg'] }}-500 text-white rounded-full text-sm font-semibold">
                            {{ $class['text'] }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        @if($laporan->status_review_saya == 'Perlu Dinilai')
                            <span class="px-3 py-1 bg-red-500 text-white rounded-full text-sm font-semibold">Perlu Dinilai</span>
                        @else
                            <span class="px-3 py-1 bg-green-500 text-white rounded-full text-sm font-semibold">Selesai Dinilai</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-center">
                        <a href="{{ route('reviewer.laporan-kemajuan.show', $laporan->id) }}" 
                           class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">
                            {{ $laporan->status_review_saya == 'Perlu Dinilai' ? 'Nilai Laporan' : 'Lihat Hasil' }}
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-6 text-gray-500">Belum ada tugas review laporan kemajuan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection