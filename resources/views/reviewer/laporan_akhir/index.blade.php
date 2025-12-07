@extends('reviewer.layouts.app')

@section('title', 'Tugas Review Laporan Akhir')
@section('page-title', 'Tugas Review Laporan Akhir')

@section('content')
<div class="max-w-7xl mx-auto mt-8 px-4 sm:px-6 lg:px-8">
    <h1 class="text-3xl font-bold mb-6 text-gray-800">Daftar Laporan Akhir untuk Direview</h1>

    @if(session('success'))
        <div class="bg-green-100 text-green-800 p-4 rounded-lg mb-6 border border-green-200">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white shadow overflow-hidden sm:rounded-lg border border-gray-200">
        
        @if($laporanAkhirs->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-12">No</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Judul Usulan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pengusul</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Target Publikasi</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Status Review</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($laporanAkhirs as $index => $laporanAkhir)
                            <tr class="hover:bg-gray-50 transition-colors duration-150">
                                <td class="px-6 py-4 text-center text-sm text-gray-500">{{ $index + 1 }}</td>

                                <td class="px-6 py-4">
                                    <a href="{{ route('reviewer.laporan_akhir.show', $laporanAkhir->id) }}" class="text-blue-600 hover:underline">
                                        {{ Str::limit($laporanAkhir->usulan->judul, 60) }}
                                    </a>
                                </td>

                                <td class="px-6 py-4 text-sm text-gray-700">
                                    {{ $laporanAkhir->usulan->pengusul->name ?? 'N/A' }}
                                </td>
                                
                                <td class="px-6 py-4 text-center text-sm text-gray-700">
                                    {{ Str::limit($laporanAkhir->publikasi_target, 25) ?? '-' }}
                                </td>

                                <td class="px-6 py-4 text-center">
                                    @php
                                        $status = $laporanAkhir->status;
                                        $class = match($status) {
                                            'Terkirim' => 'bg-yellow-100 text-yellow-800',
                                            'Perbaikan' => 'bg-orange-100 text-orange-800',
                                            default => 'bg-green-100 text-green-800', // Sudah Dinilai (Disetujui/Ditolak)
                                        };
                                        $displayText = $status == 'Terkirim' ? 'Perlu Dinilai' : $status;
                                    @endphp
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $class }}">
                                        {{ $displayText }}
                                    </span>
                                </td>

                                <td class="px-6 py-4 text-center text-sm font-medium">
                                    <a href="{{ route('reviewer.laporan_akhir.show', $laporanAkhir->id) }}"
                                       class="text-white bg-indigo-600 hover:bg-indigo-700 px-3 py-1 rounded-lg shadow-md transition whitespace-nowrap">
                                        {{ $laporanAkhir->status == 'Terkirim' ? 'Nilai Laporan' : 'Lihat Hasil' }}
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        @else
            <div class="text-center py-12 text-gray-500">
                Belum ada tugas review Laporan Akhir.
            </div>
        @endif
    </div>
</div>
@endsection