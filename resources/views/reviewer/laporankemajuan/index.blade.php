@extends('reviewer.layouts.app')

@section('title', 'Laporan Kemajuan')
@section('page-title', 'Daftar Laporan Kemajuan Dikirim')

@section('content')
<div class="max-w-7xl mx-auto mt-8 px-4 sm:px-6 lg:px-8">
    <div class="bg-white shadow overflow-hidden sm:rounded-lg border border-gray-200">
        @if($laporans->count())
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Judul Usulan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dosen</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Persentase</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($laporans as $index => $laporan)
                    <tr class="hover:bg-gray-50 transition-colors duration-150">
                        <td class="px-6 py-4 text-sm text-gray-500 text-center">{{ $index + 1 }}</td>
                        <td class="px-6 py-4 text-sm text-gray-700">{{ Str::limit($laporan->usulan->judul, 60) }}</td>
                        <td class="px-6 py-4 text-sm text-gray-700">{{ $laporan->usulan->user->name }}</td>
                        <td class="px-6 py-4 text-sm text-gray-700 text-center">{{ $laporan->persentase }}%</td>
                        <td class="px-6 py-4 text-right text-sm font-medium">
                            <a href="{{ route('reviewer.laporan-kemajuan.show', $laporan->id) }}" class="text-blue-600 hover:text-blue-900">Nilai</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="text-center py-12">
            <p class="text-gray-500">Belum ada laporan yang dikirim.</p>
        </div>
        @endif
    </div>
</div>
@endsection
