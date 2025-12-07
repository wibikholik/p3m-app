@extends('dosen.layouts.app')

@section('title', 'Daftar Tugas Laporan Akhir')
@section('page-title', 'Daftar Tugas Laporan Akhir')

@section('content')
<div class="max-w-7xl mx-auto mt-8 px-4 sm:px-6 lg:px-8">
    <h1 class="text-3xl font-bold mb-6 text-gray-800">Tugas Pengajuan Laporan Akhir</h1>

    @if(session('success'))
        <div class="bg-green-100 text-green-800 p-4 rounded-lg mb-6 border border-green-200">
            {{ session('success') }}
        </div>
    @endif
    
    @if(session('error'))
        <div class="bg-red-100 text-red-800 p-4 rounded-lg mb-6 border border-red-200">
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-white shadow overflow-hidden sm:rounded-lg border border-gray-200">
        
        @if($usulans->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-12">No</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Judul Usulan</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Status Tahap</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Status Laporan</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($usulans as $index => $usulan)
                            @php
                                $laporanAkhir = $usulan->laporanAkhir;
                                $statusLaporan = $laporanAkhir ? $laporanAkhir->status : 'Belum Dibuat';
                                $isSubmitted = $laporanAkhir && $laporanAkhir->status != 'Draft' && $laporanAkhir->status != 'Perbaikan';
                            @endphp
                            <tr class="hover:bg-gray-50 transition-colors duration-150">
                                <td class="px-6 py-4 text-center text-sm text-gray-500">{{ $index + 1 }}</td>

                                <td class="px-6 py-4">
                                    <span class="text-gray-900 font-medium">
                                        {{ Str::limit($usulan->judul, 70) }}
                                    </span>
                                    <div class="text-xs text-gray-500 mt-1">Status Usulan: {{ $usulan->status }}</div>
                                </td>
                                
                                <td class="px-6 py-4 text-center text-sm text-gray-700">
                                    {{ str_replace('_', ' ', ucfirst($usulan->status_lanjut)) }}
                                </td>

                                <td class="px-6 py-4 text-center">
                                    @php
                                        $color = match($statusLaporan) {
                                            'Belum Dibuat' => 'bg-gray-100 text-gray-700',
                                            'Draft' => 'bg-blue-100 text-blue-700',
                                            'Terkirim' => 'bg-yellow-100 text-yellow-700',
                                            'Perbaikan' => 'bg-orange-100 text-orange-700',
                                            'Disetujui' => 'bg-green-100 text-green-700',
                                            'Ditolak' => 'bg-red-100 text-red-700',
                                            default => 'bg-gray-100 text-gray-700',
                                        };
                                    @endphp
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $color }}">
                                        {{ $statusLaporan }}
                                    </span>
                                </td>

                                <td class="px-6 py-4 text-center text-sm font-medium">
                                    <a href="{{ route('dosen.laporan_akhir.form', $usulan->id) }}"
                                       class="text-white px-3 py-1 rounded-lg shadow-md transition whitespace-nowrap
                                            {{ $statusLaporan == 'Belum Dibuat' ? 'bg-indigo-600 hover:bg-indigo-700' : 'bg-blue-600 hover:bg-blue-700' }}">
                                        {{ $statusLaporan == 'Belum Dibuat' ? 'Buat Laporan' : 'Lihat / Edit' }}
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        @else
            <div class="text-center py-12 text-gray-500">
                Belum ada usulan yang siap atau ditugaskan untuk Laporan Akhir.
            </div>
        @endif
    </div>
</div>
@endsection