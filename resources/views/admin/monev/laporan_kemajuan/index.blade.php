@extends('admin.layouts.app')

@section('title', 'Monev Laporan Kemajuan')
@section('page-title', 'Monev Laporan Kemajuan')

@section('content')
<div class="max-w-7xl mx-auto mt-8 px-4 sm:px-6 lg:px-8">
    <h1 class="text-3xl font-bold mb-6 text-gray-800">Monitoring Laporan Kemajuan</h1>

    @if(session('success'))
        <div class="bg-green-100 text-green-800 p-4 rounded-lg mb-6 border border-green-200">
            {{ session('success') }}
        </div>
    @endif
    
    <div class="bg-white shadow overflow-hidden sm:rounded-lg border border-gray-200">
        
        {{-- Perbaikan Undefined Variable: Menggunakan $laporans --}}
        @if($laporans->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-12">No</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Judul Usulan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pengusul</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Progress</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Status Review</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($laporans as $index => $laporan)
                            <tr class="hover:bg-gray-50 transition-colors duration-150">
                                <td class="px-6 py-4 text-center text-sm text-gray-500">{{ $index + 1 }}</td>

                                <td class="px-6 py-4">
                                    <a href="{{ route('admin.monev.laporan_kemajuan.show', $laporan->id) }}" class="text-blue-600 hover:underline">
                                        {{ Str::limit($laporan->usulan->judul, 60) }}
                                    </a>
                                </td>

                                <td class="px-6 py-4 text-sm text-gray-700">{{ $laporan->usulan->pengusul->name ?? 'N/A' }}</td>
                                
                                <td class="px-6 py-4 text-center text-sm text-gray-700">
                                    {{ $laporan->persentase ?? 0 }}%
                                </td>

                                <td class="px-6 py-4 text-center">
                                    @php
                                        $status = $laporan->status;
                                        $class = [
                                            'Terkirim' => 'bg-yellow-100 text-yellow-800',
                                            'Disetujui' => 'bg-green-100 text-green-800',
                                            'Ditolak' => 'bg-red-100 text-red-800',
                                            'Perbaikan' => 'bg-orange-100 text-orange-800',
                                        ][$status] ?? 'bg-gray-100 text-gray-800';
                                    @endphp
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $class }}">
                                        {{ $status }}
                                    </span>
                                </td>

                                <td class="px-6 py-4 text-center text-sm font-medium">
                                    <a href="{{ route('admin.monev.laporan_kemajuan.show', $laporan->id) }}"
                                       class="text-white bg-blue-600 hover:bg-blue-700 px-3 py-1 rounded-lg shadow-md transition whitespace-nowrap">
                                        Monev Detail
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        @else
            <div class="text-center py-12 text-gray-500">
                Belum ada Laporan Kemajuan yang tersedia untuk di-Monev.
            </div>
        @endif
    </div>
</div>
@endsection