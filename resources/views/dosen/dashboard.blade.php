@extends('dosen.layouts.app')

@section('title', 'Dashboard Dosen')

@section('content')

<div class="max-w-6xl mx-auto mt-6">

    {{-- ====== PROFILE CARD ====== --}}
    <div class="bg-white shadow-md p-6 rounded-xl flex items-center gap-4 border border-gray-100">
        <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=0D8ABC&color=fff&size=90"
             class="w-20 h-20 rounded-full border" alt="avatar">

        <div>
            <h2 class="text-xl font-bold text-gray-800">{{ Auth::user()->name }}</h2>
            <p class="text-sm text-gray-500">Dosen</p>
            <p class="text-sm text-gray-500">
                Subang, Compeng, Indonesia
            </p>
        </div>

        <div class="ml-auto flex items-center gap-4 text-gray-600 text-xl">
            <i class="fa-solid fa-bell cursor-pointer"></i>
            <i class="fa-solid fa-gear cursor-pointer"></i>
        </div>
    </div>

    {{-- ====== STATUS USULAN ====== --}}
    <div class="bg-white shadow-md p-6 rounded-xl mt-6 border border-gray-100">
        <h3 class="text-lg font-semibold text-gray-700 mb-6">Status Usulan Terakhir</h3>

        <div class="flex justify-between text-center">
            @php
                $steps = ['Mengusulkan','Seleksi administrasi','Sedang direview','Pelaksanaan'];
                $currentStep = 2; // contoh tahap yang sedang berjalan
            @endphp

            @foreach($steps as $index => $label)
                <div class="flex flex-col items-center">
                    <div class="w-4 h-4 rounded-full 
                        {{ $index == $currentStep ? 'bg-blue-500' : 'bg-gray-400' }}">
                    </div>
                    <p class="text-xs text-gray-600 mt-2">{{ $label }}</p>
                </div>
            @endforeach
        </div>
    </div>


    {{-- ====== INFORMASI PRIBADI ====== --}}
    <div class="bg-white shadow-md p-6 rounded-xl mt-6 border border-gray-100">
        <h3 class="text-lg font-semibold text-gray-700 mb-4">Informasi Pribadi</h3>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 text-sm text-gray-700">

            <div>
                <strong>Nama Lengkap</strong>
                <p>{{ Auth::user()->name }}</p>
            </div>

            <div>
                <strong>NIDN/NIDK</strong>
                <p>{{ Auth::user()->nidn ?? '-' }}</p>
            </div>

            <div>
                <strong>Tempat/Tanggal Lahir</strong>
                <p>{{ Auth::user()->ttl ?? '-' }}</p>
            </div>

            <div>
                <strong>Email</strong>
                <p>{{ Auth::user()->email }}</p>
            </div>

            <div>
                <strong>Instansi</strong>
                <p>Politeknik Negeri Subang</p>
            </div>

            <div>
                <strong>No KTP</strong>
                <p>{{ Auth::user()->ktp ?? '-' }}</p>
            </div>

            <div>
                <strong>Jabatan Akademik</strong>
                <p>{{ Auth::user()->jabatan ?? 'Dosen' }}</p>
            </div>

            <div>
                <strong>Program Studi</strong>
                <p>TRPL</p>
            </div>
        </div>
    </div>


    {{-- ====== INFORMASI RIWAYAT PENELITIAN ====== --}}
    <div class="bg-white shadow-md p-6 rounded-xl mt-6 border border-gray-100">
        <h3 class="text-lg font-semibold text-gray-700 mb-4">Informasi Penelitian & Pengabdian Anda</h3>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 text-sm text-gray-700">

            <div>
                <strong>Penelitian</strong>
                <p>{{ $penelitian ?? 0 }}</p>
            </div>

            <div>
                <strong>Pengabdian</strong>
                <p>{{ $pengabdian ?? 0 }}</p>
            </div>

            <div>
                <strong>Artikel Internasional</strong>
                <p>{{ $artikel ?? 0 }}</p>
            </div>

            <div>
                <strong>Sinta Score Overall</strong>
                <p>{{ $sinta ?? 0 }}</p>
            </div>

            <div>
                <strong>HKI</strong>
                <p>{{ $hki ?? 0 }}</p>
            </div>

            <div>
                <strong>Buku</strong>
                <p>{{ $buku ?? 0 }}</p>
            </div>

            <div>
                <strong>Scopus H-index</strong>
                <p>{{ $hindex ?? 0 }}</p>
            </div>

            <div>
                <strong>Sinta Score 3yr</strong>
                <p>{{ $sinta3 ?? 0 }}</p>
            </div>
        </div>
    </div>

</div>

@endsection
