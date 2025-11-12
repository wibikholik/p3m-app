@extends('dosen.layouts.app')

@section('title', 'Daftar Usulan')

@section('page-title', 'Daftar Usulan')

@section('content')
<div class="max-w-6xl mx-auto mt-6 p-6 bg-white rounded-lg shadow">

    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold">Daftar Usulan</h1>
        <a href="{{ route('dosen.pengumuman.index') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Buat Usulan Baru</a>
    </div>

    @if($usulans->count() > 0)
        <table class="w-full border-collapse border border-gray-300">
            <thead class="bg-gray-100">
                <tr>
                    <th class="border px-3 py-1">No</th>
                    <th class="border px-3 py-1">Judul Usulan</th>
                    <th class="border px-3 py-1">Pengumuman</th>
                    <th class="border px-3 py-1">Skema</th>
                    <th class="border px-3 py-1">Tahun</th>
                    <th class="border px-3 py-1">Status</th>
                    <th class="border px-3 py-1">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($usulans as $index => $usulan)
                    <tr>
                        <td class="border px-3 py-1">{{ $index + 1 }}</td>
                        <td class="border px-3 py-1">{{ $usulan->judul }}</td>
                        <td class="border px-3 py-1">{{ $usulan->pengumuman->judul ?? '-' }}</td>
                        <td class="border px-3 py-1">{{ $usulan->skema }}</td>
                        <td class="border px-3 py-1">{{ $usulan->tahun_pelaksanaan }}</td>
                        <td class="border px-3 py-1">{{ ucfirst($usulan->status) }}</td>
                        <td class="border px-3 py-1">
                            <a href="{{ route('dosen.usulan.show', $usulan->id) }}" class="text-blue-600 hover:underline">Detail</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p class="text-center text-gray-500 mt-6">Belum ada usulan yang diajukan.</p>
    @endif
</div>
@endsection
