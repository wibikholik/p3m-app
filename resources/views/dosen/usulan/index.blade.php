@extends('dosen.layouts.app')

@section('title', 'Daftar Usulan')
@section('page-title', 'Daftar Usulan')

@section('content')
<div class="max-w-7xl mx-auto mt-8 px-4 sm:px-6 lg:px-8">

    {{-- Header Section --}}
    <div class="sm:flex sm:items-center sm:justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Daftar Usulan</h1>
            <p class="mt-2 text-sm text-gray-700">Kelola semua usulan penelitian dan pengabdian yang telah Anda ajukan.</p>
        </div>
        <div class="mt-4 sm:mt-0">
            <a href="{{ route('dosen.pengumuman.index') }}" class="inline-flex items-center justify-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Buat Usulan Baru
            </a>
        </div>
    </div>

    {{-- Table Section --}}
    <div class="bg-white shadow overflow-hidden sm:rounded-lg border border-gray-200">
        @if(isset($usulans) && $usulans->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-green-500">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-16">
                                No
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Judul & Pengumuman
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Skema
                            </th>
                            <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Tahun
                            </th>
                            <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status
                            </th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($usulans as $index => $usulan)
                            <tr class="hover:bg-gray-50 transition-colors duration-150">
                                {{-- No --}}
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                    {{ $index + 1 }}
                                </td>

                                {{-- Judul & Pengumuman --}}
                                <td class="px-6 py-4">
                                    <div class="text-sm font-semibold text-gray-900 hover:text-blue-600 transition-colors">
                                        <a href="{{ route('dosen.usulan.show', $usulan->id) }}">
                                            {{ Str::limit($usulan->judul, 60) }}
                                        </a>
                                    </div>
                                    <div class="text-xs text-gray-500 mt-1">
                                        <span class="bg-gray-100 text-gray-600 px-2 py-0.5 rounded">
                                            {{ $usulan->pengumuman->judul ?? 'Pengumuman Dihapus' }}
                                        </span>
                                    </div>
                                </td>

                                {{-- Skema --}}
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                    {{ $usulan->skema }}
                                </td>

                                {{-- Tahun --}}
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                    {{ $usulan->created_at->year }}
                                </td>

                                {{-- Status Badge --}}
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    @php
                                        $statusClasses = [
                                            'diajukan' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                                            'diterima' => 'bg-green-100 text-green-800 border-green-200',
                                            'ditolak'  => 'bg-red-100 text-red-800 border-red-200',
                                            'revisi'   => 'bg-orange-100 text-orange-800 border-orange-200',
                                        ];
                                        $currentStatus = strtolower($usulan->status);
                                        $class = $statusClasses[$currentStatus] ?? 'bg-gray-100 text-gray-800 border-gray-200';
                                    @endphp
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full border {{ $class }}">
                                        {{ ucfirst($usulan->status) }}
                                    </span>
                                </td>

                                {{-- Aksi --}}
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex justify-end gap-3">
                                        <a href="{{ route('dosen.usulan.show', $usulan->id) }}" class="text-blue-600 hover:text-blue-900 bg-blue-50 px-3 py-1 rounded hover:bg-blue-100 transition">
                                            Detail
                                        </a>
                                        @if($currentStatus == 'diajukan' || $currentStatus == 'revisi')
                                            <a href="{{ route('dosen.usulan.edit', $usulan->id) }}" class="text-yellow-600 hover:text-yellow-900 bg-yellow-50 px-3 py-1 rounded hover:bg-yellow-100 transition">
                                                Edit
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            {{-- Empty State --}}
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">Belum ada usulan</h3>
                <p class="mt-1 text-sm text-gray-500">Mulai dengan memilih pengumuman dan ajukan proposal Anda.</p>
                <div class="mt-6">
                    <a href="{{ route('dosen.pengumuman.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Ajukan Usulan Baru
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection