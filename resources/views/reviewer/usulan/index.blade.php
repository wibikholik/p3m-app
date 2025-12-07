@extends('reviewer.layouts.app')

@section('title', 'Daftar Tugas Review')

@section('content')
<div class="max-w-7xl mx-auto mt-8 px-4 sm:px-6 lg:px-8">
    <h1 class="text-3xl font-bold mb-6 text-gray-800">Daftar Tugas Review</h1>

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
                    <th class="px-6 py-3 text-center text-sm font-medium text-gray-700 uppercase">Deadline</th>
                    <th class="px-6 py-3 text-center text-sm font-medium text-gray-700 uppercase">Status Review</th>
                    <th class="px-6 py-3 text-center text-sm font-medium text-gray-700 uppercase">Status Revisi</th>
                    <th class="px-6 py-3 text-center text-sm font-medium text-gray-700 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($usulans as $index => $item)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4 text-center">{{ $index + 1 }}</td>
                    <td class="px-6 py-4">
                        <a href="{{ route('reviewer.usulan.review', $item->id) }}" class="text-blue-600 hover:underline">
                            {{ Str::limit($item->judul, 60) }}
                        </a>
                    </td>
                    <td class="px-6 py-4 text-center">{{ $item->deadline ? \Carbon\Carbon::parse($item->deadline)->format('d M Y') : '-' }}</td>
                    <td class="px-6 py-4 text-center">
                        @if($item->sudah_direview)
                            <span class="px-3 py-1 bg-green-500 text-white rounded-full text-sm font-semibold">Sudah Direview</span>
                        @else
                            <span class="px-3 py-1 bg-yellow-500 text-white rounded-full text-sm font-semibold">Belum Direview</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-center">
                        {{-- STATUS REVISI DARI TABEL USULAN --}}
                        @switch($item->status_revisi)
                            @case('diajukan')
                            @case('revisi_diajukan')
                                <span class="px-3 py-1 bg-orange-500 text-white rounded-full text-sm font-semibold">Revisi Diajukan</span>
                                @break
                            @case('menunggu_verifikasi')
                                <span class="px-3 py-1 bg-orange-500 text-white rounded-full text-sm font-semibold">Revisi Diajukan</span>
                                @break
                            @case('dikembalikan')
                                <span class="px-3 py-1 bg-red-500 text-white rounded-full text-sm font-semibold">Menunggu Pengusul</span>
                                @break
                            @case('disetujui')
                                <span class="px-3 py-1 bg-indigo-500 text-white rounded-full text-sm font-semibold">Revisi Selesai Dinilai</span>
                                @break
                            @default
                                <span class="px-3 py-1 bg-gray-400 text-white rounded-full text-sm font-semibold">Tidak Ada Revisi</span>
                        @endswitch
                    </td>
                    <td class="px-6 py-4 text-center">
                        {{-- AKSI --}}
                        @if($item->status_revisi == 'menunggu_verifikasi' || $item->status_revisi == 'revisi_diajukan')
                            {{-- Jika revisi diajukan, beri tombol review revisi --}}
                            <a href="{{ route('reviewer.usulan.review_revisi', $item->id) }}" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">Review Revisi</a>
                        @elseif($item->sudah_direview)
                            {{-- Jika sudah review awal (dan tidak ada revisi yang aktif), beri tombol lihat hasil --}}
                            <a href="{{ route('reviewer.penilaian.hasil', $item->id) }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Lihat Hasil</a>
                        @else
                            {{-- Jika belum review awal --}}
                            <a href="{{ route('reviewer.usulan.review', $item->id) }}" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Review</a>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-6 text-gray-500">Belum ada tugas review.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection