@extends('reviewer.layouts.app')

@section('title', 'Dashboard Reviewer')

@section('content')

<header class="bg-white shadow-sm border-b border-gray-200">
    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold leading-tight text-gray-900">
            Dashboard Reviewer
        </h1>
        <p class="mt-1 text-base text-gray-600">
            Selamat datang, {{ Auth::user()->name }}! 👋 Kelola usulan yang perlu Anda review.
        </p>
    </div>
</header>

<hr class="border-gray-300 my-4">

<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">

    ## 📊 Ringkasan Usulan

    {{-- Statistik Cards --}}
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">

        {{-- Total Usulan --}}
        <div class="bg-white overflow-hidden shadow-lg rounded-xl border border-blue-100">
            <div class="p-5 flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-medium text-gray-500 truncate">Total Usulan</h3>
                    <p class="mt-1 text-3xl font-semibold text-blue-600">{{ $total_usulan }}</p>
                </div>
                <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                </div>
            </div>
        </div>

        {{-- Belum Dinilai --}}
        <div class="bg-white overflow-hidden shadow-lg rounded-xl border border-yellow-100">
            <div class="p-5 flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-medium text-gray-500 truncate">Perlu Dinilai</h3>
                    <p class="mt-1 text-3xl font-semibold text-yellow-600">{{ $belum_dinilai }}</p>
                </div>
                <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
        </div>

        {{-- Sudah Dinilai --}}
        <div class="bg-white overflow-hidden shadow-lg rounded-xl border border-green-100">
            <div class="p-5 flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-medium text-gray-500 truncate">Telah Selesai</h3>
                    <p class="mt-1 text-3xl font-semibold text-green-600">{{ $sudah_dinilai }}</p>
                </div>
                <div class="p-3 rounded-full bg-green-100 text-green-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
        </div>

        {{-- Menunggu Revisi --}}
        <div class="bg-white overflow-hidden shadow-lg rounded-xl border border-purple-100">
            <div class="p-5 flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-medium text-gray-500 truncate">Menunggu Revisi</h3>
                    <p class="mt-1 text-3xl font-semibold text-purple-600">{{ $menunggu_revisi }}</p>
                </div>
                <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 4v4m0 0l-4-4m4 4l4-4"></path></svg>
                </div>
            </div>
        </div>

    </div>

    <hr class="border-gray-300 my-8">

    ## 📝 Daftar Usulan untuk Direview

    <div class="mt-8 bg-white shadow-lg rounded-xl">
        <div class="p-6">

            {{-- Filter dan Pencarian --}}
            <form method="GET" action="{{ route('reviewer.dashboard') }}" class="mb-6">
                <div class="flex flex-col md:flex-row justify-between items-center space-y-4 md:space-y-0 md:space-x-4">
                    
                    {{-- Input Pencarian --}}
                    <div class="w-full md:w-1/3">
                        <input type="text" name="search" placeholder="Cari Judul/Pengusul..."
                               class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
                               value="{{ request('search') }}">
                    </div>

                    <div class="w-full md:w-auto flex space-x-4">
                        {{-- Filter Skema --}}
                        <select name="skema" class="border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Semua Skema</option>
                            @foreach ($skema_list as $skema)
                                <option value="{{ $skema }}" {{ request('skema') == $skema ? 'selected' : '' }}>{{ $skema }}</option>
                            @endforeach
                        </select>
                        
                        {{-- Filter Status --}}
                        <select name="status" class="border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Semua Status</option>
                            <option value="assigned" {{ request('status') == 'assigned' ? 'selected' : '' }}>Perlu Dinilai</option>
                            <option value="done" {{ request('status') == 'done' ? 'selected' : '' }}>Selesai Review</option>
                            <option value="revisi" {{ request('status') == 'revisi' ? 'selected' : '' }}>Revisi Diajukan</option>
                        </select>

                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg shadow hover:bg-blue-700 transition">Filter</button>
                        
                        {{-- Tombol reset --}}
                        @if (request()->hasAny(['search', 'status', 'skema']))
                            <a href="{{ route('reviewer.dashboard') }}" class="bg-gray-300 text-gray-800 px-4 py-2 rounded-lg shadow hover:bg-gray-400 transition flex items-center">Reset</a>
                        @endif
                    </div>
                </div>
            </form>

            {{-- Tabel Usulan --}}
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Judul Usulan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pengusul</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Skema</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Masuk</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status Review</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($usulan as $item)
                            <tr class="hover:bg-gray-50 transition duration-150">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ \Illuminate\Support\Str::limit($item->judul, 50) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $item->pengusul_name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $item->skema }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $item->created_at_formatted }}</td>

                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if ($item->review_status == 'assigned')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Perlu Dinilai</span>
                                    @elseif ($item->review_status == 'done')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Selesai Review</span>
                                    @elseif ($item->review_status == 'revisi')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800">Revisi Diajukan</span>
                                    @else
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-200 text-gray-700">{{ ucwords($item->review_status) }}</span>
                                    @endif
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                    {{-- PERBAIKAN: Menggunakan $item->usulan_id (ID usulan) atau $item->review_id (ID review) --}}
                                    <a href="{{ route('reviewer.usulan.index', $item->usulan_id) }}"
                                       class="text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2 text-center transition duration-150 ease-in-out inline-flex items-center shadow-md">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H10v-2.828l8.586-8.586z"></path></svg>
                                        Mulai Review
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                                    🎉 Tidak ada usulan baru yang perlu direview saat ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            {{-- Paginasi --}}
            <div class="mt-4">
                {{-- Mempertahankan filter dan search saat navigasi halaman --}}
                {{ $usulan->appends(request()->except('page'))->links() }}
            </div>

        </div>
    </div>
</div>

@endsection