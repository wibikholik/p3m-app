@extends('reviewer.layouts.app')

@section('title', 'Dashboard')

@section('content')

<div class="py-10">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                Selamat datang, {{ Auth::user()->name }}! 👋<br>
                Ini adalah halaman dashboard khusus Reviewer.
            </div>
        </div>
    </div>
</div>

<h2 class="text-2xl font-bold text-gray-800 mb-6 mt-8">Dashboard</h2>

{{-- Statistik --}}
<div class="grid grid-cols-1 md:grid-cols-4 gap-6">

    <div class="bg-blue-600 text-white p-6 rounded-lg shadow">
        <h3 class="text-lg font-semibold">Total Usulan</h3>
        <p class="text-3xl font-bold mt-2">{{ $total_usulan }}</p>
    </div>

    <div class="bg-yellow-500 text-white p-6 rounded-lg shadow">
        <h3 class="text-lg font-semibold">Belum Dinilai</h3>
        <p class="text-3xl font-bold mt-2">{{ $belum_dinilai }}</p>
    </div>

    <div class="bg-green-600 text-white p-6 rounded-lg shadow">
        <h3 class="text-lg font-semibold">Sudah Dinilai</h3>
        <p class="text-3xl font-bold mt-2">{{ $sudah_dinilai }}</p>
    </div>

    <div class="bg-purple-600 text-white p-6 rounded-lg shadow">
        <h3 class="text-lg font-semibold">Menunggu Revisi</h3>
        <p class="text-3xl font-bold mt-2">{{ $menunggu_revisi }}</p>
    </div>

</div>

{{-- Daftar Usulan --}}
<div class="mt-10">
    <h3 class="text-xl font-bold text-gray-800 mb-4">Daftar Usulan yang Perlu Direview</h3>

    <div class="bg-white p-6 rounded-lg shadow overflow-x-auto">

        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-100 border-b">
                    <th class="p-3 font-semibold text-gray-700">Judul</th>
                    <th class="p-3 font-semibold text-gray-700">Pengusul</th>
                    <th class="p-3 font-semibold text-gray-700">Skema</th>
                    <th class="p-3 font-semibold text-gray-700">Tanggal Masuk</th>
                    <th class="p-3 font-semibold text-gray-700">Status</th>
                    <th class="p-3 font-semibold text-gray-700 text-center">Aksi</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($usulan as $item)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="p-3">{{ $item->judul }}</td>
                        <td class="p-3">{{ $item->pengusul_name }}</td>
                        <td class="p-3">{{ $item->skema }}</td>
                        <td class="p-3">{{ $item->created_at_formatted }}</td>

                        <td class="p-3">
                            @if ($item->review_status == 'assigned')
                                <span class="px-3 py-1 rounded bg-yellow-100 text-yellow-700 text-sm">Belum Dinilai</span>
                            @elseif ($item->review_status == 'done')
                                <span class="px-3 py-1 rounded bg-green-100 text-green-700 text-sm">Selesai</span>
                            @elseif ($item->review_status == 'revisi')
                                <span class="px-3 py-1 rounded bg-purple-100 text-purple-700 text-sm">Revisi</span>
                            @else
                                <span class="px-3 py-1 rounded bg-gray-200 text-gray-700 text-sm">{{ $item->review_status }}</span>
                            @endif
                        </td>

                        <td class="p-3 text-center">
                            <a href=""
                               class="bg-blue-600 text-white px-4 py-2 rounded-lg shadow hover:bg-blue-700 transition">
                                Review
                            </a>
                        </td>
                    </tr>

                @empty
                    <tr>
                        <td colspan="6" class="p-3 text-center text-gray-500">
                            Tidak ada usulan untuk direview.
                        </td>
                    </tr>
                @endforelse
            </tbody>

        </table>

    </div>
</div>

@endsection
