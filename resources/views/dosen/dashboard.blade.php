@extends('dosen.layouts.app')

@section('title', 'Dashboard')

@section('content')

<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
    <!-- Greeting Section -->
    <div class="bg-white overflow-hidden shadow-xl rounded-xl p-8 mb-8 border border-gray-100">
        <div class="text-2xl font-semibold text-gray-900 flex items-center">
            👋 Selamat datang kembali, <span class="text-blue-600 ml-2">{{ Auth::user()->name }}</span>!
        </div>
        <p class="mt-2 text-gray-600">Ini adalah halaman ringkasan aktivitas penelitian dan pengabdian Anda.</p>
    </div>

    <h2 class="text-3xl font-extrabold text-gray-900 mb-6">Ringkasan Aktivitas</h2>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        
        <!-- Total Proposal Saya -->
        <div class="bg-white shadow-lg rounded-xl p-6 border-t-4 border-blue-500 transition duration-300 hover:shadow-xl">
            <div class="flex items-center justify-between">
                <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider">Total Usulan (Penelitian & Pengabdian)</h3>
                <svg class="w-8 h-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            </div>
            <p class="mt-4 text-3xl font-extrabold text-blue-600">
                5
            </p>
            <a href="{{ route('dosen.usulan.index') }}" class="text-xs text-blue-500 hover:text-blue-700 font-medium mt-2 block">Lihat Daftar Usulan &rarr;</a>
        </div>

        <!-- Penelitian Aktif -->
        <div class="bg-white shadow-lg rounded-xl p-6 border-t-4 border-green-500 transition duration-300 hover:shadow-xl">
            <div class="flex items-center justify-between">
                <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider">Penelitian Aktif</h3>
                <svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
            </div>
            <p class="mt-4 text-3xl font-extrabold text-green-600">
                2
            </p>
            <span class="text-xs text-gray-500 mt-2 block">Proyek dalam tahap pelaksanaan</span>
        </div>

        <!-- Pengabdian Aktif -->
        <div class="bg-white shadow-lg rounded-xl p-6 border-t-4 border-yellow-500 transition duration-300 hover:shadow-xl">
            <div class="flex items-center justify-between">
                <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider">Pengabdian Aktif</h3>
                <svg class="w-8 h-8 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
            </div>
            <p class="mt-4 text-3xl font-extrabold text-yellow-600">
                1
            </p>
            <span class="text-xs text-gray-500 mt-2 block">Kegiatan dalam tahap pelaksanaan</span>
        </div>

        <!-- Proposal Pending Review (Contoh) -->
        <div class="bg-white shadow-lg rounded-xl p-6 border-t-4 border-red-500 transition duration-300 hover:shadow-xl">
            <div class="flex items-center justify-between">
                <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider">Menunggu Review</h3>
                <svg class="w-8 h-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <p class="mt-4 text-3xl font-extrabold text-red-600">
                1
            </p>
            <span class="text-xs text-gray-500 mt-2 block">Proposal belum diputuskan</span>
        </div>
    </div>
</section>

@endsection