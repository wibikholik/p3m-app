@extends('dosen.layouts.app')

@section('title', 'Pengumuman Dosen')
@section('page-title', 'Pengumuman & Hibah')

@section('content')
<div class="max-w-7xl mx-auto mt-8 px-4 sm:px-6 lg:px-8">

    {{-- Header Section & Filter --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8 gap-4">
        <div>
            <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight">Daftar Pengumuman</h2>
            <p class="mt-1 text-sm text-gray-500">Temukan peluang penelitian, pengabdian, dan informasi terbaru.</p>
        </div>

        {{-- Filter Form --}}
        <div class="w-full md:w-auto">
            <form action="{{ route('dosen.pengumuman.index') }}" method="GET" class="flex items-center shadow-sm rounded-md">
                <div class="relative flex-grow focus-within:z-10">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M3 3a1 1 0 011-1h12a1 1 0 011 1v3a1 1 0 01-.293.707L12 11.414V15a1 1 0 01-.293.707l-2 2A1 1 0 018 17v-5.586L3.293 6.707A1 1 0 013 6V3z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <select name="kategori" onchange="this.form.submit()" class="block w-full md:w-64 pl-10 pr-10 py-2.5 border-gray-300 rounded-l-md focus:ring-blue-500 focus:border-blue-500 sm:text-sm bg-white text-gray-700">
                        <option value="">Semua Kategori</option>
                        @foreach ($kategori as $kat)
                            <option value="{{ $kat->id }}" {{ request('kategori') == $kat->id ? 'selected' : '' }}>
                                {{ $kat->nama_kategori }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="-ml-px relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-r-md text-white bg-blue-600 hover:bg-blue-700 focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                    Cari
                </button>
            </form>
        </div>
    </div>

    {{-- Grid Pengumuman --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @forelse ($pengumuman as $item)
            @php
                $now = now();
                // Pastikan field tanggal ada di database
                $start = $item->tanggal_mulai ? \Carbon\Carbon::parse($item->tanggal_mulai) : null;
                $end = $item->tanggal_akhir ? \Carbon\Carbon::parse($item->tanggal_akhir) : null;
                
                // Cek Aktif
                $isAktif = $start && $end && $now->between($start, $end);
                
                // Hitung sisa hari (jika aktif)
                $sisaHari = $isAktif ? $now->diffInDays($end) : 0;
            @endphp

            <div class="group flex flex-col bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
                
                {{-- Image Wrapper --}}
                <div class="relative h-48 w-full overflow-hidden bg-gray-100">
                    {{-- Badge Kategori --}}
                    <div class="absolute top-3 left-3 z-10">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-white/90 text-blue-800 shadow-sm backdrop-blur-sm">
                            {{ $item->kategori->nama_kategori ?? 'Umum' }}
                        </span>
                    </div>

                    @if ($item->gambar)
                        <img src="{{ asset('storage/' . $item->gambar) }}" alt="{{ $item->judul }}" class="h-full w-full object-cover group-hover:scale-105 transition-transform duration-500">
                    @else
                        <div class="flex items-center justify-center h-full w-full text-gray-300">
                            <svg class="h-16 w-16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                    @endif

                    {{-- Status Overlay (Jika Berakhir) --}}
                    @if (!$isAktif && $end && $now->greaterThan($end))
                        <div class="absolute inset-0 bg-gray-900/60 flex items-center justify-center">
                            <span class="text-white font-bold tracking-wider border-2 border-white px-4 py-1 rounded transform -rotate-12">BERAKHIR</span>
                        </div>
                    @endif
                </div>

                {{-- Content Body --}}
                <div class="flex-1 p-5 flex flex-col">
                    
                    {{-- Judul --}}
                    <a href="{{ route('dosen.pengumuman.show', $item->id) }}" class="block mt-2">
                        <h3 class="text-xl font-bold text-gray-900 hover:text-blue-600 transition-colors line-clamp-2">
                            {{ $item->judul }}
                        </h3>
                    </a>

                    {{-- Tanggal --}}
                    <div class="mt-3 flex items-center text-sm text-gray-500">
                        <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                        </svg>
                        <span>
                            @if ($start && $end)
                                {{ $start->format('d M Y') }} - {{ $end->format('d M Y') }}
                            @else
                                Jadwal belum ditentukan
                            @endif
                        </span>
                    </div>

                    {{-- Sisa Hari (Jika Aktif) --}}
                    @if ($isAktif)
                        <div class="mt-2">
                            @if($sisaHari <= 3)
                                <span class="text-xs font-semibold text-red-600 bg-red-100 px-2 py-1 rounded animate-pulse">
                                    Segera Berakhir: {{ $sisaHari }} hari lagi!
                                </span>
                            @else
                                <span class="text-xs font-semibold text-green-600 bg-green-100 px-2 py-1 rounded">
                                    Tersisa {{ $sisaHari }} hari
                                </span>
                            @endif
                        </div>
                    @endif
                </div>

                {{-- Footer Actions --}}
                <div class="p-5 pt-0 mt-auto">
                    <div class="border-t border-gray-100 pt-4 flex gap-3">
                        {{-- Tombol Detail --}}
                        <a href="{{ route('dosen.pengumuman.show', $item->id) }}" 
                           class="flex-1 inline-flex justify-center items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Detail
                        </a>

                        {{-- Tombol Ajukan --}}
                        @if ($isAktif)
                            <a href="{{ route('dosen.usulan.create', $item->id) }}" 
                               class="flex-1 inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 shadow-sm">
                                Ajukan
                            </a>
                        @else
                            <button disabled class="flex-1 inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-gray-400 bg-gray-100 cursor-not-allowed">
                                Ditutup
                            </button>
                        @endif
                    </div>
                </div>
            </div>

        @empty
            {{-- Empty State --}}
            <div class="col-span-full flex flex-col items-center justify-center py-16 px-4 text-center bg-gray-50 rounded-xl border-2 border-dashed border-gray-300">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">Belum ada pengumuman</h3>
                <p class="mt-1 text-sm text-gray-500">Saat ini belum ada pengumuman atau hibah yang dibuka sesuai kategori yang dipilih.</p>
                <div class="mt-6">
                    <a href="{{ route('dosen.pengumuman.index') }}" class="text-blue-600 hover:text-blue-500 font-medium">
                        Reset Filter &rarr;
                    </a>
                </div>
            </div>
        @endforelse
    </div>
</div>
@endsection