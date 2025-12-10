@extends('dosen.layouts.app')

@section('title', 'Dashboard Dosen P3M')

@section('content')

<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
    <div class="bg-white overflow-hidden shadow-xl rounded-xl p-8 mb-8 border border-gray-100">
        <div class="text-2xl font-semibold text-gray-900 flex items-center">
            👋 Selamat datang kembali, <span class="text-blue-600 ml-2">{{ Auth::user()->name ?? 'Dosen' }}</span>!
        </div>
        <p class="mt-2 text-gray-600">Ini adalah halaman ringkasan aktivitas penelitian dan pengabdian Anda.</p>
    </div>

    <h2 class="text-3xl font-extrabold text-gray-900 mb-6">Ringkasan Metrik Kunci</h2>

    {{-- Asumsi: Variabel $summaryData dan $lastProposals sudah tersedia dari Controller --}}
    @php
        // Ambil data dari variabel yang dilewatkan oleh controller
        $totalUsulan = $summaryData['total_usulan'] ?? 0;
        $penelitianAktif = $summaryData['penelitian_aktif'] ?? 0;
        $pengabdianAktif = $summaryData['pengabdian_aktif'] ?? 0;
        $menungguReview = $summaryData['menunggu_review'] ?? 0;
        $usulanDisetujui = $summaryData['usulan_disetujui'] ?? 0; // Ini adalah metrik 'Diterima' dari Controller
        $usulanDitolak = $summaryData['usulan_ditolak'] ?? 0;

        // Ambil usulan yang paling baru (first() dari $lastProposals) untuk Tracking
        $latestProposal = $lastProposals->first();
        $currentStatus = $latestProposal->status ?? 'Draft';

        // Definisikan urutan langkah tracking
        $trackingSteps = [
            'Diajukan' => [
                'label' => '1. Pengajuan Usulan', 
                'is_complete' => in_array($currentStatus, ['Diajukan', 'Menunggu Review', 'Diterima', 'Sedang Berjalan', 'Ditolak'])
            ],
            'Menunggu Review' => [
                'label' => '2. Dalam Proses Review', 
                'is_complete' => in_array($currentStatus, ['Menunggu Review', 'Diterima', 'Sedang Berjalan', 'Ditolak'])
            ],
            'Diterima' => [ // Mengganti 'Disetujui' dengan 'Diterima'
                'label' => '3. Keputusan Final (Diterima)', 
                'is_complete' => in_array($currentStatus, ['Diterima', 'Sedang Berjalan'])
            ],
        ];
        
        $isFinished = in_array($currentStatus, ['Sedang Berjalan', 'Ditolak', 'Diterima']);
        $isRejected = ($currentStatus == 'Ditolak');

        // Logic untuk warna garis tracking (PERBAIKAN LINE COLOR)
        $progressCount = 0;
        foreach ($trackingSteps as $step) {
            if ($step['is_complete']) {
                $progressCount++;
            }
        }
        
        $lineColor = 'bg-gray-300'; // Default
        
        if ($progressCount > 0) {
            $lineColor = 'bg-blue-500'; // Ada progres, garis biru
        }
        
        if (in_array($currentStatus, ['Diterima', 'Sedang Berjalan','Selesai'])) {
            $lineColor = 'bg-green-500'; // Status sukses, garis hijau
        }

        if ($isRejected) {
            $lineColor = 'bg-red-500'; // Status gagal/ditolak, garis merah (override)
        }

        // Logic untuk Placeholder Chart (Progress Bars)
        $totalForChart = $usulanDisetujui + $usulanDitolak + $menungguReview;
        $percentDisetujui = $totalForChart > 0 ? round(($usulanDisetujui / $totalForChart) * 100) : 0;
        $percentDitolak = $totalForChart > 0 ? round(($usulanDitolak / $totalForChart) * 100) : 0;
        $percentReview = $totalForChart > 0 ? round(($menungguReview / $totalForChart) * 100) : 0;

    @endphp

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
        
        <div class="bg-white shadow-lg rounded-xl p-6 border-t-4 border-blue-500 transition duration-300 hover:shadow-xl">
            <div class="flex items-center justify-between">
                <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider">Total Semua Usulan</h3>
                <svg class="w-8 h-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            </div>
            <p class="mt-4 text-3xl font-extrabold text-blue-600">
                {{ $totalUsulan }}
            </p>
            <a href="{{ route('dosen.usulan.index') }}" class="text-xs text-blue-500 hover:text-blue-700 font-medium mt-2 block">Lihat Daftar Usulan &rarr;</a>
        </div>

        <div class="bg-white shadow-lg rounded-xl p-6 border-t-4 border-green-500 transition duration-300 hover:shadow-xl">
            <div class="flex items-center justify-between">
                <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider">Penelitian Aktif</h3>
                <svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
            </div>
            <p class="mt-4 text-3xl font-extrabold text-green-600">
                {{ $penelitianAktif }}
            </p>
            <span class="text-xs text-gray-500 mt-2 block">Proyek dalam tahap pelaksanaan</span>
        </div>

        <div class="bg-white shadow-lg rounded-xl p-6 border-t-4 border-yellow-500 transition duration-300 hover:shadow-xl">
            <div class="flex items-center justify-between">
                <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider">Pengabdian Aktif</h3>
                <svg class="w-8 h-8 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
            </div>
            <p class="mt-4 text-3xl font-extrabold text-yellow-600">
                {{ $pengabdianAktif }}
            </p>
            <span class="text-xs text-gray-500 mt-2 block">Kegiatan dalam tahap pelaksanaan</span>
        </div>

        <div class="bg-white shadow-lg rounded-xl p-6 border-t-4 border-red-500 transition duration-300 hover:shadow-xl">
            <div class="flex items-center justify-between">
                <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider">Menunggu Review</h3>
                <svg class="w-8 h-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <p class="mt-4 text-3xl font-extrabold text-red-600">
                {{ $menungguReview }}
            </p>
            <span class="text-xs text-gray-500 mt-2 block">Proposal belum diputuskan</span>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <div class="lg:col-span-2">
            <div class="bg-white shadow-lg rounded-xl p-6 border border-gray-100 h-full">
                <h3 class="text-xl font-bold text-gray-900 mb-4">📈 Perbandingan Status Usulan</h3>
                <p class="text-sm text-gray-500 mb-6">Visualisasi status keseluruhan usulan yang pernah diajukan (Penelitian & Pengabdian). Total data: **{{ $totalForChart }}**</p>
                
                {{-- Placeholder Chart menggunakan Progress Bar --}}
                @if ($totalForChart > 0)
                    <div class="space-y-4">
                        
                        <div>
                            <div class="flex justify-between text-sm font-medium mb-1">
                                <span class="text-green-600">Diterima: {{ $usulanDisetujui }}</span>
                                <span class="text-green-600">{{ $percentDisetujui }}%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2.5">
                                <div class="bg-green-500 h-2.5 rounded-full" style="width: {{ $percentDisetujui }}%"></div>
                            </div>
                        </div>

                        <div>
                            <div class="flex justify-between text-sm font-medium mb-1">
                                <span class="text-red-600">Ditolak: {{ $usulanDitolak }}</span>
                                <span class="text-red-600">{{ $percentDitolak }}%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2.5">
                                <div class="bg-red-500 h-2.5 rounded-full" style="width: {{ $percentDitolak }}%"></div>
                            </div>
                        </div>

                        <div>
                            <div class="flex justify-between text-sm font-medium mb-1">
                                <span class="text-yellow-600">Menunggu Review: {{ $menungguReview }}</span>
                                <span class="text-yellow-600">{{ $percentReview }}%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2.5">
                                <div class="bg-yellow-500 h-2.5 rounded-full" style="width: {{ $percentReview }}%"></div>
                            </div>
                        </div>
                    </div>
                  
                @else
                    <div class="h-48 flex items-center justify-center bg-gray-50 rounded-lg border border-dashed border-gray-200">
                        <p class="text-gray-400 font-semibold">Data usulan tidak ditemukan untuk chart.</p>
                    </div>
                @endif
            </div>
        </div>

        <div class="lg:col-span-1">
            <div class="bg-white shadow-lg rounded-xl p-6 border border-gray-100 h-full">
                <h3 class="text-xl font-bold text-gray-900 mb-4">🚀 Progres Usulan Terakhir</h3>
                
                @if ($latestProposal)
                    <p class="text-md font-bold text-blue-600 mb-4 truncate">{{ $latestProposal->judul ?? 'Judul Usulan Terakhir' }}</p>
                    <p class="text-xs text-gray-500 mb-4">Status Saat Ini: <span class="font-semibold text-gray-900">{{ $currentStatus }}</span></p>

                    <div class="relative pl-4">
                        {{-- Garis vertikal timeline (Menggunakan $lineColor) --}}
                        <div class="absolute left-0 top-0 bottom-0 w-0.5 {{ $lineColor }}"></div>

                        @foreach ($trackingSteps as $key => $step)
                            <div class="relative mb-6">
                                {{-- Ikon Status --}}
                                @if ($step['is_complete'])
                                    <div class="absolute -left-2.5 top-0 w-5 h-5 bg-blue-500 rounded-full flex items-center justify-center text-white z-10">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    </div>
                                @else
                                    <div class="absolute -left-2.5 top-0 w-5 h-5 bg-gray-300 rounded-full z-10"></div>
                                @endif
                                
                                <div class="ml-4 pt-0.5">
                                    <p class="text-sm font-semibold {{ $step['is_complete'] ? 'text-gray-900' : 'text-gray-500' }}">{{ $step['label'] }}</p>
                                    @if ($key == $currentStatus && !$isFinished)
                                        <p class="text-xs text-blue-500 font-medium">Status terbaru</p>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                        
                        {{-- Status Akhir (Berjalan/Ditolak) --}}
                        <div class="relative mb-0">
                            @if ($isRejected)
                                <div class="absolute -left-2.5 top-0 w-5 h-5 bg-red-500 rounded-full flex items-center justify-center text-white z-10">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                </div>
                                <div class="ml-4 pt-0.5">
                                    <p class="text-sm font-semibold text-red-600">Proses Dihentikan (Ditolak)</p>
                                </div>
                            @elseif ($isFinished)
                                <div class="absolute -left-2.5 top-0 w-5 h-5 bg-green-500 rounded-full flex items-center justify-center text-white z-10">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                </div>
                                <div class="ml-4 pt-0.5">
                                    <p class="text-sm font-semibold text-green-600">Proyek Aktif / Sedang Berjalan</p>
                                </div>
                            @endif
                        </div>

                    </div>
                    
                    <a href="{{ route('dosen.usulan.index') }}?id={{ $latestProposal->id }}" class="mt-6 inline-block text-sm font-medium text-blue-500 hover:text-blue-700 transition">
                        Lihat Detail Progres &rarr;
                    </a>
                @else
                    <div class="text-center text-gray-500 py-10">
                        <svg class="w-10 h-10 mx-auto mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                        <p>Tidak ada usulan yang ditemukan.</p>
                        <a href="{{ route('dosen.usulan.create') ?? '#' }}" class="mt-2 inline-block text-sm font-medium text-blue-500 hover:text-blue-700 transition">Buat Usulan Baru &rarr;</a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection