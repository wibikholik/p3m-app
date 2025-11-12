@extends('dosen.layouts.app')

@section('title', 'Detail Usulan')

@section('page-title', 'Detail Usulan')

@section('content')
<div class="max-w-4xl mx-auto mt-6 p-6 bg-white rounded-lg shadow">

    <h1 class="text-2xl font-bold mb-4">Detail Usulan: {{ $usulan->judul }}</h1>
    
    {{-- Kembali --}}
    <div class="text-right mb-4">
        <a href="{{ route('dosen.usulan.index') }}" class="text-blue-600 hover:underline">
            ← Kembali ke Daftar Usulan Saya
        </a>
    </div>

    {{-- KOTAK STATUS BARU --}}
    <div class="mb-4 p-4 rounded-lg
        @if($usulan->status == 'Draft') bg-yellow-100 text-yellow-800
        @elseif($usulan->status == 'Menunggu Persetujuan') bg-blue-100 text-blue-800
        @elseif($usulan->status == 'Disetujui') bg-green-100 text-green-800
        @else bg-red-100 text-red-800
        @endif
    ">
        <strong>Status Saat Ini:</strong> {{ $usulan->status }}
        
        @if($usulan->status == 'Draft')
            <p class="text-sm">Usulan ini belum diajukan ke admin. Silakan lengkapi data dan submit.</p>
        @elseif($usulan->status == 'Menunggu Persetujuan')
            <p class="text-sm">Usulan sedang direview oleh admin. Anda tidak dapat mengubah data ini lagi.</p>
        @endif
    </div>


    {{-- Info Usulan --}}
    <div class="mb-6">
        <p><strong>Skema:</strong> {{ $usulan->skema }}</p>
        <p><strong>Deskripsi:</strong> {{ $usulan->deskripsi }}</p>
        <p><strong>Tahun Pelaksanaan:</strong> {{ $usulan->tahun_pelaksanaan }}</p>
        <p>
            <strong>File Proposal:</strong> 
            {{-- DIKOREKSI: Menggunakan file_lampiran (sesuai controller store) --}}
            @if($usulan->file_lampiran)
                <a href="{{ asset('storage/'.$usulan->file_lampiran) }}" target="_blank" class="text-blue-600 hover:underline">
                    Lihat Proposal
                </a>
            @else
                <span class="text-red-500">Belum diunggah</span>
            @endif
        </p>
    </div>

    {{-- RAB --}}
    <div class="mb-6">
        <h2 class="text-lg font-semibold mb-2">Rencana Anggaran Biaya (RAB)</h2>
        @if($usulan->rabs && $usulan->rabs->count() > 0)
            <table class="w-full border-collapse border border-gray-300">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="border px-3 py-1">No</th>
                        <th class="border px-3 py-1">Nama Item</th>
                        <th class="border px-3 py-1">Jumlah</th>
                        <th class="border px-3 py-1">Harga Satuan</th>
                        <th class="border px-3 py-1">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @php $totalRab = 0; @endphp
                    @foreach($usulan->rabs as $index => $rab)
                        @php $subtotal = $rab->jumlah * $rab->harga_satuan; $totalRab += $subtotal; @endphp
                        <tr>
                            <td class="border px-3 py-1">{{ $index + 1 }}</td>
                            <td class="border px-3 py-1">{{ $rab->nama_item }}</td>
                            <td class="border px-3 py-1">{{ $rab->jumlah }}</td>
                            <td class="border px-3 py-1">Rp {{ number_format($rab->harga_satuan, 0, ',', '.') }}</td>
                            <td class="border px-3 py-1">Rp {{ number_format($subtotal, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                    <tr class="font-bold bg-gray-100">
                        <td colspan="4" class="border px-3 py-1 text-right">Total</td>
                        <td class="border px-3 py-1">Rp {{ number_format($totalRab, 0, ',', '.') }}</td>
                    </tr>
                </tbody>
            </table>
        @else
            <p class="text-red-500">Belum ada RAB yang diinput.</p>
        @endif
    </div>

    {{-- Anggota --}}
    <div class="mb-6">
        <h2 class="text-lg font-semibold mb-2">Anggota</h2>
        @if($usulan->anggotas && $usulan->anggotas->count() > 0)
            <ul class="list-disc pl-5">
                @foreach($usulan->anggotas as $anggota)
                    <li>{{ $anggota->user->name }} @if($anggota->peran) ({{ $anggota->peran }}) @endif</li>
                @endforeach
            </ul>
        @else
            <p>Belum ada anggota yang ditambahkan.</p>
        @endif
    </div>


    {{-- ====================================================== --}}
    {{-- BLOK AKSI BARU (HANYA MUNCUL JIKA STATUS DRAFT) --}}
    {{-- ====================================================== --}}
    @if($usulan->status == 'Draft')
        <hr class="my-6">
        <div class="p-4 bg-gray-50 rounded-lg">
            <h2 class="text-lg font-semibold mb-3">Tindakan (Status Draft)</h2>

            @php
                // Cek kelengkapan data
                $proposalLengkap = !empty($usulan->file_lampiran);
                $rabLengkap = $usulan->rabs && $usulan->rabs->count() > 0;
                $bisaSubmit = $proposalLengkap && $rabLengkap; // Tentukan syarat submit (misal: proposal & RAB wajib)
            @endphp

            <p class="mb-4">Usulan ini masih dalam status Draft. Harap lengkapi data sebelum mengajukan ke admin.</p>

            <!-- Checklist Kelengkapan -->
            <ul class="mb-4 space-y-2">
                <li class="flex items-center">
                    @if($proposalLengkap)
                        <span class="text-green-500 mr-2">✔</span> File Proposal sudah diunggah.
                    @else
                        <span class="text-red-500 mr-2">✘</span> File Proposal belum diunggah.
                    @endif
                </li>
                <li class="flex items-center">
                    @if($rabLengkap)
                        <span class="text-green-500 mr-2">✔</span> Rencana Anggaran Biaya (RAB) sudah diisi.
                    @else
                        <span class="text-red-500 mr-2">✘</span> Rencana Anggaran Biaya (RAB) belum diisi.
                    @endif
                </li>
            </ul>

            <!-- Tombol Aksi -->
            <div class="flex gap-3">
                
                {{-- Tombol Edit: Anda perlu membuat route dan fungsi 'edit' & 'update' di controller --}}
                <a href="{{ route('dosen.usulan.edit', $usulan->id) }}" class="bg-blue-600 text-white px-4 py-2 rounded">
                    Edit/Lengkapi Usulan
                </a>

                @if($bisaSubmit)
                    {{-- Tombol Submit: Anda perlu membuat route dan fungsi 'submitUsulan' di controller --}}
                    <form action="{{ route('dosen.usulan.submit', $usulan->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin mensubmit usulan ini? Data tidak dapat diubah lagi.');">
                        @csrf
                        @method('POST') {{-- Atau PATCH/PUT, sesuaikan dengan route Anda --}}
                        <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded">
                            Submit Usulan ke Admin
                        </button>
                    </form>
                @else
                    {{-- Tombol Submit dimatikan jika belum lengkap --}}
                    <button type="button" class="bg-gray-300 text-gray-500 px-4 py-2 rounded" disabled>
                        Submit Usulan (Lengkapi data dulu)
                    </button>
                @endif
            </div>
        </div>
    @endif

</div>
@endsection