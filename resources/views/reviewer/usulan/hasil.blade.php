@extends('reviewer.layouts.app')

@section('title', 'Hasil Penilaian: ' . $usulan->judul)
@section('page-title', 'Hasil Penilaian')

@section('content')
<div class="max-w-7xl mx-auto mt-8 px-4 sm:px-6 lg:px-8">

    {{-- Flash message --}}
    @if(session('success'))
        <div class="mb-4 p-3 bg-green-50 border border-green-200 text-green-700 rounded">
            {{ session('success') }}
        </div>
    @endif

    {{-- Judul Usulan --}}
    <h1 class="text-2xl font-bold mb-4">{{ $usulan->judul }}</h1>

    {{-- Info Usulan --}}
    <div class="bg-white shadow rounded-lg p-4 mb-6">
        <p><strong>Pengusul:</strong> {{ $usulan->pengusul->name ?? '-' }}</p>
        <p><strong>Skema:</strong> {{ $usulan->skema }}</p>
        <p><strong>Tahun:</strong> {{ \Carbon\Carbon::parse($usulan->created_at)->year }}</p>
    </div>

    {{-- Hasil Review Awal --}}
    <div class="bg-white shadow rounded-lg p-6 mb-6">
        <h3 class="text-lg font-semibold mb-4">Hasil Review Awal</h3>

        @if($penilaian->isEmpty())
            <p class="text-gray-500">Belum ada penilaian untuk review awal.</p>
        @else
            <table class="min-w-full border border-gray-200 mb-4">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-2 border">Komponen</th>
                        <th class="px-4 py-2 border">Bobot (%)</th>
                        <th class="px-4 py-2 border">Nilai</th>
                        <th class="px-4 py-2 border">Catatan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($penilaian as $p)
                        <tr>
                            <td class="px-4 py-2 border">{{ $p->komponen->nama }}</td>
                            <td class="px-4 py-2 border">{{ $p->komponen->bobot }}</td>
                            <td class="px-4 py-2 border">{{ $p->nilai }}</td>
                            <td class="px-4 py-2 border">{{ $p->catatan }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <p class="font-semibold">Total Nilai Awal: {{ number_format($totalNilai, 2) }}</p>
        @endif
    </div>

    {{-- Hasil Review Revisi --}}
    @if(isset($penilaianRevisiFull) && $penilaianRevisiFull->isNotEmpty())
    <div class="bg-white shadow rounded-lg p-6 mb-6">
        <h3 class="text-lg font-semibold mb-4">Hasil Review Revisi</h3>

        <table class="min-w-full border border-gray-200 mb-4">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-2 border">Komponen</th>
                    <th class="px-4 py-2 border">Bobot (%)</th>
                    <th class="px-4 py-2 border">Nilai</th>
                    <th class="px-4 py-2 border">Catatan</th>
                </tr>
            </thead>
            <tbody>
                @foreach($penilaianRevisiFull as $p)
                    <tr>
                        <td class="px-4 py-2 border">{{ $p->komponen->nama }}</td>
                        <td class="px-4 py-2 border">{{ $p->komponen->bobot }}</td>
                        <td class="px-4 py-2 border">{{ $p->nilai }}</td>
                        <td class="px-4 py-2 border">{{ $p->catatan }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <p class="font-semibold">Total Nilai Revisi: {{ number_format($totalNilaiRevisi, 2) }}</p>
    </div>
    @endif

    {{-- Tombol Kembali --}}
    <div class="mt-4">
        <a href="{{ route('reviewer.usulan.index') }}" 
           class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">
           Kembali ke Daftar Usulan
        </a>
    </div>

</div>
@endsection
